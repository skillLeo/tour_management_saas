<?php

namespace Botble\Tours\Models;

use Botble\ACL\Models\User;
use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Base\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class Tour extends BaseModel
{
    use HasSlug;
    protected $table = 'tours';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'content',
        'image',
        'gallery',
        'duration_days',
        'duration_nights',
        'duration_hours',
        'max_people',
        'min_people',
        'price',
        'children_price',
        'infants_price',
        'sale_percentage',
        'location',
        'departure_location',
        'return_location',
        'latitude',
        'longitude',
        'included_services',
        'excluded_services',
        'activities',
        'tour_highlights',
        'itinerary',
        'is_featured',
        'allow_booking',
        'booking_advance_days',
        'category_id',
        'city_id',
        'tour_type',
        'tour_length',
        'author_id',
        'author_type',
        'store_id',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'name' => SafeContent::class,
        'description' => SafeContent::class,
        'gallery' => 'array',
        'included_services' => 'array',
        'excluded_services' => 'array',
        'activities' => 'array',
        'tour_highlights' => 'array',
        'itinerary' => 'array',
        'is_featured' => 'boolean',
        'allow_booking' => 'boolean',
        'price' => 'decimal:2',
        'children_price' => 'decimal:2',
        'infants_price' => 'decimal:2',
        'sale_percentage' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Set the included services attribute.
     * 
     * @param mixed $value The value from the form
     * @return void
     */
    public function setIncludedServicesAttribute($value)
    {
        // إذا كانت القيمة نصية، نقسمها إلى مصفوفة ثم نحولها إلى JSON
        if (is_string($value) && !empty($value)) {
            $services = array_map('trim', explode(',', $value));
            $this->attributes['included_services'] = json_encode($services);
        } 
        // إذا كانت القيمة مصفوفة، نحولها إلى JSON
        elseif (is_array($value)) {
            $this->attributes['included_services'] = json_encode($value);
        }
        // إذا كانت القيمة فارغة، نضع مصفوفة فارغة
        else {
            $this->attributes['included_services'] = json_encode([]);
        }
    }

    /**
     * Set the excluded services attribute.
     * 
     * @param mixed $value The value from the form
     * @return void
     */
    public function setExcludedServicesAttribute($value)
    {
        // إذا كانت القيمة نصية، نقسمها إلى مصفوفة ثم نحولها إلى JSON
        if (is_string($value) && !empty($value)) {
            $services = array_map('trim', explode(',', $value));
            $this->attributes['excluded_services'] = json_encode($services);
        } 
        // إذا كانت القيمة مصفوفة، نحولها إلى JSON
        elseif (is_array($value)) {
            $this->attributes['excluded_services'] = json_encode($value);
        }
        // إذا كانت القيمة فارغة، نضع مصفوفة فارغة
        else {
            $this->attributes['excluded_services'] = json_encode([]);
        }
    }

    /**
     * Set the tour highlights attribute.
     * 
     * @param mixed $value The value from the form
     * @return void
     */
    public function setTourHighlightsAttribute($value)
    {
        // إذا كانت القيمة نصية، نقسمها إلى مصفوفة ثم نحولها إلى JSON
        if (is_string($value) && !empty($value)) {
            $highlights = array_map('trim', explode(',', $value));
            $this->attributes['tour_highlights'] = json_encode($highlights);
        } 
        // إذا كانت القيمة مصفوفة، نحولها إلى JSON
        elseif (is_array($value)) {
            $this->attributes['tour_highlights'] = json_encode($value);
        }
        // إذا كانت القيمة فارغة، نضع مصفوفة فارغة
        else {
            $this->attributes['tour_highlights'] = json_encode([]);
        }
    }
  
    public function category(): BelongsTo
    {
        return $this->belongsTo(TourCategory::class, 'category_id')->withDefault();
    }
    
    public function city(): BelongsTo
    {
        return $this->belongsTo(TourCity::class, 'city_id')->withDefault();
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id')->withDefault();
    }

    public function store(): BelongsTo
    {
        if (! is_plugin_active('marketplace')) {
            return $this->belongsTo(User::class, 'author_id')->withDefault();
        }
        
        return $this->belongsTo(\Botble\Marketplace\Models\Store::class, 'store_id')->withDefault();
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(TourBooking::class, 'tour_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(TourReview::class, 'tour_id');
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(TourReview::class, 'tour_id')->where('is_approved', true);
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(TourFaq::class, 'tour_id')->orderBy('order');
    }

    public function places(): HasMany
    {
        return $this->hasMany(TourPlace::class, 'tour_id')->orderBy('order');
    }
    
    public function languages(): BelongsToMany
    {
        return $this->belongsToMany(TourLanguage::class, 'language_tour', 'tour_id', 'language_id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(TourSchedule::class, 'tour_id')->orderBy('order');
    }

    public function timeSlots(): HasMany
    {
        return $this->hasMany(TourTimeSlot::class)->orderBy('start_time');
    }

    public function availableTimeSlots(): HasMany
    {
        return $this->hasMany(TourTimeSlot::class)
            ->where('status', 'available')
            ->orderBy('start_time');
    }

    public function getConfirmedBookingsAttribute()
    {
        return $this->bookings()->where('status', 'confirmed')->get();
    }

    public function getAverageRatingAttribute(): float
    {
        return $this->approvedReviews()->avg('rating') ?: 0;
    }

    public function getReviewsCountAttribute(): int
    {
        return $this->approvedReviews()->count();
    }

    public function getStarRatingAttribute(): string
    {
        $rating = $this->average_rating;
        $fullStars = floor($rating);
        $halfStar = ($rating - $fullStars) >= 0.5;
        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);

        return str_repeat('★', $fullStars) . 
               ($halfStar ? '☆' : '') . 
               str_repeat('☆', $emptyStars);
    }

    public function getCurrentPriceAttribute(): float
    {
        if ($this->sale_percentage && $this->sale_percentage > 0) {
            return $this->price * (1 - ($this->sale_percentage / 100));
        }
        return $this->price;
    }

    public function getCurrentChildrenPriceAttribute(): float
    {
        $basePrice = $this->children_price ?: 0;
        if ($this->sale_percentage && $this->sale_percentage > 0) {
            return $basePrice * (1 - ($this->sale_percentage / 100));
        }
        return $basePrice;
    }

    public function getCurrentInfantsPriceAttribute(): float
    {
        $basePrice = $this->infants_price ?: 0;
        if ($this->sale_percentage && $this->sale_percentage > 0) {
            return $basePrice * (1 - ($this->sale_percentage / 100));
        }
        return $basePrice;
    }

    public function getDiscountPercentageAttribute(): ?float
    {
        return $this->sale_percentage > 0 ? $this->sale_percentage : null;
    }

    public function hasDiscountAttribute(): bool
    {
        return $this->sale_percentage && $this->sale_percentage > 0;
    }

    public function getDurationTextAttribute(): string
    {
        if (!empty($this->duration_hours) && $this->duration_hours > 0) {
            return $this->duration_hours . ' ' . (trans('plugins/tours::tours.hours') ?? 'hours');
        }

        if (!empty($this->duration_days) && $this->duration_days > 0 && $this->duration_nights > 0) {
            return $this->duration_days . ' days / ' . $this->duration_nights . ' nights';
        }

        if (!empty($this->duration_days) && $this->duration_days > 0) {
            return $this->duration_days . ' day' . ($this->duration_days > 1 ? 's' : '');
        }

        return '';
    }
    
    public function getDepartureInfoAttribute(): ?string
    {
        return $this->departure_location;
    }
    
    public function getReturnInfoAttribute(): ?string
    {
        return $this->return_location;
    }

    public function getAvailableSpotsAttribute(): int
    {
        $bookedSpots = $this->bookings()
            ->where('status', 'confirmed')
            ->sum('adults');

        return max(0, $this->max_people - $bookedSpots);
    }

    public function isAvailableForDate($date): bool
    {
        if (!$this->allow_booking) {
            return false;
        }



        return true;
    }

    public function getImagesAttribute(): array
    {
        return $this->gallery ?: [];
    }

    /**
     * Get the included services attribute.
     * 
     * @param mixed $value The value from the database
     * @return array
     */
    public function getIncludedServicesAttribute($value)
    {
        // إذا كانت القيمة فارغة، نرجع مصفوفة فارغة
        if (empty($value)) {
            return [];
        }
        
        // إذا كانت القيمة نصية وتبدأ بـ [ أو {، فهي JSON
        if (is_string($value) && (strpos($value, '[') === 0 || strpos($value, '{') === 0)) {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }
        
        // نقسم النص المفصول بفواصل إلى مصفوفة
        if (is_string($value)) {
            return array_map('trim', explode(',', $value));
        }
        
        // إذا كانت القيمة مصفوفة بالفعل، نرجعها كما هي
        return is_array($value) ? $value : [];
    }

    /**
     * Get the excluded services attribute.
     * 
     * @param mixed $value The value from the database
     * @return array
     */
    public function getExcludedServicesAttribute($value)
    {
        // إذا كانت القيمة فارغة، نرجع مصفوفة فارغة
        if (empty($value)) {
            return [];
        }
        
        // إذا كانت القيمة نصية وتبدأ بـ [ أو {، فهي JSON
        if (is_string($value) && (strpos($value, '[') === 0 || strpos($value, '{') === 0)) {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }
        
        // نقسم النص المفصول بفواصل إلى مصفوفة
        if (is_string($value)) {
            return array_map('trim', explode(',', $value));
        }
        
        // إذا كانت القيمة مصفوفة بالفعل، نرجعها كما هي
        return is_array($value) ? $value : [];
    }

    /**
     * Get the tour highlights attribute.
     * 
     * @param mixed $value The value from the database
     * @return array
     */
    public function getTourHighlightsAttribute($value)
    {
        // إذا كانت القيمة فارغة، نرجع مصفوفة فارغة
        if (empty($value)) {
            return [];
        }
        
        // إذا كانت القيمة نصية وتبدأ بـ [ أو {، فهي JSON
        if (is_string($value) && (strpos($value, '[') === 0 || strpos($value, '{') === 0)) {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }
        
        // نقسم النص المفصول بفواصل إلى مصفوفة
        if (is_string($value)) {
            return array_map('trim', explode(',', $value));
        }
        
        // إذا كانت القيمة مصفوفة بالفعل، نرجعها كما هي
        return is_array($value) ? $value : [];
    }

    /**
     * Get the itinerary attribute.
     * 
     * @param mixed $value The value from the database
     * @return array
     */
    public function getItineraryAttribute($value)
    {
        // إذا كانت القيمة فارغة، نرجع مصفوفة فارغة
        if (empty($value)) {
            return [];
        }
        
        // إذا كانت القيمة نصية وتبدأ بـ [ أو {، فهي JSON
        if (is_string($value) && (strpos($value, '[') === 0 || strpos($value, '{') === 0)) {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }
        
        // نقسم النص المفصول بفواصل إلى مصفوفة
        if (is_string($value)) {
            return array_map('trim', explode(',', $value));
        }
        
        // إذا كانت القيمة مصفوفة بالفعل، نرجعها كما هي
        return is_array($value) ? $value : [];
    }

    /**
     * Mutator: activities
     */
    public function setActivitiesAttribute($value)
    {
        if (is_string($value) && !empty($value)) {
            $items = array_map('trim', explode(',', $value));
            $this->attributes['activities'] = json_encode($items);
            return;
        }
        if (is_array($value)) {
            $this->attributes['activities'] = json_encode($value);
            return;
        }
        $this->attributes['activities'] = json_encode([]);
    }

    /**
     * Accessor: activities
     */
    public function getActivitiesAttribute($value)
    {
        if (empty($value)) { return []; }
        if (is_string($value) && (str_starts_with($value, '[') || str_starts_with($value, '{'))) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }
        if (is_string($value)) { return array_map('trim', explode(',', $value)); }
        return is_array($value) ? $value : [];
    }

    /**
     * Set the gallery attribute.
     * 
     * @param mixed $value The value from the form
     * @return void
     */
    public function setGalleryAttribute($value)
    {
        \Log::info('Tour Model setGalleryAttribute called:', [
            'value' => $value,
            'type' => gettype($value),
            'is_string' => is_string($value),
            'is_array' => is_array($value),
            'is_empty' => empty($value),
        ]);
        
        // If value is already a JSON string, store it directly
        if (is_string($value) && (strpos($value, '[') === 0 || strpos($value, '{') === 0)) {
            \Log::info('Setting gallery as JSON string:', ['value' => $value]);
            $this->attributes['gallery'] = $value;
        }
        // If value is an array, convert to JSON
        elseif (is_array($value)) {
            $jsonValue = json_encode($value);
            \Log::info('Converting array to JSON:', ['array' => $value, 'json' => $jsonValue]);
            $this->attributes['gallery'] = $jsonValue;
        }
        // If value is a comma-separated string
        elseif (is_string($value) && !empty($value)) {
            $images = array_map('trim', explode(',', $value));
            $jsonValue = json_encode($images);
            \Log::info('Converting comma-separated string to JSON:', ['string' => $value, 'array' => $images, 'json' => $jsonValue]);
            $this->attributes['gallery'] = $jsonValue;
        }
        // If value is empty or null
        else {
            \Log::info('Setting gallery to null (empty/null value)');
            $this->attributes['gallery'] = null;
        }
        
        \Log::info('Final gallery attribute set:', ['gallery' => $this->attributes['gallery']]);
    }

    /**
     * Get the gallery attribute.
     * 
     * @param mixed $value The value from the database
     * @return array
     */
    public function getGalleryAttribute($value)
    {
        // If empty, return empty array
        if (empty($value)) {
            return [];
        }
        
        // If it's a JSON string, decode it
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }
        
        // If it's already an array, return it
        return is_array($value) ? $value : [];
    }
    
  
}