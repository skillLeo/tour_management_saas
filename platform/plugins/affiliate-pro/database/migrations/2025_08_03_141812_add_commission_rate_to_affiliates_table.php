<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (Schema::hasColumn('affiliates', 'commission_rate')) {
            return;
        }

        Schema::table('affiliates', function (Blueprint $table): void {
            $table->decimal('commission_rate', 5, 2)->nullable()->after('affiliate_code')
                ->comment('Custom commission rate for this affiliate (percentage)');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('affiliates', 'commission_rate')) {
            return;
        }

        Schema::table('affiliates', function (Blueprint $table): void {
            $table->dropColumn('commission_rate');
        });
    }
};
