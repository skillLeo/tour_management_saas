<?php

namespace Botble\Tours\Providers;

use Botble\Tours\Console\Commands\BackfillTourSlugs;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands([
            BackfillTourSlugs::class,
        ]);
    }
}