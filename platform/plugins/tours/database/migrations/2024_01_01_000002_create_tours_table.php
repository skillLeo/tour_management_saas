<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('tours', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 255);
            $table->string('slug', 255)->unique();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('image', 255)->nullable();
            $table->json('gallery')->nullable();

            
            // Tour Details
            $table->integer('duration_days')->default(0);
            
            $table->integer('duration_nights')->default(0);
            $table->integer('duration_hours')->default(0);
            $table->integer('max_people')->default(10);
            $table->integer('min_people')->default(1);
            $table->decimal('price', 15, 2)->default(0);
            $table->decimal('children_price', 15, 2)->nullable();
            $table->decimal('infants_price', 15, 2)->nullable();
            $table->decimal('sale_percentage', 5, 2)->nullable()->comment('Sale percentage (0-100)');
            
            // Location
            $table->string('location', 255)->nullable();
            $table->string('departure_location', 255)->nullable();
            $table->string('return_location', 255)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // Tour Features
            $table->json('included_services')->nullable();
            $table->json('excluded_services')->nullable();
            $table->json('activities')->nullable();
            $table->json('tour_highlights')->nullable(); 
            $table->json('itinerary')->nullable(); 
            
            // Booking Settings
            $table->boolean('is_featured')->default(false);
            $table->boolean('allow_booking')->default(true);
            $table->integer('booking_advance_days')->default(1); 
            // Relations
            $table->foreignId('category_id')->nullable()->constrained('tour_categories')->onDelete('set null');
            $table->string('tour_type')->nullable();
            $table->string('tour_length')->nullable();
            $table->foreignId('author_id')->constrained('ec_customers')->onDelete('cascade');
            $table->string('author_type')->nullable();
            // Meta
            $table->string('status', 60)->default('published');
            $table->string('meta_title', 255)->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords', 255)->nullable();
            
            $table->timestamps();
            
            $table->index(['status', 'created_at']);
            $table->index(['category_id', 'status']);
            $table->index(['is_featured', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tours');
    }
};