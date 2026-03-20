<?php

namespace Botble\Tours\Providers;

use Botble\Base\Facades\DashboardMenu;
use Botble\Base\Facades\PanelSectionManager;
use Botble\Base\PanelSections\PanelSectionItem;
use Botble\Base\Supports\DashboardMenuItem;
use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Setting\PanelSections\SettingOthersPanelSection;
use Botble\Tours\Models\Tour;
use Botble\Tours\Models\TourBooking;
use Botble\Tours\Models\TourCategory;
use Botble\Tours\Models\TourCity;
use Botble\Tours\Models\TourReview;
use Botble\Tours\Models\TourFaq;
use Botble\Tours\Models\TourLanguage;
use Botble\Tours\Models\TourPlace;
use Botble\Tours\Models\TourSchedule;
use Botble\Tours\Models\TourTimeSlot;
use Botble\Tours\Repositories\Caches\TourCacheDecorator;
use Botble\Tours\Repositories\Caches\TourCategoryCacheDecorator;
use Botble\Tours\Repositories\Caches\TourCityCacheDecorator;
use Botble\Tours\Repositories\Caches\TourLanguageCacheDecorator;
use Botble\Tours\Repositories\Caches\TourReviewCacheDecorator;
use Botble\Tours\Repositories\Eloquent\TourBookingRepository;
use Botble\Tours\Repositories\Eloquent\TourCategoryRepository;
use Botble\Tours\Repositories\Eloquent\TourCityRepository;
use Botble\Tours\Repositories\Eloquent\TourRepository;
use Botble\Tours\Repositories\Eloquent\TourReviewRepository;
use Botble\Tours\Repositories\Eloquent\TourFaqRepository;
use Botble\Tours\Repositories\Eloquent\TourLanguageRepository;
use Botble\Tours\Repositories\Eloquent\TourPlaceRepository;
use Botble\Tours\Repositories\Eloquent\TourScheduleRepository;
use Botble\Tours\Repositories\Eloquent\TourTimeSlotRepository;
use Botble\Tours\Repositories\Interfaces\TourBookingInterface;
use Botble\Tours\Repositories\Interfaces\TourCategoryInterface;
use Botble\Tours\Repositories\Interfaces\TourCityInterface;
use Botble\Tours\Repositories\Interfaces\TourInterface;
use Botble\Tours\Repositories\Interfaces\TourReviewInterface;
use Botble\Tours\Repositories\Interfaces\TourFaqInterface;
use Botble\Tours\Repositories\Interfaces\TourLanguageInterface;
use Botble\Tours\Repositories\Interfaces\TourPlaceInterface;
use Botble\Tours\Repositories\Interfaces\TourScheduleInterface;
use Botble\Tours\Repositories\Interfaces\TourTimeSlotInterface;
use Botble\Slug\Facades\SlugHelper;
use Illuminate\Support\Facades\Event;
use Botble\Tours\Providers\HookServiceProvider;
use Botble\Tours\Providers\CommandServiceProvider;
use Botble\Tours\Providers\EventServiceProvider;
use Botble\SeoHelper\Facades\SeoHelper;

class ToursServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register(): void
    {
        $this->app->bind(TourInterface::class, function () {
            return new TourCacheDecorator(
                new TourRepository(new Tour)
            );
        });

        $this->app->bind(TourCategoryInterface::class, function () {
            return new TourCategoryCacheDecorator(
                new TourCategoryRepository(new TourCategory)
            );
        });

        $this->app->bind(TourCityInterface::class, function () {
            return new TourCityCacheDecorator(
                new TourCityRepository(new TourCity)
            );
        });

        $this->app->bind(TourBookingInterface::class, function () {
            return new TourBookingRepository(new TourBooking());
        });

        $this->app->bind(TourReviewInterface::class, function () {
            return new TourReviewCacheDecorator(
                new TourReviewRepository(new TourReview())
            );
        });

        $this->app->bind(TourFaqInterface::class, function () {
            return new TourFaqRepository(new TourFaq());
        });

        $this->app->bind(TourPlaceInterface::class, function () {
            return new TourPlaceRepository(new TourPlace());
        });

        $this->app->bind(TourScheduleInterface::class, function () {
            return new TourScheduleRepository(new TourSchedule());
        });

        $this->app->bind(TourTimeSlotInterface::class, function () {
            return new TourTimeSlotRepository(new TourTimeSlot());
        });
        
        $this->app->bind(TourLanguageInterface::class, function () {
            return new TourLanguageCacheDecorator(
                new TourLanguageRepository(new TourLanguage())
            );
        });

        $this->app->register(CommandServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
        $this->app->register(HookServiceProvider::class);
        $this->app->register(TourSlugHookServiceProvider::class);
        $this->app->register(MarketplaceServiceProvider::class);
    }

    public function boot(): void
    {
        $this
            ->setNamespace('plugins/tours')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadRoutes()
            ->loadAndPublishViews()
            ->publishAssets();

        // Publish vendor form assets
        $this->publishes([
            __DIR__ . '/../../public' => public_path('vendor/core/plugins/tours'),
        ], 'tours-assets');
        
        // Publish customer dashboard views to theme
        if (class_exists('Botble\Ecommerce\Facades\EcommerceHelper')) {
            $this->publishes([
                __DIR__ . '/../../resources/views/customers' => resource_path('views/vendor/plugins/ecommerce/themes/' . (theme_option('name', 'farmart') ?: 'farmart') . '/views/customers'),
            ], 'tours-customer-views');
        }

        // Load vendor routes for test environment  
        $this->loadRoutesFrom(__DIR__ . '/../../routes/vendor.php');
        
            
        // Load constants
        require_once plugin_path('tours/src/constants.php');

        // Register slug support
        SlugHelper::registerModule(Tour::class, 'Tours');
        SlugHelper::registerModule(TourCategory::class, 'Tour Categories');
        SlugHelper::registerModule(TourCity::class, 'Tour Cities');
        
        // Set default prefixes for permalinks
        SlugHelper::setPrefix(Tour::class, 'tours', true);
        SlugHelper::setPrefix(TourCategory::class, 'tour-categories', true);
        SlugHelper::setPrefix(TourCity::class, 'tour-cities', true);
        
        // Register SEO support
        SeoHelper::registerModule(Tour::class);
        SeoHelper::registerModule(TourCategory::class);
        SeoHelper::registerModule(TourCity::class);

        DashboardMenu::default()->beforeRetrieving(function (): void {
            DashboardMenu::make()
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-tours')
                        ->priority(500)
                        ->name('plugins/tours::tours.name')
                        ->icon('ti ti-map-pin')
                )
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-tours-list')
                        ->priority(0)
                        ->parentId('cms-plugins-tours')
                        ->name('plugins/tours::tours.tours')
                        ->icon('ti ti-list-check')
                        ->route('tours.index')
                )
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-tour-categories')
                        ->priority(10)
                        ->parentId('cms-plugins-tours')
                        ->name('plugins/tours::tour-categories.name')
                        ->icon('ti ti-folder')
                        ->route('tour-categories.index')
                )
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-tour-cities')
                        ->priority(15)
                        ->parentId('cms-plugins-tours')
                        ->name('plugins/tours::tour-cities.name')
                        ->icon('ti ti-building')
                        ->route('tour-cities.index')
                )
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-tour-languages')
                        ->priority(18)
                        ->parentId('cms-plugins-tours')
                        ->name('plugins/tours::tour-languages.name')
                        ->icon('ti ti-language')
                        ->route('tour-languages.index')
                )
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-tour-bookings')
                        ->priority(20)
                        ->parentId('cms-plugins-tours')
                        ->name('plugins/tours::tour-bookings.name')
                        ->icon('ti ti-calendar-check')
                        ->route('tour-bookings.index')
                )
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-tour-reviews')
                        ->priority(30)
                        ->parentId('cms-plugins-tours')
                        ->name('plugins/tours::tour-reviews.name')
                        ->icon('ti ti-star')
                        ->route('tour-reviews.index')
                )
                ->registerItem(
                    DashboardMenuItem::make()
                        ->id('cms-plugins-tour-enquiries')
                        ->priority(40)
                        ->parentId('cms-plugins-tours')
                        ->name('plugins/tours::tours.enquiries')
                        ->icon('ti ti-inbox')
                        ->route('tour-enquiries.index')
                );
        });

        PanelSectionManager::default()->beforeRendering(function (): void {
            PanelSectionManager::registerItem(
                SettingOthersPanelSection::class,
                fn () => PanelSectionItem::make('tours')
                    ->setTitle(trans('plugins/tours::tours.settings.title'))
                    ->withIcon('ti ti-settings')
                    ->withPriority(500)
                    ->withDescription(trans('plugins/tours::tours.settings.description'))
            );
        });

        // Register Customer Dashboard Menu Item (opens in new page, not inside dashboard)
        DashboardMenu::for('customer')->beforeRetrieving(function (): void {
            DashboardMenu::make()
                ->registerItem([
                    'id' => 'customer-tour-bookings',
                    'priority' => 999, // After logout
                    'name' => __('My Tour Bookings'),
                    'url' => fn () => route('customer.tour-bookings.index'),
                    'icon' => 'ti ti-ticket',
                ]);
        });

        // Register assets for the tour gallery
        Event::listen('theme.header.before', function () {
            if (is_plugin_active('tours') && (is_tour_page() || is_tours_page())) {
                echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />';
            }
        });
        
        Event::listen('theme.footer.after', function () {
            if (is_plugin_active('tours') && (is_tour_page() || is_tours_page())) {
                // Ensure plugin frontend script is loaded on tour pages
                echo '<script src="' . asset('vendor/core/plugins/tours/assets/js/tour.js') . '"></script>';
                echo '<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>';
                echo '<script>
                    document.addEventListener("DOMContentLoaded", function() {
                        Fancybox.bind("[data-fancybox]");
                    });
                </script>';
            }
        });
    }
}