<?php

namespace Botble\AffiliatePro\Events;

use Botble\AffiliatePro\Models\Affiliate;
use Botble\AffiliatePro\Models\AffiliateLevel;
use Botble\Base\Events\Event;
use Illuminate\Queue\SerializesModels;

class LevelUpgradedEvent extends Event
{
    use SerializesModels;

    public function __construct(
        public Affiliate $affiliate,
        public AffiliateLevel $newLevel,
        public ?AffiliateLevel $oldLevel = null
    ) {
    }
}
