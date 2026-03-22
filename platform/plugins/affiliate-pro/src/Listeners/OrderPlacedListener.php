<?php

namespace Botble\AffiliatePro\Listeners;

use Botble\AffiliatePro\Enums\AffiliateStatusEnum;
use Botble\AffiliatePro\Enums\CommissionStatusEnum;
use Botble\AffiliatePro\Facades\AffiliateHelper;
use Botble\AffiliatePro\Models\Affiliate;
use Botble\AffiliatePro\Models\Commission;
use Botble\AffiliatePro\Services\AffiliateTrackingService;
use Botble\Ecommerce\Events\OrderPlacedEvent;
use Botble\Ecommerce\Models\OrderProduct;

class OrderPlacedListener
{
    public function handle(OrderPlacedEvent $event): void
    {
        $order = $event->order;

        $affiliateCode = request()->cookie(AffiliateTrackingService::COOKIE_NAME);

        if (! $affiliateCode) {
            return;
        }

        $affiliate = Affiliate::query()
            ->where('affiliate_code', $affiliateCode)
            ->where('status', AffiliateStatusEnum::APPROVED)
            ->first();

        if (! $affiliate) {
            return;
        }

        $order->setOrderMetadata('affiliate_id', $affiliate->id);
        $order->setOrderMetadata('affiliate_code', $affiliateCode);

        if (Commission::query()->where('order_id', $order->id)->exists()) {
            return;
        }

        $totalCommission = 0;

        foreach ($order->products as $orderProduct) {
            $totalCommission += $this->calculateProductCommission($orderProduct, $affiliate);
        }

        if ($totalCommission <= 0) {
            return;
        }

        Commission::query()->create([
            'affiliate_id' => $affiliate->id,
            'order_id' => $order->id,
            'amount' => $totalCommission,
            'description' => 'Pending commission for order ' . $order->code,
            'status' => CommissionStatusEnum::PENDING,
        ]);
    }

    protected function calculateProductCommission(OrderProduct $orderProduct, Affiliate $affiliate): float
    {
        $productId = $orderProduct->product_id;
        $productPrice = $orderProduct->price;
        $quantity = $orderProduct->qty;

        $commissionPercentage = AffiliateHelper::getCommissionPercentage($productId, $affiliate);

        return $productPrice * $quantity * ($commissionPercentage / 100);
    }
}
