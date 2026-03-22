<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('affiliate_levels_translations')) {
            Schema::create('affiliate_levels_translations', function (Blueprint $table): void {
                $table->string('lang_code');
                $table->foreignId('affiliate_levels_id');
                $table->string('name')->nullable();
                $table->text('benefits')->nullable();

                $table->primary(['lang_code', 'affiliate_levels_id'], 'affiliate_levels_translations_primary');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliate_levels_translations');
    }
};
