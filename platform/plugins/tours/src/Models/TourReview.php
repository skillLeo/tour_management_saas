<?php

namespace Botble\Tours\Models;

use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class TourReview extends BaseModel
{
    protected $table = 'tour_reviews';

    protected $fillable = [
        'tour_id',
        'rating',
        'review',
        'customer_name',
        'customer_email',
        'is_approved',
    ];

    protected $casts = [
        'rating' => 'decimal:1',
        'is_approved' => 'boolean',
    ];

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('is_approved', true);
    }

    public function scopeByTour(Builder $query, int $tourId): Builder
    {
        return $query->where('tour_id', $tourId);
    }

    public function getStarRatingAttribute(): string
    {
        $rating = $this->rating;
        $fullStars = floor($rating);
        $halfStar = ($rating - $fullStars) >= 0.5;
        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);

        return str_repeat('★', $fullStars) . 
               ($halfStar ? '☆' : '') . 
               str_repeat('☆', $emptyStars);
    }

    public function getRatingPercentageAttribute(): float
    {
        return ($this->rating / 5) * 100;
    }

    public function getReviewTextAttribute(): ?string
    {
        return $this->review;
    }
} 