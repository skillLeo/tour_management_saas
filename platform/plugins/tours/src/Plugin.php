<?php

namespace Botble\Tours;

use Botble\PluginManagement\Abstracts\PluginOperationAbstract;
use Botble\Setting\Facades\Setting;
use Illuminate\Support\Facades\Schema;

class Plugin extends PluginOperationAbstract
{
    public static function activate(): void
    {
        Setting::set([

            'tours_booking_advance_days' => 1,
            'tours_per_page' => 12,
            'tours_enable_booking' => true,
        ])->save();
    }

    public static function deactivate(): void
    {
        // Plugin deactivation logic
    }

    public static function remove(): void
    {
        Schema::dropIfExists('tour_faqs');
        Schema::dropIfExists('tour_time_slots');
        Schema::dropIfExists('tour_bookings');
        Schema::dropIfExists('tours');
        Schema::dropIfExists('tour_categories');

        Setting::delete([
            'tours_default_currency',
            'tours_booking_advance_days',
            'tours_per_page',
            'tours_enable_booking',
        ]);
    }
} 