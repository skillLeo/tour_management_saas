<?php

namespace Botble\Base\Supports;

use Botble\Base\Events\SystemUpdateAvailable;
use Botble\Base\Events\SystemUpdateCachesCleared;
use Botble\Base\Events\SystemUpdateCachesClearing;
use Botble\Base\Events\SystemUpdateChecked;
use Botble\Base\Events\SystemUpdateChecking;
use Botble\Base\Events\SystemUpdateDBMigrated;
use Botble\Base\Events\SystemUpdateDBMigrating;
use Botble\Base\Events\SystemUpdateDownloaded;
use Botble\Base\Events\SystemUpdateDownloading;
use Botble\Base\Events\SystemUpdateExtractedFiles;
use Botble\Base\Events\SystemUpdatePublished;
use Botble\Base\Events\SystemUpdatePublishing;
use Botble\Base\Events\SystemUpdateUnavailable;
use Botble\Base\Exceptions\MissingCURLExtensionException;
use Botble\Base\Exceptions\MissingZipExtensionException;
use Botble\Base\Facades\BaseHelper;
use Botble\Base\Services\ClearCacheService;
use Botble\Base\Supports\ValueObjects\CoreProduct;
use Botble\Setting\Facades\Setting;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use League\Flysystem\UnableToWriteFile;
use Throwable;
use ZipArchive;

/**
 * DO NOT MODIFY THIS FILE.
 */
final class Core
{
    private string $basePath;

    private string $coreDataFilePath;

    private string $version = '1.0.0';

    private string $minimumPhpVersion = '8.2.0';

    protected static array $coreFileData = [];

    public function __construct(
        private readonly CacheRepository $cache,
        private readonly Filesystem $files
    ) {
        $this->basePath = base_path();
        $this->coreDataFilePath = core_path('core.json');

        $this->parseDataFromCoreDataFile();
    }

    public static function make(): self
    {
        return app(self::class);
    }

    public function version(): string
    {
        return $this->version;
    }

    public function minimumPhpVersion(): string
    {
        return $this->minimumPhpVersion;
    }

    public function updateFilesAndDatabase(string $version): void
    {
        $this->updateFiles($version);
        $this->updateDatabase();
    }

    public function updateFiles(string $version): bool
    {
        $filePath = $this->getUpdatedFilePath($version);

        if (! $this->files->exists($filePath)) {
            return false;
        }

        $this->cleanCaches();

        $coreTempPath = storage_path('app/core.json');

        try {
            $this->files->copy($this->coreDataFilePath, $coreTempPath);
            $zip = new Zipper();

            $oldLibrary = base_path('vendor/maennchen/zipstream-php');
            if ($this->files->exists($oldLibrary)) {
                $this->files->deleteDirectory($oldLibrary);
            }

            $bootstrapCachePath = base_path('bootstrap/cache');

            @unlink($bootstrapCachePath . '/packages.php');
            @unlink($bootstrapCachePath . '/services.php');

            if ($zip->extract($filePath, $this->basePath)) {
                @unlink($bootstrapCachePath . '/packages.php');
                @unlink($bootstrapCachePath . '/services.php');

                $this->cleanCaches();

                $this->files->delete($filePath);

                SystemUpdateExtractedFiles::dispatch();

                $this->files->delete($coreTempPath);

                return true;
            }

            if ($this->files->exists($coreTempPath)) {
                $this->files->move($coreTempPath, $this->coreDataFilePath);
            }

            return false;
        } catch (Throwable $exception) {
            $bootstrapCachePath = base_path('bootstrap/cache');

            @unlink($bootstrapCachePath . '/packages.php');
            @unlink($bootstrapCachePath . '/services.php');

            if ($this->files->exists($coreTempPath)) {
                $this->files->move($coreTempPath, $this->coreDataFilePath);
            }

            $this->logError($exception);

            throw $exception;
        }
    }

    public function updateDatabase(): void
    {
        try {
            $wrongFile = database_path('migrations/media_folders_table.php');

            if ($this->files->exists($wrongFile)) {
                $this->files->delete($wrongFile);
            }

            $this->runMigrationFiles();
        } catch (Throwable $exception) {
            $this->logError($exception);

            throw $exception;
        }
    }

    public function publishUpdateAssets(): void
    {
        $this->publishCoreAssets();
        $this->publishPackagesAssets();
    }

    public function publishCoreAssets(): void
    {
        SystemUpdatePublishing::dispatch();

        $this->publishAssets(core_path());
    }

    public function publishPackagesAssets(): void
    {
        $this->publishAssets(package_path());

        $this->publishAssets(base_path('vendor'));

        SystemUpdatePublished::dispatch();
    }

    public function cleanCaches(): void
    {
        try {
            SystemUpdateCachesClearing::dispatch();

            ClearCacheService::make()->purgeAll();

            SystemUpdateCachesCleared::dispatch();

            self::$coreFileData = [];
        } catch (Throwable $exception) {
            $this->logError($exception);
        }
    }

    public function cleanUp(): void
    {
        $this->cleanCaches();
    }

    public function logError(Exception|Throwable $exception): void
    {
        BaseHelper::logError($exception);
    }

    private function publishPaths(): array
    {
        return IlluminateServiceProvider::pathsToPublish(null, 'cms-public');
    }

