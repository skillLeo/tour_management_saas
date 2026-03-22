<div class="hr my-1"></div>
<div class="p-3">
    <h4>{{ trans('plugins/affiliate-pro::affiliate.affiliate_info') }}</h4>
    <dl class="mb-0">
        <dt>{{ trans('plugins/affiliate-pro::affiliate.name') }}</dt>
        <dd>
            <a href="{{ route('affiliate-pro.edit', $affiliate->id) }}" target="_blank">
                {{ $affiliate->customer->name }}
            </a>
        </dd>
        <dt>{{ trans('core/base::tables.email') }}</dt>
        <dd>
            <a href="mailto:{{ $affiliate->customer->email }}">{{ $affiliate->customer->email }}</a>
        </dd>
        <dt>{{ trans('plugins/affiliate-pro::affiliate.affiliate_code') }}</dt>
        <dd class="text-warning">{{ $affiliate->affiliate_code }}</dd>
        @if ($commission)
            <dt>{{ trans('plugins/affiliate-pro::commission.amount') }}</dt>
            <dd class="text-warning">{{ format_price($commission->amount) }} ({{ $commission->status->label() }})</dd>
        @endif
    </dl>
</div>
