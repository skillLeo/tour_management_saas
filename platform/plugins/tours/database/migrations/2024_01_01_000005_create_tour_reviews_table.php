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
        Schema::create('tour_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tour_id');
            $table->decimal('rating', 3, 1)->comment('Rating from 0.0 to 5.0');
            $table->text('review')->nullable();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->boolean('is_approved')->default(true);
            $table->timestamps();

            $table->foreign('tour_id')->references('id')->on('tours')->onDelete('cascade');
            $table->index(['tour_id', 'is_approved']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tour_reviews');
    }
}; 