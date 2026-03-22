<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('affiliate_clicks')) {
            return;
        }

        Schema::table('affiliate_clicks', function (Blueprint $table): void {
            $table->string('referrer_url', 255)->nullable()->change();
            $table->string('landing_url', 255)->nullable()->change();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('affiliate_clicks')) {
            return;
        }

        Schema::table('affiliate_clicks', function (Blueprint $table): void {
            $table->string('referrer_url')->nullable()->change();
            $table->string('landing_url')->nullable()->change();
        });
    }
};
