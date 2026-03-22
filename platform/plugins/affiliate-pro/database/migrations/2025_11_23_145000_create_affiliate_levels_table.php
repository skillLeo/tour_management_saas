<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('affiliate_levels')) {
            Schema::create('affiliate_levels', function (Blueprint $table): void {
                $table->id();
                $table->string('name');
                $table->decimal('min_commission', 15, 2)->default(0);
                $table->decimal('max_commission', 15, 2)->nullable();
                $table->decimal('commission_rate', 8, 2)->default(1.00);
                $table->text('benefits')->nullable();
                $table->boolean('is_default')->default(false);
                $table->string('status')->default('published');
                $table->integer('order')->default(0);
                $table->timestamps();
            });
        }

        Schema::table('affiliates', function (Blueprint $table): void {
            if (! Schema::hasColumn('affiliates', 'level_id')) {
                $table->unsignedBigInteger('level_id')->nullable();
            }

            if (! Schema::hasColumn('affiliates', 'level_updated_at')) {
                $table->timestamp('level_updated_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('affiliates', function (Blueprint $table): void {
            if (Schema::hasColumn('affiliates', 'level_id')) {
                $table->dropColumn('level_id');
            }

            if (Schema::hasColumn('affiliates', 'level_updated_at')) {
                $table->dropColumn('level_updated_at');
            }
        });

        Schema::dropIfExists('affiliate_levels');
    }
};
