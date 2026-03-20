<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('tour_schedules', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tour_id')->constrained('tours')->onDelete('cascade');
            $table->string('title', 255);
            $table->text('description');
            $table->integer('order')->default(0);
            $table->string('status', 60)->default('published');
            $table->timestamps();
            
            $table->index(['tour_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tour_schedules');
    }
};