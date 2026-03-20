<?php

namespace Botble\Tours\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Botble\Base\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Botble\Slug\Models\Slug;

class TourCategory extends BaseModel
{
    use HasSlug;
    protected $table = 'tour_categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'icon',
        'order',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'name' => SafeContent::class,
        'description' => SafeContent::class,
    ];

    protected $appends = ['tours_count'];

    public function slugable()
    {
        return $this->morphOne(Slug::class, 'reference');
    }

    public function tours(): HasMany
    {
        return $this->hasMany(Tour::class, 'category_id');
    }

    public function getPublishedToursAttribute()
    {
        return $this->tours()->where('status', BaseStatusEnum::PUBLISHED)->get();
    }

    public function getToursCountAttribute(): int
    {
        return $this->tours()->where('status', BaseStatusEnum::PUBLISHED)->count();
    }
} 