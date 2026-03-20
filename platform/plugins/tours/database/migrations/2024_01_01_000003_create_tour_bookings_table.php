<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('tour_bookings', function (Blueprint $table): void {
            $table->id();
            $table->string('booking_code', 20)->unique();
            
            // Tour Information
            $table->foreignId('tour_id')->constrained('tours')->onDelete('cascade');
            $table->unsignedBigInteger('store_id')->nullable();
            $table->unsignedBigInteger('time_slot_id')->nullable();
            $table->json('time_slot_ids')->nullable();
            $table->date('tour_date');
            $table->integer('adults')->default(1);
            $table->integer('children')->default(0);
            $table->integer('infants')->default(0);
            
            // Customer Information
            $table->string('customer_name', 255);
            $table->string('customer_email', 255);
            $table->string('customer_phone', 20);
            $table->text('customer_address')->nullable();
            $table->string('customer_nationality', 100)->nullable();
            
            // Pricing
            $table->decimal('adult_price', 15, 2)->default(0);
            $table->decimal('child_price', 15, 2)->default(0);
            $table->decimal('infant_price', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->string('currency', 3)->default('USD');
            
            // Payment
            $table->string('payment_status', 60)->default('pending'); // pending, paid, failed, refunded
            $table->string('payment_method', 60)->nullable();
            $table->string('payment_reference', 255)->nullable();
            $table->timestamp('payment_date')->nullable();
            
            // Booking Status
            $table->string('status', 60)->default('pending'); // pending, confirmed, cancelled, completed
            $table->text('notes')->nullable();
            $table->text('special_requirements')->nullable();
            
            // Cancellation
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->decimal('refund_amount', 15, 2)->nullable();
            
            $table->timestamps();
            
            $table->index(['tour_id', 'tour_date']);
            $table->index('store_id');
            $table->index(['status', 'created_at']);
            $table->index(['payment_status', 'created_at']);
            $table->index('customer_email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tour_bookings');
    }
}; 