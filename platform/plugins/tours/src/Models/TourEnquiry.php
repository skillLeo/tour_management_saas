<?php

namespace Botble\Tours\Models;

use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourEnquiry extends BaseModel
{
    protected $table = 'tour_enquiries';

    protected $fillable = [
        'tour_id',
        'customer_name',
        'customer_email',
        'subject',
        'message',
    ];

    public function tour(): BelongsTo
    {
        return $this->belongsTo(Tour::class);
    }
}


