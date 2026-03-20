<?php

namespace Botble\Tours\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Models\BaseModel;
use Botble\Ecommerce\Models\Order;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourBooking extends BaseModel
{
    protected $table = 'tour_bookings';

    protected $fillable = [
        'booking_code',
        'tour_id',
        'store_id',
        'time_slot_id',
        'time_slot_ids',
        'tour_date',
        'adults',
        'children',
        'infants',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_address',
        'customer_nationality',
        'adult_price',
        'child_price',
        'infant_price',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total_amount',

        'payment_status',
        'payment_method',
        'payment_reference',
        'payment_date',
        'status',
        'notes',
        'special_requirements',
        'cancelled_at',
        'cancellation_reason',
        'refund_amount',
        'order_id',
    ];

    protected $casts = [
        'customer_name' => SafeContent::class,
        'customer_address' => SafeContent::class,
        'notes' => SafeContent::class,
        'special_requirements' => SafeContent::class,
        'cancellation_reason' => SafeContent::class,
        'tour_date' => 'date',
        'payment_date' => 'datetime',
        'cancelled_at' => 'datetime',
        'adult_price' => 'decimal:2',
        'child_price' => 'decimal:2',
        'infant_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'time_slot_ids' => 'array',
    ];

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(\Botble\Marketplace\Models\Store::class, 'store_id');
    }

    public function timeSlot(): BelongsTo
    {
        return $this->belongsTo(TourTimeSlot::class, 'time_slot_id');
    }

    /**
     * Get the order that owns the booking.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function getTotalPeopleAttribute(): int
    {
        return $this->adults + $this->children + $this->infants;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'cancelled' => 'Cancelled',
            'completed' => 'Completed',
            default => 'Unknown',
        };
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return match ($this->payment_status) {
            'pending' => 'Pending',
            'paid' => 'Paid',
            'failed' => 'Failed',
            'refunded' => 'Refunded',
            default => 'Unknown',
        };
    }

    public function canBeCancelled(): bool
    {
        if ($this->status === 'cancelled' || $this->status === 'completed') {
            return false;
        }

        // Check if cancellation is within allowed time (24 hours before tour date)
        $cancellationDeadline = $this->tour_date->subHours(24);
        
        return now() <= $cancellationDeadline;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (empty($booking->booking_code)) {
                $booking->booking_code = 'TB' . strtoupper(uniqid());
            }
        });
    }
}