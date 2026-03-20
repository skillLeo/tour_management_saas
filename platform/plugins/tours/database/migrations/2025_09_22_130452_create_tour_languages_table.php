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
        // Create tour_languages table
        Schema::create('tour_languages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 10)->unique();
            $table->string('flag')->nullable();
            $table->string('status', 60)->default('published');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
        
        // Create pivot table for tours and languages
        Schema::create('language_tour', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tour_id');
            $table->unsignedBigInteger('language_id');
            $table->timestamps();
            
            $table->foreign('tour_id')->references('id')->on('tours')->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on('tour_languages')->onDelete('cascade');
            
            // Ensure a language can only be added once to a tour
            $table->unique(['tour_id', 'language_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('language_tour');
        Schema::dropIfExists('tour_languages');
    }
};
