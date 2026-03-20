@extends('plugins/marketplace::themes.vendor-dashboard.layouts.master')

@section('content')
    <div class="ps-page__content">
        <div class="ps-page__header mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1>{{ __('Edit Booking') }}</h1>
                    <small class="text-muted">{{ __('Booking Code: :code', ['code' => $booking->booking_code]) }}</small>
                </div>
                <div>
                    <a href="{{ route('marketplace.vendor.tour-bookings.show', $booking->id) }}" class="btn btn-outline-secondary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="15,18 9,12 15,6"/>
                        </svg>
                        {{ __('Back to Details') }}
                    </a>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('marketplace.vendor.tour-bookings.update', $booking->id) }}">
            @csrf
            @method('PUT')
            
            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <!-- Booking Status -->
                    <div class="ps-card mb-4">
                        <div class="ps-card__header">
                            <h4>{{ __('Booking Status') }}</h4>
                        </div>
                        <div class="ps-card__body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">{{ __('Booking Status') }}</label>
                                        <select name="status" id="status" class="form-select">
                                            <option value="pending" {{ $booking->status === 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                            <option value="confirmed" {{ $booking->status === 'confirmed' ? 'selected' : '' }}>{{ __('Confirmed') }}</option>
                                            <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                                            <option value="completed" {{ $booking->status === 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="payment_status" class="form-label">{{ __('Payment Status') }}</label>
                                        <select name="payment_status" id="payment_status" class="form-select">
                                            <option value="pending" {{ $booking->payment_status === 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                            <option value="paid" {{ $booking->payment_status === 'paid' ? 'selected' : '' }}>{{ __('Paid') }}</option>
                                            <option value="failed" {{ $booking->payment_status === 'failed' ? 'selected' : '' }}>{{ __('Failed') }}</option>
                                            <option value="refunded" {{ $booking->payment_status === 'refunded' ? 'selected' : '' }}>{{ __('Refunded') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Information (Read-only) -->
                    <div class="ps-card mb-4">
                        <div class="ps-card__header">
                            <h4>{{ __('Booking Information') }}</h4>
                            <small class="text-muted">{{ __('(Read-only)') }}</small>
                        </div>
                        <div class="ps-card__body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('Booking Code') }}</label>
                                        <input type="text" class="form-control" value="{{ $booking->booking_code }}" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('Tour Date') }}</label>
                                        <input type="text" class="form-control" value="{{ $booking->tour_date->format('F d, Y') }}" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('Adults') }}</label>
                                        <input type="text" class="form-control" value="{{ $booking->adults }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('Children') }}</label>
                                        <input type="text" class="form-control" value="{{ $booking->children }}" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('Infants') }}</label>
                                        <input type="text" class="form-control" value="{{ $booking->infants }}" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('Total Amount') }}</label>
                                        <input type="text" class="form-control" value="{{ format_price($booking->total_amount) }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Information (Read-only) -->
                    <div class="ps-card mb-4">
                        <div class="ps-card__header">
                            <h4>{{ __('Customer Information') }}</h4>
                            <small class="text-muted">{{ __('(Read-only)') }}</small>
                        </div>
                        <div class="ps-card__body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('Customer Name') }}</label>
                                        <input type="text" class="form-control" value="{{ $booking->customer_name }}" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('Email') }}</label>
                                        <input type="email" class="form-control" value="{{ $booking->customer_email }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    @if($booking->customer_phone)
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('Phone') }}</label>
                                            <input type="text" class="form-control" value="{{ $booking->customer_phone }}" readonly>
                                        </div>
                                    @endif
                                    @if($booking->customer_nationality)
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('Spoken Language') }}</label>
                                            <input type="text" class="form-control" value="{{ $booking->customer_nationality }}" readonly>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @if($booking->customer_address)
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Address') }}</label>
                                    <textarea class="form-control" rows="2" readonly>{{ $booking->customer_address }}</textarea>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Notes and Requirements -->
                    <div class="ps-card">
                        <div class="ps-card__header">
                            <h4>{{ __('Notes & Requirements') }}</h4>
                        </div>
                        <div class="ps-card__body">
                            <div class="mb-3">
                                <label for="special_requirements" class="form-label">{{ __('Special Requirements') }}</label>
                                <textarea name="special_requirements" id="special_requirements" class="form-control" rows="3">{{ old('special_requirements', $booking->special_requirements) }}</textarea>
                                <small class="form-text text-muted">{{ __('Customer\'s special requirements or requests') }}</small>
                            </div>
                            <div class="mb-3">
                                <label for="notes" class="form-label">{{ __('Internal Notes') }}</label>
                                <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes', $booking->notes) }}</textarea>
                                <small class="form-text text-muted">{{ __('Internal notes for your reference (not visible to customer)') }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Tour Information -->
                    @if($booking->tour)
                        <div class="ps-card mb-4">
                            <div class="ps-card__header">
                                <h4>{{ __('Tour Information') }}</h4>
                            </div>
                            <div class="ps-card__body">
                                @if($booking->tour->image)
                                    <img src="{{ RvMedia::getImageUrl($booking->tour->image, 'thumb') }}" 
                                         alt="{{ $booking->tour->name }}" 
                                         class="img-fluid rounded mb-3">
                                @endif
                                <h5>{{ $booking->tour->name }}</h5>
                                <p class="text-muted small">{{ Str::limit($booking->tour->description, 150) }}</p>
                                <div class="d-grid">
                                    <a href="{{ route('marketplace.vendor.tours.edit', $booking->tour->id) }}" class="btn btn-outline-primary btn-sm">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                            <path d="m18.5 2.5 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                        {{ __('Edit Tour') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Save Changes -->
                    <div class="ps-card">
                        <div class="ps-card__header">
                            <h4>{{ __('Save Changes') }}</h4>
                        </div>
                        <div class="ps-card__body">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                                        <polyline points="17,21 17,13 7,13 7,21"/>
                                        <polyline points="7,3 7,8 15,8"/>
                                    </svg>
                                    {{ __('Update Booking') }}
                                </button>
                                <a href="{{ route('marketplace.vendor.tour-bookings.show', $booking->id) }}" class="btn btn-outline-secondary">
                                    {{ __('Cancel') }}
                                </a>
                            </div>
                            
                            <hr class="my-3">
                            
                            <div class="text-center">
                                <small class="text-muted">
                                    {{ __('Last updated: :date', ['date' => $booking->updated_at->format('M d, Y \a\t H:i')]) }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
