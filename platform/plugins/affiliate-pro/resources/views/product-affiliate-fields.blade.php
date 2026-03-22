@php
    $hasCustomCommission = $commissionPercentage > 0;
@endphp

<div class="mb-3">
    <x-core::form-group>
        <x-core::form.on-off
            name="is_affiliate_enabled"
            :value="$isAffiliateEnabled"
            :label="trans('plugins/affiliate-pro::product.enable_affiliate')"
            data-bb-toggle="collapse"
            data-bb-target="#affiliate_options_wrap"
        />
        <x-core::form.helper-text>
            {{ trans('plugins/affiliate-pro::product.enable_affiliate_helper') }}
        </x-core::form.helper-text>
    </x-core::form-group>

    <div id="affiliate_options_wrap" @if (!$isAffiliateEnabled) style="display: none;" @endif data-bb-value="1">
        <x-core::alert type="info" class="mb-3">
            {!! BaseHelper::clean(trans('plugins/affiliate-pro::product.default_commission_info', ['percentage' => '<strong>' . number_format($defaultCommissionPercentage, 2) . '%</strong>'])) !!}
        </x-core::alert>

        <x-core::form-group>
            <x-core::form.checkbox
                name="use_custom_commission"
                value="1"
                :checked="$hasCustomCommission"
                id="use_custom_commission"
                :label="trans('plugins/affiliate-pro::product.use_custom_commission')"
                data-bb-toggle="collapse"
                data-bb-target="#custom_commission_wrap"
            />
        </x-core::form-group>

        <div id="custom_commission_wrap" class="ms-4" @if (!$hasCustomCommission) style="display: none;" @endif data-bb-value="1">
            <x-core::form.text-input
                type="number"
                name="affiliate_commission_percentage"
                :label="trans('plugins/affiliate-pro::product.commission_percentage')"
                :value="$commissionPercentage > 0 ? $commissionPercentage : ''"
                :placeholder="trans('plugins/affiliate-pro::product.commission_percentage_placeholder')"
                min="0.01"
                max="100"
                step="0.01"
                :helper-text="trans('plugins/affiliate-pro::product.commission_percentage_helper')"
            >
                <x-slot:append>
                    <span class="input-group-text">%</span>
                </x-slot:append>
            </x-core::form.text-input>
        </div>
    </div>
</div>
