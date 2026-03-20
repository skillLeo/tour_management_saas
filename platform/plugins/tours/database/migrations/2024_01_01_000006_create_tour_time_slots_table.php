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
        Schema::create('tour_time_slots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tour_id');
            $table->time('start_time');
            $table->integer('order')->default(0);
            $table->string('status', 20)->default('available');
            $table->text('restricted_days')->nullable(); // Days of the week when this slot is not available
            $table->timestamps();

            $table->foreign('tour_id')->references('id')->on('tours')->onDelete('cascade');
            $table->index(['tour_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_time_slots');
    }
};