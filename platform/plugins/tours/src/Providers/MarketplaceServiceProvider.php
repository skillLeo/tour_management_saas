<?php

namespace Botble\Tours\Providers;

use Botble\Base\Facades\DashboardMenu;
use Botble\Base\Supports\ServiceProvider;
use Botble\Marketplace\Models\Store;
use Botble\Tours\Models\Tour;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

class MarketplaceServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Register views for marketplace themes namespace with higher priority
        $viewFactory = app('view');
        $viewFactory->prependNamespace('marketplace', __DIR__ . '/../../resources/views/themes');

        // Register vendor dashboard menu for tours
        DashboardMenu::for('vendor')->beforeRetrieving(function (): void {
            DashboardMenu::make()
                ->registerItem([
                    'id' => 'marketplace.vendor.tours',
                    'priority' => 2.5,
                    'name' => __('Tours'),
                    'url' => fn () => route('marketplace.vendor.tours.index'),
                    'icon' => 'ti ti-map-pin',
                ])
                ->registerItem([
                    'id' => 'marketplace.vendor.tour-bookings',
                    'priority' => 2.6,
                    'name' => __('Tour Bookings'),
                    'url' => fn () => route('marketplace.vendor.tour-bookings.index'),
                    'icon' => 'ti ti-calendar-event',
                ]);
        });

        // Routes are loaded from ToursServiceProvider

        // Add store relationship to Tour model
        add_action('init', function (): void {
            Tour::resolveRelationUsing('store', function ($tourModel) {
                return $tourModel->belongsTo(Store::class, 'store_id');
            });
        });

        // Filter tours by vendor store in admin
        add_filter('base_filter_before_get_list_data', function ($query, $model) {
            if ($model instanceof Tour && auth('customer')->check() && auth('customer')->user()->is_vendor) {
                return $query->where('store_id', auth('customer')->user()->store->id);
            }
            
            return $query;
        }, 150, 2);
    }

    public function register(): void
    {
        // No checks needed for test environment
    }
}
