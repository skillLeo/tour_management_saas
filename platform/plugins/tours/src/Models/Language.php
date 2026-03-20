<?php

namespace Botble\Tours\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Language extends BaseModel
{
    protected $table = 'languages';

    protected $fillable = [
        'name',
        'code',
        'flag',
        'status',
        'order',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'name' => SafeContent::class,
    ];

    /**
     * Get the tours that use this language.
     */
    public function tours(): BelongsToMany
    {
        return $this->belongsToMany(Tour::class, 'tour_language')->withTimestamps();
    }

    /**
     * Get the flag image URL.
     */
    public function getFlagUrlAttribute(): string
    {
        if (empty($this->flag)) {
            return asset('vendor/core/plugins/tours/images/flags/default-flag.png');
        }

        return \Botble\Media\Facades\RvMedia::getImageUrl($this->flag, null, false, \Botble\Media\Facades\RvMedia::getDefaultImage());
    }
}
