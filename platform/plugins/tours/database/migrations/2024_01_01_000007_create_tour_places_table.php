<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('tour_places', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tour_id')->constrained('tours')->onDelete('cascade');
            $table->string('name', 255);
            $table->string('image', 255)->nullable();
            $table->integer('order')->default(0);
            $table->string('status', 60)->default('published');
            $table->timestamps();
            
            $table->index(['tour_id', 'status']);
            $table->index(['tour_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tour_places');
    }
};