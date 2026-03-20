@extends(EcommerceHelper::viewPath('customers.master'))

@section('title', __('Tour Bookings'))

@section('content')
    <div class="bb-customer-content-wrapper">
        @if($bookings->isNotEmpty())
            <div class="customer-list-order">
                <div class="bb-customer-card-list order-cards">
                @foreach ($bookings as $booking)
                    <div class="bb-customer-card order-card">
                        <div class="bb-customer-card-header">
                            <div class="d-flex justify-content-between align-items-center gap-3">
                                <div class="flex-grow-1">
                                    <h3 class="bb-customer-card-title mb-2">
                                        {{ $booking->booking_code }} - {{ $booking->tour->name ?? __('N/A') }}
                                    </h3>
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <div class="bb-customer-card-status">
                                            <span class="badge badge-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : ($booking->status === 'cancelled' ? 'danger' : 'info')) }}">
                                                {{ $booking->status_label }}
                                            </span>
                                        </div>
                                        <span class="text-muted" style="font-size: 0.75rem;">•</span>
                                        <span class="text-muted" style="font-size: 0.75rem;">
                                            {{ $booking->created_at->translatedFormat('M d, Y') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bb-customer-card-body">
                            <div class="bb-customer-card-info">
                                <div class="row g-3">
                                    <div class="col-6 col-sm-4">
                                        <div class="info-item">
                                            <span class="label">{{ __('Tour Date') }}</span>
                                            <span class="value">{{ $booking->tour_date->translatedFormat('M d, Y') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-6 col-sm-4">
                                        <div class="info-item">
                                            <span class="label">{{ __('Total People') }}</span>
                                            <span class="value">{{ $booking->total_people }}</span>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-4">
                                        <div class="info-item">
                                            <span class="label">{{ __('Total Amount') }}</span>
                                            <span class="value">{{ format_price($booking->total_amount) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bb-customer-card-footer">
                            <a
                                class="btn btn-primary btn-sm"
                                href="{{ route('customer.tour-bookings.show', $booking->id) }}"
                            >
                                <x-core::icon name="ti ti-eye" />
                                <span>{{ __('View Details') }}</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

                @if($bookings->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {!! $bookings->links() !!}
                    </div>
                @endif
            </div>
        @else
            @include(EcommerceHelper::viewPath('customers.partials.empty-state'), [
                'title' => __('No Tour Bookings Yet'),
                'subtitle' => __('You haven\'t booked any tours yet. Start exploring amazing tours!'),
                'actionUrl' => route('public.tours.index'),
                'actionLabel' => __('Browse Tours'),
            ])
        @endif
    </div>
@stop
