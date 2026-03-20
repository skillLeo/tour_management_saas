<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('tours', function (Blueprint $table): void {
            if (! Schema::hasColumn('tours', 'city_id')) {
                $table->unsignedBigInteger('city_id')->nullable()->after('category_id');
                $table->foreign('city_id')->references('id')->on('tour_cities')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table): void {
            if (Schema::hasColumn('tours', 'city_id')) {
                $table->dropForeign(['city_id']);
                $table->dropColumn('city_id');
            }
        });
    }
};
