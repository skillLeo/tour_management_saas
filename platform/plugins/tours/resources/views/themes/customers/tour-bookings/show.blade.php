@extends(EcommerceHelper::viewPath('customers.master'))

@section('title', __('Booking Details'))

@section('content')
    <div class="bb-customer-content-wrapper">
        <div class="customer-order-detail">
            <div class="bb-order-detail-wrapper">
                <!-- Booking Information Section -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="bb-order-info">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="bb-order-info-section">
                                        <h5 class="bb-section-title mb-3">
                                            <x-core::icon name="ti ti-ticket" />
                                            {{ __('Booking Information') }}
                                        </h5>
                                        <div class="bb-order-info-list">
                                            <div class="bb-order-info-item">
                                                <span class="label">{{ __('Booking Code') }}:</span>
                                                <span class="value fw-bold">{{ $booking->booking_code }}</span>
                                            </div>
                                            <div class="bb-order-info-item">
                                                <span class="label">{{ __('Booked On') }}:</span>
                                                <span class="value">{{ $booking->created_at->translatedFormat('d M Y H:i:s') }}</span>
                                            </div>
                                            <div class="bb-order-info-item">
                                                <span class="label">{{ __('Booking Status') }}:</span>
                                                <span class="value">
                                                    <span class="badge badge-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : ($booking->status === 'cancelled' ? 'danger' : 'info')) }}">
                                                        {{ $booking->status_label }}
                                                    </span>
                                                </span>
                                            </div>
                                            <div class="bb-order-info-item">
                                                <span class="label">{{ __('Tour Date') }}:</span>
                                                <span class="value fw-semibold">{{ $booking->tour_date->translatedFormat('l, M d, Y') }}</span>
                                            </div>
                                            @if($booking->timeSlot)
                                                <div class="bb-order-info-item">
                                                    <span class="label">{{ __('Time Slot') }}:</span>
                                                    <span class="value">{{ $booking->timeSlot->start_time }} - {{ $booking->timeSlot->end_time }}</span>
                                                </div>
                                            @endif
                                            @if($booking->payment_method)
                                                <div class="bb-order-info-item">
                                                    <span class="label">{{ __('Payment Method') }}:</span>
                                                    <span class="value">{{ $booking->payment_method }}</span>
                                                </div>
                                            @endif
                                            <div class="bb-order-info-item">
                                                <span class="label">{{ __('Payment Status') }}:</span>
                                                <span class="value">
                                                    <span class="badge badge-{{ $booking->payment_status === 'paid' ? 'success' : ($booking->payment_status === 'failed' ? 'danger' : 'warning') }}">
                                                        {{ $booking->payment_status_label }}
                                                    </span>
                                                </span>
                                            </div>
                                            @if($booking->special_requirements)
                                                <div class="bb-order-info-item">
                                                    <span class="label">{{ __('Special Requirements') }}:</span>
                                                    <span class="value text-warning fst-italic">{{ $booking->special_requirements }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="bb-order-address-section">
                                        <h5 class="bb-section-title mb-3">
                                            <x-core::icon name="ti ti-user" />
                                            {{ __('Customer Information') }}
                                        </h5>
                                        <div class="bb-order-info-list">
                                            <div class="bb-order-info-item">
                                                <span class="label">{{ __('Name') }}:</span>
                                                <span class="value">{{ $booking->customer_name }}</span>
                                            </div>
                                            <div class="bb-order-info-item">
                                                <span class="label">{{ __('Email') }}:</span>
                                                <span class="value">{{ $booking->customer_email }}</span>
                                            </div>
                                            @if($booking->customer_phone)
                                                <div class="bb-order-info-item">
                                                    <span class="label">{{ __('Phone') }}:</span>
                                                    <span class="value">{{ $booking->customer_phone }}</span>
                                                </div>
                                            @endif
                                            @if($booking->customer_nationality)
                                                <div class="bb-order-info-item">
                                                    <span class="label">{{ __('Spoken Language') }}:</span>
                                                    <span class="value">{{ $booking->customer_nationality }}</span>
                                                </div>
                                            @endif
                                            @if($booking->customer_address)
                                                <div class="bb-order-info-item">
                                                    <span class="label">{{ __('Address') }}:</span>
                                                    <span class="value">{{ $booking->customer_address }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tour Information Section -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="bb-section-title mb-3">
                            <x-core::icon name="ti ti-map-pin" />
                            {{ __('Tour Information') }}
                        </h5>
                        <div class="bb-order-products">
                            <div class="bb-order-product-card">
                                <div class="bb-order-product-card-content">
                                    @if($booking->tour && $booking->tour->image)
                                        <div class="bb-order-product-card-image">
                                            <img src="{{ RvMedia::getImageUrl($booking->tour->image, 'thumb') }}" 
                                                 alt="{{ $booking->tour->name }}">
                                        </div>
                                    @endif
                                    <div class="bb-order-product-card-details">
                                        <div class="bb-order-product-card-header">
                                            <div class="bb-order-product-card-name">
                                                @if($booking->tour && $booking->tour->slug)
                                                    <a href="{{ route('public.tours.detail', $booking->tour->slug) }}" target="_blank">
                                                        {{ $booking->tour->name ?? __('N/A') }}
                                                    </a>
                                                @else
                                                    {{ $booking->tour->name ?? __('N/A') }}
                                                @endif
                                            </div>
                                        </div>
                                        <div class="bb-order-product-card-meta">
                                            @if($booking->tour->category)
                                                <div class="bb-order-product-card-attributes">
                                                    <small>{{ __('Category') }}: {{ $booking->tour->category->name }}</small>
                                                </div>
                                            @endif
                                            @if($booking->tour->city)
                                                <div class="bb-order-product-card-attributes">
                                                    <small>{{ __('City') }}: {{ $booking->tour->city->name }}</small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="bb-order-product-card-price">
                                        <div class="bb-order-product-card-quantity">
                                            <span class="label">{{ __('Total People') }}:</span>
                                            <span class="value">{{ $booking->total_people }}</span>
                                        </div>
                                        <div class="d-flex flex-column align-items-end gap-1">
                                            <small class="text-muted">
                                                {{ __('Adults') }}: {{ $booking->adults }}
                                                @if($booking->children > 0), {{ __('Children') }}: {{ $booking->children }}@endif
                                                @if($booking->infants > 0), {{ __('Infants') }}: {{ $booking->infants }}@endif
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Summary Section -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="bb-section-title mb-3">
                            <x-core::icon name="ti ti-credit-card" />
                            {{ __('Payment Summary') }}
                        </h5>
                        <div class="bb-order-summary">
                            <div class="bb-order-summary-item">
                                <span class="label">{{ __('Adult Price') }} ({{ $booking->adults }} x {{ format_price($booking->adult_price) }})</span>
                                <span class="value">{{ format_price($booking->adults * $booking->adult_price) }}</span>
                            </div>
                            @if($booking->children > 0)
                                <div class="bb-order-summary-item">
                                    <span class="label">{{ __('Child Price') }} ({{ $booking->children }} x {{ format_price($booking->child_price) }})</span>
                                    <span class="value">{{ format_price($booking->children * $booking->child_price) }}</span>
                                </div>
                            @endif
                            @if($booking->infants > 0)
                                <div class="bb-order-summary-item">
                                    <span class="label">{{ __('Infant Price') }} ({{ $booking->infants }} x {{ format_price($booking->infant_price) }})</span>
                                    <span class="value">{{ format_price($booking->infants * $booking->infant_price) }}</span>
                                </div>
                            @endif
                            <div class="bb-order-summary-item">
                                <span class="label">{{ __('Subtotal') }}</span>
                                <span class="value">{{ format_price($booking->subtotal) }}</span>
                            </div>
                            @if($booking->tax_amount > 0)
                                <div class="bb-order-summary-item">
                                    <span class="label">{{ __('Tax') }}</span>
                                    <span class="value">{{ format_price($booking->tax_amount) }}</span>
                                </div>
                            @endif
                            @if($booking->discount_amount > 0)
                                <div class="bb-order-summary-item">
                                    <span class="label">{{ __('Discount') }}</span>
                                    <span class="value text-danger">-{{ format_price($booking->discount_amount) }}</span>
                                </div>
                            @endif
                            <div class="bb-order-summary-item bb-order-summary-total">
                                <span class="label">{{ __('Total Amount') }}</span>
                                <span class="value">{{ format_price($booking->total_amount) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cancellation Information (if cancelled) -->
                @if($booking->status === 'cancelled')
                    <div class="card mb-4 border-danger">
                        <div class="card-body">
                            <h5 class="bb-section-title mb-3 text-danger">
                                <x-core::icon name="ti ti-x" />
                                {{ __('Cancellation Information') }}
                            </h5>
                            <div class="bb-order-info-list">
                                <div class="bb-order-info-item">
                                    <span class="label">{{ __('Cancelled At') }}:</span>
                                    <span class="value">{{ $booking->cancelled_at->translatedFormat('d M Y H:i:s') }}</span>
                                </div>
                                @if($booking->refund_amount > 0)
                                    <div class="bb-order-info-item">
                                        <span class="label">{{ __('Refund Amount') }}:</span>
                                        <span class="value text-success">{{ format_price($booking->refund_amount) }}</span>
                                    </div>
                                @endif
                                @if($booking->cancellation_reason)
                                    <div class="bb-order-info-item">
                                        <span class="label">{{ __('Cancellation Reason') }}:</span>
                                        <span class="value text-warning fst-italic">{{ $booking->cancellation_reason }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="bb-order-actions d-flex flex-wrap gap-2 mt-3">
                @if($booking->tour && $booking->tour->slug)
                    <a class="btn btn-primary" href="{{ route('public.tours.detail', $booking->tour->slug) }}" target="_blank">
                        <x-core::icon name="ti ti-external-link" />
                        {{ __('View Tour') }}
                    </a>
                @endif
                
                @if($booking->canBeCancelled())
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal-cancel-booking">
                        <x-core::icon name="ti ti-x" />
                        {{ __('Cancel Booking') }}
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Cancel Booking Modal -->
    @if($booking->canBeCancelled())
        <div class="modal fade" id="modal-cancel-booking" tabindex="-1" aria-labelledby="modalCancelBookingLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content bb-modal-content">
                    <div class="modal-header align-items-start bb-modal-header">
                        <div>
                            <h4 class="modal-title fs-5 fw-bold" id="modalCancelBookingLabel">
                                <x-core::icon name="ti ti-alert-triangle" class="text-warning me-2" />
                                {{ __('Cancel Booking') }}
                            </h4>
                            <p class="text-muted mb-0 mt-2">{{ __('Are you sure you want to cancel this booking? This action cannot be undone.') }}</p>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="{{ route('customer.tour-bookings.cancel', $booking->id) }}" id="cancel-booking-form">
                        @csrf
                        <div class="modal-body bb-modal-body">
                            <div class="mb-3">
                                <label for="cancellation_reason" class="form-label">
                                    {{ __('Cancellation Reason') }} <span class="text-danger">*</span>
                                </label>
                                <textarea 
                                    name="cancellation_reason" 
                                    id="cancellation_reason"
                                    class="form-control" 
                                    rows="4" 
                                    required
                                    placeholder="{{ __('Please provide a reason for cancellation...') }}"
                                ></textarea>
                            </div>
                        </div>
                        <div class="modal-footer bb-modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <x-core::icon name="ti ti-x" />
                                {{ __('Close') }}
                            </button>
                            <button type="submit" class="btn btn-danger">
                                <x-core::icon name="ti ti-check" />
                                {{ __('Cancel Booking') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@stop