    public function publishAssets(string $path): void
    {
        foreach ($this->publishPaths() as $from => $to) {
            if (! Str::contains($from, $path)) {
                continue;
            }

            try {
                $this->files->ensureDirectoryExists(dirname($to));
                $this->files->copyDirectory($from, $to);
            } catch (Throwable $exception) {
                $this->logError($exception);
            }
        }
    }

    public function runMigrationFiles(): void
    {
        SystemUpdateDBMigrating::dispatch();

        $migrator = app('migrator');

        rescue(fn () => $migrator->run(database_path('migrations')));

        $paths = [
            core_path(),
            package_path(),
        ];

        foreach ($paths as $path) {
            foreach (BaseHelper::scanFolder($path) as $module) {
                $modulePath = BaseHelper::joinPaths([$path, $module]);

                if (! $this->files->isDirectory($modulePath)) {
                    continue;
                }

                $moduleMigrationPath = BaseHelper::joinPaths([$modulePath, 'database', 'migrations']);

                if ($this->files->isDirectory($moduleMigrationPath)) {
                    $migrator->run($moduleMigrationPath);
                }
            }
        }

        SystemUpdateDBMigrated::dispatch();
    }

    private function validateUpdateFile(string $filePath): void
    {
        if (! class_exists('ZipArchive', false)) {
            throw new MissingZipExtensionException();
        }

        $zip = new ZipArchive();

        if ($zip->open($filePath)) {
            if ($zip->getFromName('.env')) {
                throw ValidationException::withMessages([
                    'file' => 'The update file contains a .env file. Please remove it and try again.',
                ]);
            }

            /**
             * @var array{
             *     productId: string,
             *     source: string,
             *     apiUrl: string,
             *     apiKey: string,
             *     version: string,
             *     minimumPhpVersion?: string,
             * }|null $content
             */
            $content = json_decode($zip->getFromName('platform/core/core.json'), true);

            if (! $content) {
                throw ValidationException::withMessages([
                    'file' => 'The update file is invalid. Please contact us for support.',
                ]);
            }

            $validator = Validator::make($content, [
                'version' => ['required', 'string'],
                'minimumPhpVersion' => ['nullable', 'string'],
            ])->stopOnFirstFailure();

            if ($validator->passes()) {
                if (version_compare($content['version'], $this->version, '<')) {
                    $zip->close();

                    throw ValidationException::withMessages(
                        ['version' => 'The version of the update is lower than the current version.']
                    );
                }

                if (
                    isset($content['minimumPhpVersion']) &&
                    version_compare($content['minimumPhpVersion'], phpversion(), '>')
                ) {
                    $zip->close();

                    throw ValidationException::withMessages(
                        [
                            'minimumPhpVersion' => sprintf(
                                'The minimum PHP version required (v%s) for the update is higher than the current PHP version.',
                                $content['minimumPhpVersion']
                            ),
                        ]
                    );
                }
            } else {
                $zip->close();

                throw ValidationException::withMessages($validator->errors()->toArray());
            }
        }

        $zip->close();
    }

    private function parseDataFromCoreDataFile(): void
    {
        if (! $this->files->exists($this->coreDataFilePath)) {
            return;
        }

        $data = $this->getCoreFileData();

        $this->version = Arr::get($data, 'version', $this->version);
        $this->minimumPhpVersion = Arr::get($data, 'minimumPhpVersion', $this->minimumPhpVersion);
    }

    public function getCoreFileData(): array
    {
        if (self::$coreFileData) {
            return self::$coreFileData;
        }

        if ($this->cache->has('core_file_data') && $coreData = $this->cache->get('core_file_data')) {
            self::$coreFileData = $coreData;

            return $coreData;
        }

        return $this->getCoreFileDataFromDisk();
    }

    private function getCoreFileDataFromDisk(): array
    {
        try {
            $data = json_decode($this->files->get($this->coreDataFilePath), true) ?: [];

            self::$coreFileData = $data;

            $this->cache->put('core_file_data', $data, Carbon::now()->addMinutes(30));

            return $data;
        } catch (FileNotFoundException) {
            return [];
        }
    }

    private function getClientIpAddress(): string
    {
        $staticIp = config('core.base.general.static_ip');

        if ($staticIp && filter_var($staticIp, FILTER_VALIDATE_IP)) {
            return $staticIp;
        }

        return Helper::getIpFromThirdParty();
    }

    public function getServerIP(): string
    {
        return $this->getClientIpAddress();
    }

    private function parseProductUpdateResponse(Response $response): CoreProduct|false
    {
        $data = $response->json();

        if ($response->ok() && Arr::get($data, 'status')) {
            return new CoreProduct(
                Arr::get($data, 'update_id'),
                Arr::get($data, 'version'),
                Carbon::createFromFormat('Y-m-d', Arr::get($data, 'release_date')),
                trim((string) Arr::get($data, 'summary')),
                trim((string) Arr::get($data, 'changelog')),
                (bool) Arr::get($data, 'has_sql')
            );
        }

        return false;
    }

    private function getUpdatedFilePath(string $version): string
    {
        return $this->basePath . DIRECTORY_SEPARATOR . 'update_main_' . str_replace('.', '_', $version) . '.zip';
    }
}
