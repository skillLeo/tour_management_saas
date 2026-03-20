<?php

namespace Botble\Tours\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourFaq extends BaseModel
{
    protected $table = 'tour_faqs';

    protected $fillable = [
        'tour_id',
        'question',
        'answer',
        'order',
        'status',
    ];

    protected $casts = [
        'status' => BaseStatusEnum::class,
        'question' => SafeContent::class,
        'answer' => SafeContent::class,
    ];

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class, 'tour_id');
    }
} 