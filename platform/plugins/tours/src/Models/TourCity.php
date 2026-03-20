<?php

namespace Botble\Tours\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;

class TourCity extends BaseModel
{
    protected $table = 'tour_cities';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'status',
        'order',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'name' => SafeContent::class,
        'description' => SafeContent::class,
    ];

    protected $appends = ['tours_count'];

    public function tours(): HasMany
    {
        // This relationship will be used only if the city_id column exists
        return $this->hasMany(Tour::class, 'city_id');
    }

    public function getPublishedToursAttribute()
    {
        // Check if the city_id column exists in the tours table
        if (Schema::hasColumn('tours', 'city_id')) {
            return $this->tours()->where('status', BaseStatusEnum::PUBLISHED)->get();
        }
        
        return collect();
    }

    public function getToursCountAttribute(): int
    {
        // Check if the city_id column exists in the tours table
        if (Schema::hasColumn('tours', 'city_id')) {
            return $this->tours()->where('status', BaseStatusEnum::PUBLISHED)->count();
        }
        
        return 0;
    }
}
