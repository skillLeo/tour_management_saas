@php
    Theme::set('pageTitle', __('My Tour Bookings'));
    Theme::set('pageName', __('My Tour Bookings'));
@endphp

@section('header')
    {!! Theme::partial('header') !!}
@endsection

<div class="container my-5">
        <!-- Page Header -->
        <div class="page-header mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-2">{{ __('My Tour Bookings') }}</h1>
                    <p class="text-muted mb-0">{{ __('View and manage your tour booking history') }}</p>
                </div>
                <div>
                    <a href="{{ route('customer.overview') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-arrow-left"></i>
                        {{ __('Back to Dashboard') }}
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

        @if($bookings->isNotEmpty())
            <!-- Tour Bookings List -->
            <div class="row g-4">
                @foreach ($bookings as $booking)
                    <div class="col-12">
                        <div class="card shadow-sm hover-shadow">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <!-- Tour Image -->
                                    @if($booking->tour && $booking->tour->image)
                                        <div class="col-md-2">
                                            <img src="{{ RvMedia::getImageUrl($booking->tour->image, 'thumb') }}" 
                                                 alt="{{ $booking->tour->name }}" 
                                                 class="img-fluid rounded">
                                        </div>
                                    @endif

                                    <!-- Booking Info -->
                                    <div class="{{ $booking->tour && $booking->tour->image ? 'col-md-6' : 'col-md-8' }}">
                                        <div class="d-flex align-items-start justify-content-between mb-2">
                                            <div>
                                                <h5 class="mb-1">
                                                    <a href="{{ route('customer.tour-bookings.show', $booking->id) }}" class="text-decoration-none">
                                                        {{ $booking->tour->name ?? __('N/A') }}
                                                    </a>
                                                </h5>
                                                <p class="text-muted small mb-2">
                                                    <i class="ti ti-ticket"></i>
                                                    <strong>{{ __('Booking Code') }}:</strong> {{ $booking->booking_code }}
                                                </p>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge badge-{{ $booking->status === 'confirmed' ? 'success' : ($booking->status === 'pending' ? 'warning' : ($booking->status === 'cancelled' ? 'danger' : 'info')) }} mb-1">
                                                    {{ $booking->status_label }}
                                                </span>
                                                <br>
                                                <span class="badge badge-{{ $booking->payment_status === 'paid' ? 'success' : ($booking->payment_status === 'failed' ? 'danger' : 'warning') }}">
                                                    {{ $booking->payment_status_label }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="row g-3 text-muted small">
                                            <div class="col-6 col-md-4">
                                                <i class="ti ti-calendar"></i>
                                                <strong>{{ __('Tour Date') }}:</strong><br>
                                                {{ $booking->tour_date->translatedFormat('M d, Y') }}
                                            </div>
                                            <div class="col-6 col-md-4">
                                                <i class="ti ti-users"></i>
                                                <strong>{{ __('People') }}:</strong><br>
                                                {{ $booking->total_people }}
                                            </div>
                                            <div class="col-6 col-md-4">
                                                <i class="ti ti-clock"></i>
                                                <strong>{{ __('Booked On') }}:</strong><br>
                                                {{ $booking->created_at->translatedFormat('M d, Y') }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Price & Action -->
                                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                        <div class="mb-3">
                                            <div class="text-muted small">{{ __('Total Amount') }}</div>
                                            <h4 class="mb-0 text-primary">{{ format_price($booking->total_amount) }}</h4>
                                        </div>
                                        <a href="{{ route('customer.tour-bookings.show', $booking->id) }}" class="btn btn-primary btn-sm w-100">
                                            <i class="ti ti-eye"></i>
                                            {{ __('View Details') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($bookings->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {!! $bookings->links() !!}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="ti ti-ticket" style="font-size: 5rem; color: #dee2e6;"></i>
                </div>
                <h3>{{ __('No Tour Bookings Yet') }}</h3>
                <p class="text-muted mb-4">{{ __('You haven\'t booked any tours yet. Start exploring amazing tours!') }}</p>
                <a href="{{ route('public.tours.index') }}" class="btn btn-primary">
                    <i class="ti ti-search"></i>
                    {{ __('Browse Tours') }}
                </a>
            </div>
        @endif
    </div>

    <style>
        .hover-shadow {
            transition: all 0.3s ease;
        }
        .hover-shadow:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
            transform: translateY(-2px);
        }
        .page-header h1 {
            color: #2c3e50;
            font-weight: 600;
        }
        .badge {
            padding: 0.5em 0.75em;
            font-size: 0.75rem;
        }
    </style>
