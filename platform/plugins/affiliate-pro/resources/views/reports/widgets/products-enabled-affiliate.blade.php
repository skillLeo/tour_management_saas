<x-core::card class="mb-3">
    <x-core::card.header>
        <x-core::card.title>{{ trans('plugins/affiliate-pro::reports.products_enabled_affiliate') }}</x-core::card.title>
    </x-core::card.header>
    <x-core::card.body>
        <div class="d-flex align-items-center">
            <div class="subheader">{{ trans('plugins/affiliate-pro::reports.products') }}</div>
            <div class="ms-auto lh-1">
                <span class="text-green h1 mb-0">{{ number_format($productsEnabledAffiliate) }}</span>
            </div>
        </div>
        <div class="progress progress-sm mt-2">
            <div class="progress-bar bg-green" style="width: {{ $percentage }}%" role="progressbar" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100" aria-label="{{ $percentage }}% Complete">
                <span class="visually-hidden">{{ $percentage }}% Complete</span>
            </div>
        </div>
        <div class="mt-2 text-secondary">
            {{ trans('plugins/affiliate-pro::reports.out_of_total_products', ['total' => number_format($totalProducts)]) }}
        </div>
    </x-core::card.body>
</x-core::card>
