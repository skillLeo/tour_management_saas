<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (! is_plugin_active('marketplace')) {
            return;
        }

        Schema::table('tours', function (Blueprint $table): void {
            if (! Schema::hasColumn('tours', 'store_id')) {
                $table->unsignedBigInteger('store_id')->nullable()->after('author_id');
                $table->index('store_id');
            }
        });
    }

    public function down(): void
    {
        if (! is_plugin_active('marketplace')) {
            return;
        }

        Schema::table('tours', function (Blueprint $table): void {
            if (Schema::hasColumn('tours', 'store_id')) {
                $table->dropIndex(['store_id']);
                $table->dropColumn('store_id');
            }
        });
    }
};
