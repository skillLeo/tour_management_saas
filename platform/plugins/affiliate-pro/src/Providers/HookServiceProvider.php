<?php

namespace Botble\AffiliatePro\Providers;

use Botble\AffiliatePro\Enums\AffiliateStatusEnum;
use Botble\AffiliatePro\Enums\WithdrawalStatusEnum;
use Botble\AffiliatePro\Facades\AffiliateHelper;
use Botble\AffiliatePro\Models\Affiliate;
use Botble\AffiliatePro\Models\Commission;
use Botble\AffiliatePro\Models\Withdrawal;
use Botble\Base\Facades\MetaBox;
use Botble\Base\Supports\ServiceProvider;
use Botble\Ecommerce\Models\Order;
use Botble\Ecommerce\Models\Product;
use Botble\Marketplace\Forms\ProductForm as VendorProductForm;
use Botble\Theme\Facades\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class HookServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        add_action(BASE_ACTION_META_BOXES, function ($context, $object) {
            if (
                ! $object
                || $context != 'advanced'
                || ! is_in_admin()
                || ! $object instanceof Product
                || ! in_array(Route::currentRouteName(), [
                    'products.create',
                    'products.edit',
                ])
            ) {
                return false;
            }

            MetaBox::addMetaBox(
                'affiliate_settings_wrap',
                trans('plugins/affiliate-pro::affiliate.affiliate_settings'),
                [$this, 'addAffiliateSettingsFields'],
                get_class($object),
                $context
            );

            return true;
        }, 24, 2);

        add_action(BASE_ACTION_AFTER_CREATE_CONTENT, [$this, 'saveAffiliateFields'], 230, 3);
        add_action(BASE_ACTION_AFTER_UPDATE_CONTENT, [$this, 'saveAffiliateFields'], 231, 3);

        add_filter('ecommerce_after_product_description', [$this, 'showAffiliateCommissionInfo'], 10, 2);

        add_filter(BASE_FILTER_APPEND_MENU_NAME, [$this, 'getPendingRequestsCount'], 130, 2);
        add_filter(BASE_FILTER_MENU_ITEMS_COUNT, [$this, 'getMenuItemCount'], 121);

        add_filter('ecommerce_order_detail_extra_info', [$this, 'addAffiliateInfoToOrder'], 110, 2);

        if (is_plugin_active('marketplace')) {
            add_filter('marketplace_calculate_vendor_revenue', [$this, 'deductAffiliateCommissionFromVendorRevenue'], 10, 2);

            add_filter(BASE_FILTER_BEFORE_RENDER_FORM, function ($form, $data) {
                if (! $form instanceof VendorProductForm) {
                    return $form;
                }

                $product = $data instanceof Product ? $data : new Product();

                return $form->addMetaBoxes([
                    'affiliate_settings' => [
                        'title' => trans('plugins/affiliate-pro::affiliate.affiliate_settings'),
                        'content' => $this->addAffiliateSettingsFields($product),
                        'priority' => 5,
                    ],
                ]);
            }, 127, 2);
        }
    }

    public function addAffiliateSettingsFields(Product $product): string
    {
        $isAffiliateEnabled = $product->is_affiliate_enabled ?? true;
        $commissionPercentage = $product->affiliate_commission_percentage;
        $defaultCommissionPercentage = (float) AffiliateHelper::getSetting('commission_percentage', 10);

        return view('plugins/affiliate-pro::product-affiliate-fields', compact('product', 'isAffiliateEnabled', 'commissionPercentage', 'defaultCommissionPercentage'))->render();
    }

    public function saveAffiliateFields(string $screen, Request $request, $object): void
    {
        if (! $object instanceof Product) {
            return;
        }

        if (! $request->has('is_affiliate_enabled')) {
            return;
        }

        $object->is_affiliate_enabled = (bool) $request->input('is_affiliate_enabled');

        $useCustomCommission = (bool) $request->input('use_custom_commission');
        $object->affiliate_commission_percentage = $useCustomCommission ? $request->input('affiliate_commission_percentage') : null;

        $object->save();
    }

    public function showAffiliateCommissionInfo(?string $html, $product): string
    {
        // Check if customer is logged in
        if (! Auth::guard('customer')->check()) {
            return $html ?? '';
        }

        $customer = Auth::guard('customer')->user();

        $affiliate = AffiliateHelper::getActiveAffiliateByCustomerId($customer->id);

        if (! $affiliate) {
            return $html ?? '';
        }

        if (isset($product->is_affiliate_enabled) && ! $product->is_affiliate_enabled) {
            return $html ?? '';
        }

        $commissionPercentage = AffiliateHelper::getCommissionPercentage($product->id);

        if ($commissionPercentage <= 0) {
            return $html ?? '';
        }

        $productPrice = $product->price()->getPrice();
        $commissionAmount = $productPrice * ($commissionPercentage / 100);

        $currentUrl = request()->url();
        $affiliateLinkForProduct = $currentUrl . '?aff=' . $affiliate->affiliate_code;

        $version = '1.2.4';

        Theme::asset()->add('affiliate-commission-info-css', 'vendor/core/plugins/affiliate-pro/css/affiliate-commission-info.css', version: $version);

        Theme::asset()
            ->container('footer')
            ->usePath(false)
            ->add('affiliate-commission-info-js', 'vendor/core/plugins/affiliate-pro/js/affiliate-commission-info.js', ['jquery'], version: $version);

        $affiliateHtml = view('plugins/affiliate-pro::themes.affiliate-commission-info', [
            'affiliate' => $affiliate,
            'product' => $product,
            'commissionPercentage' => $commissionPercentage,
            'commissionAmount' => $commissionAmount,
            'affiliateLink' => $affiliateLinkForProduct,
            'productPrice' => $productPrice,
        ])->render();

        return ($html ?? '') . $affiliateHtml;
    }

    public function getPendingRequestsCount(string|int|null $number, string $menuId): int|string|null
    {
        switch ($menuId) {
            case 'cms-plugins-affiliate-pro-pending':
                if (! Auth::user()->hasPermission('affiliate-pro.edit')) {
                    return $number;
                }

                return view('core/base::partials.navbar.badge-count', ['class' => 'pending-affiliate-requests'])->render();

            case 'cms-plugins-affiliate-pro-withdrawals':
                if (! Auth::user()->hasPermission('affiliate.withdrawals.index')) {
                    return $number;
                }

                return view('core/base::partials.navbar.badge-count', ['class' => 'pending-affiliate-withdrawals'])->render();

            case 'cms-plugins-affiliate-pro':
                if (
                    ! Auth::user()->hasAnyPermission([
                        'affiliate-pro.edit',
                        'affiliate.withdrawals.index',
                    ])
                ) {
                    return $number;
                }

                return view('core/base::partials.navbar.badge-count', ['class' => 'affiliate-pro-notifications-count'])->render();
        }

        return $number;
    }

    public function getMenuItemCount(array $data = []): array
    {
        if (! Auth::check()) {
            return $data;
        }

        $countPendingRequests = 0;

        if (Auth::user()->hasPermission('affiliate-pro.edit')) {
            $countPendingRequests = Affiliate::query()
                ->where('status', AffiliateStatusEnum::PENDING)
                ->count();

            $data[] = [
                'key' => 'pending-affiliate-requests',
                'value' => $countPendingRequests,
            ];
        }

        $countPendingWithdrawals = 0;

        if (Auth::user()->hasPermission('affiliate.withdrawals.index')) {
            $countPendingWithdrawals = Withdrawal::query()
                ->whereIn('status', [WithdrawalStatusEnum::PENDING, WithdrawalStatusEnum::PROCESSING])
                ->count();

            $data[] = [
                'key' => 'pending-affiliate-withdrawals',
                'value' => $countPendingWithdrawals,
            ];
        }

        if (Auth::user()->hasAnyPermission(['affiliate-pro.edit', 'affiliate.withdrawals.index'])) {
            $data[] = [
                'key' => 'affiliate-pro-notifications-count',
                'value' => $countPendingRequests + $countPendingWithdrawals,
            ];
        }

        return $data;
    }

    public function addAffiliateInfoToOrder(?string $html, $order): string
    {
        if (! $order) {
            return $html ?? '';
        }

        $affiliateId = $order->getOrderMetadata('affiliate_id');

        if (! $affiliateId) {
            return $html ?? '';
        }

        $affiliate = Affiliate::query()->find($affiliateId);

        if (! $affiliate) {
            return $html ?? '';
        }

        $commission = Commission::query()->where('order_id', $order->id)->first();

        return ($html ?? '') . view('plugins/affiliate-pro::partials.order-affiliate-info', compact('affiliate', 'commission'))->render();
    }

    public function deductAffiliateCommissionFromVendorRevenue(array $data, Order $order): array
    {
        $affiliateCommission = Commission::query()->where('order_id', $order->id)->first();

        if (! $affiliateCommission) {
            return $data;
        }

        $affiliateCommissionAmount = (float) $affiliateCommission->amount;
        $data['affiliate_commission'] = $affiliateCommissionAmount;
        $data['amount'] = $data['amount'] - $affiliateCommissionAmount;

        return $data;
    }
}
