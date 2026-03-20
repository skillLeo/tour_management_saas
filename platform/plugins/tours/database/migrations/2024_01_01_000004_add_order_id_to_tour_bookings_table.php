<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('tour_bookings', 'order_id')) {
            Schema::table('tour_bookings', function (Blueprint $table) {
                $table->foreignId('order_id')->nullable()->index();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('tour_bookings', 'order_id')) {
            Schema::table('tour_bookings', function (Blueprint $table) {
                $table->dropColumn('order_id');
            });
        }
    }
};