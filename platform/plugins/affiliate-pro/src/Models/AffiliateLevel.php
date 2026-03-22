<?php

namespace Botble\AffiliatePro\Models;

use Botble\Base\Casts\SafeContent;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Models\BaseModel;

class AffiliateLevel extends BaseModel
{
    protected $table = 'affiliate_levels';

    protected $fillable = [
        'name',
        'min_commission',
        'max_commission',
        'commission_rate',
        'benefits',
        'is_default',
        'status',
        'order',
    ];

    protected $casts = [
        'min_commission' => 'float',
        'max_commission' => 'float',
        'commission_rate' => 'decimal:2',
        'is_default' => 'boolean',
        'status' => BaseStatusEnum::class,
        'name' => SafeContent::class,
    ];
}
