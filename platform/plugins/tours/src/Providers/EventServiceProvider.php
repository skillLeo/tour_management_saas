<?php

namespace Botble\Tours\Providers;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Slug\Events\UpdatedSlugEvent;
use Botble\Tours\Listeners\SyncTourSlug;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UpdatedSlugEvent::class => [
            SyncTourSlug::class . '@handleUpdatedSlug',
        ],
        CreatedContentEvent::class => [
            SyncTourSlug::class . '@handleCreatedContent',
        ],
        UpdatedContentEvent::class => [
            SyncTourSlug::class . '@handleUpdatedContent',
        ],
    ];
}