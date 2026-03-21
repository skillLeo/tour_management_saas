@php
    Theme::set('pageTitle', __('Booking Details') . ' - ' . $booking->booking_code);
    Theme::set('pageName', __('Booking Details'));
@endphp

@section('header')
    {!! Theme::partial('header') !!}
@endsection

<div class="container my-5">
        <!-- Page Header -->
        <div class="page-header mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-2">{{ __('Booking Details') }}</h1>
                    <p class="text-muted mb-0">{{ $booking->booking_code }}</p>
                </div>
                <div>
                    <a href="{{ route('customer.tour-bookings.index') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-arrow-left"></i>
                        {{ __('Back to Bookings') }}
                    </a>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Tour Information -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="ti ti-map-pin"></i>
                            {{ __('Tour Information') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if($booking->tour && $booking->tour->image)
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <img src="{{ RvMedia::getImageUrl($booking->tour->image, 'medium') }}" 
                                         alt="{{ $booking->tour->name }}" 
                                         class="img-fluid rounded">
                                </div>
                            @endif
                            <div class="{{ $booking->tour && $booking->tour->image ? 'col-md-8' : 'col-12' }}">
                                <h4 class="mb-3">{{ $booking->tour->name ?? __('N/A') }}</h4>
                                <div class="row g-3">
                                    @if($booking->tour->category)
                                        <div class="col-6">
                                            <strong>{{ __('Category') }}:</strong><br>
                                            <span class="text-muted">{{ $booking->tour->category->name }}</span>
                                        </div>
                                    @endif
                                    @if($booking->tour->city)
                                        <div class="col-6">
                                            <strong>{{ __('City') }}:</strong><br>
                                            <span class="text-muted">{{ $booking->tour->city->name }}</span>
                                        </div>
                                    @endif
                                    <div class="col-6">
                                        <strong>{{ __('Tour Date') }}:</strong><br>
                                        <span class="text-muted">{{ $booking->tour_date->translatedFormat('l, M d, Y') }}</span>
                                    </div>
                                    @if($booking->timeSlot)
                                        <div class="col-6">
                                            <strong>{{ __('Time') }}:</strong><br>
                                            <span class="text-muted">{{ $booking->timeSlot->start_time }} - {{ $booking->timeSlot->end_time }}</span>
                                        </div>
                                    @endif
                                </div>
                                @if($booking->tour && $booking->tour->slug)
                                    <a href="{{ route('public.tours.detail', $booking->tour->slug) }}" class="btn btn-outline-primary btn-sm mt-3" target="_blank">
                                        <i class="ti ti-external-link"></i>
                                        {{ __('View Tour Page') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Details -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="ti ti-users"></i>
                            {{ __('Booking Details') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3 col-6">
                                <div class="text-center p-3 bg-light rounded">
                                    <div class="h3 mb-0 text-primary">{{ $booking->adults }}</div>
                                    <small class="text-muted">{{ __('Adults') }}</small>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="text-center p-3 bg-light rounded">
                                    <div class="h3 mb-0 text-success">{{ $booking->children }}</div>
                                    <small class="text-muted">{{ __('Children') }}</small>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="text-center p-3 bg-light rounded">
                                    <div class="h3 mb-0 text-warning">{{ $booking->infants }}</div>
                                    <small class="text-muted">{{ __('Infants') }}</small>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="text-center p-3 bg-primary text-white rounded">
                                    <div class="h3 mb-0">{{ $booking->total_people }}</div>
                                    <small>{{ __('Total People') }}</small>
                                </div>
                            </div>
                        </div>

                        @if($booking->special_requirements)
                            <div class="mt-4">
                                <strong>{{ __('Special Requirements') }}:</strong>
                                <p class="text-muted mb-0 mt-2">{{ $booking->special_requirements }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Payment Summary -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="ti ti-credit-card"></i>
                            {{ __('Payment Summary') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <td>{{ __('Adult Price') }} ({{ $booking->adults }} x {{ format_price($booking->adult_price) }})</td>
                                    <td class="text-end">{{ format_price($booking->adults * $booking->adult_price) }}</td>
                                </tr>
                                @if($booking->children > 0)
                                    <tr>
                                        <td>{{ __('Child Price') }} ({{ $booking->children }} x {{ format_price($booking->child_price) }})</td>
                                        <td class="text-end">{{ format_price($booking->children * $booking->child_price) }}</td>
                                    </tr>
                                @endif
                                @if($booking->infants > 0)
                                    <tr>
                                        <td>{{ __('Infant Price') }} ({{ $booking->infants }} x {{ format_price($booking->infant_price) }})</td>
                                        <td class="text-end">{{ format_price($booking->infants * $booking->infant_price) }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td>{{ __('Subtotal') }}</td>
                                    <td class="text-end">{{ format_price($booking->subtotal) }}</td>
                                </tr>
                                @if($booking->tax_amount > 0)
                                    <tr>
                                        <td>{{ __('Tax') }}</td>
                                        <td class="text-end">{{ format_price($booking->tax_amount) }}</td>
                                    </tr>
                                @endif
                                @if($booking->discount_amount > 0)
                                    <tr>
                                        <td>{{ __('Discount') }}</td>
                                        <td class="text-end text-success">-{{ format_price($booking->discount_amount) }}</td>
                                    </tr>
                                @endif
                                <tr class="border-top">
                                    <td><strong>{{ __('Total Amount') }}</strong></td>
                                    <td class="text-end"><strong class="h4 text-primary mb-0">{{ format_price($booking->total_amount) }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

              <!-- Cancellation Info (if cancelled) -->
@if($booking->status === 'cancelled')
<div class="card shadow-sm border-danger mb-4">
    <div class="card-header bg-danger text-white">
        <h5 class="mb-0">
            <i class="ti ti-x"></i>
            {{ __('Cancellation Information') }}
        </h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <strong>{{ __('Cancelled At') }}:</strong><br>
                <span class="text-muted">
                    {{ $booking->cancelled_at ? $booking->cancelled_at->translatedFormat('d M Y H:i:s') : __('N/A') }}
                </span>
            </div>
            @if($booking->refund_amount > 0)
                <div class="col-md-6">
                    <strong>{{ __('Refund Amount') }}:</strong><br>
                    <span class="text-success">{{ format_price($booking->refund_amount) }}</span>
                </div>
            @endif
            @if($booking->cancellation_reason)
                <div class="col-12">
                    <strong>{{ __('Reason') }}:</strong><br>
                    <p class="text-muted mb-0">{{ $booking->cancellation_reason }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endif
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                <!-- Booking Status Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-3">{{ __('Booking Status') }}</h6>
                        <span class="badge badge-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : ($booking->status === 'cancelled' ? 'danger' : 'info')) }} p-3" style="font-size: 1.2rem;">
                            {{ $booking->status_label }}
                        </span>
                        <hr>
                        <h6 class="text-muted mb-3">{{ __('Payment Status') }}</h6>
                        <span class="badge badge-{{ $booking->payment_status === 'paid' ? 'success' : ($booking->payment_status === 'failed' ? 'danger' : 'warning') }} p-3" style="font-size: 1.2rem;">
                            {{ $booking->payment_status_label }}
                        </span>
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="ti ti-user"></i>
                            {{ __('Customer Information') }}
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>{{ __('Name') }}:</strong><br>
                            <span class="text-muted">{{ $booking->customer_name }}</span>
                        </div>
                        <div class="mb-3">
                            <strong>{{ __('Email') }}:</strong><br>
                            <span class="text-muted">{{ $booking->customer_email }}</span>
                        </div>
                        @if($booking->customer_phone)
                            <div class="mb-3">
                                <strong>{{ __('Phone') }}:</strong><br>
                                <span class="text-muted">{{ $booking->customer_phone }}</span>
                            </div>
                        @endif
                        @if($booking->customer_nationality)
                            <div class="mb-0">
                                <strong>{{ __('Nationality') }}:</strong><br>
                                <span class="text-muted">{{ $booking->customer_nationality }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Payment Info -->
                @if($booking->payment_method || $booking->payment_reference)
                    <div class="card shadow-sm mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="ti ti-receipt"></i>
                                {{ __('Payment Information') }}
                            </h6>
                        </div>
                        <div class="card-body">
                            @if($booking->payment_method)
                                <div class="mb-3">
                                    <strong>{{ __('Method') }}:</strong><br>
                                    <span class="text-muted">{{ $booking->payment_method }}</span>
                                </div>
                            @endif
                            @if($booking->payment_reference)
                                <div class="mb-3">
                                    <strong>{{ __('Reference') }}:</strong><br>
                                    <span class="text-muted">{{ $booking->payment_reference }}</span>
                                </div>
                            @endif
                            @if($booking->payment_date)
                                <div class="mb-0">
                                    <strong>{{ __('Date') }}:</strong><br>
                                    <span class="text-muted">{{ $booking->payment_date->translatedFormat('M d, Y H:i') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Actions -->
                @if($booking->canBeCancelled())
                    <div class="card shadow-sm border-warning">
                        <div class="card-body text-center">
                            <p class="text-muted small mb-3">{{ __('You can cancel this booking until 24 hours before the tour date.') }}</p>
                            <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#cancelModal">
                                <i class="ti ti-x"></i>
                                {{ __('Cancel Booking') }}
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Cancel Modal -->
    @if($booking->canBeCancelled())
        <div class="modal fade" id="cancelModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" action="{{ route('customer.tour-bookings.cancel', $booking->id) }}">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="ti ti-alert-triangle text-warning"></i>
                                {{ __('Cancel Booking') }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p class="text-muted">{{ __('Are you sure you want to cancel this booking? This action cannot be undone.') }}</p>
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
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                {{ __('Close') }}
                            </button>
                            <button type="submit" class="btn btn-danger">
                                <i class="ti ti-check"></i>
                                {{ __('Confirm Cancellation') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <style>
        .page-header h1 {
            color: #2c3e50;
            font-weight: 600;
        }
        .card {
            border: none;
        }
        .card-header {
            border-bottom: 2px solid rgba(0,0,0,0.1);
            font-weight: 600;
        }
        .badge {
            font-size: 0.85rem;
        }
    </style>
