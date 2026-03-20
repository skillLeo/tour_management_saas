<?php

namespace Botble\Tours\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TourLanguage extends BaseModel
{
    protected $table = 'tour_languages';

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

    public function tours(): BelongsToMany
    {
        return $this->belongsToMany(Tour::class, 'language_tour', 'language_id', 'tour_id');
    }
}
