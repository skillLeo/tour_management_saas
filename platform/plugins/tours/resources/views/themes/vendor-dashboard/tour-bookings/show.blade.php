@extends('plugins/marketplace::themes.vendor-dashboard.layouts.master')

@section('content')
    <div class="ps-page__content">
        <div class="ps-page__header mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1>{{ __('Booking Details') }}</h1>
                    <small class="text-muted">{{ __('Booking Code: :code', ['code' => $booking->booking_code]) }}</small>
                </div>
                <div>
                    <a href="{{ route('marketplace.vendor.tour-bookings.index') }}" class="btn btn-outline-secondary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="15,18 9,12 15,6"/>
                        </svg>
                        {{ __('Back to Bookings') }}
                    </a>
                    <a href="{{ route('marketplace.vendor.tour-bookings.edit', $booking->id) }}" class="btn btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="m18.5 2.5 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                        {{ __('Edit Booking') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Booking Information -->
                <div class="ps-card mb-4">
                    <div class="ps-card__header">
                        <h4>{{ __('Booking Information') }}</h4>
                        <div class="ms-auto">
                            @php
                                $statusColors = [
                                    'confirmed' => 'success',
                                    'pending' => 'warning',
                                    'cancelled' => 'danger',
                                    'completed' => 'info'
                                ];
                                $color = $statusColors[$booking->status] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $color }} fs-6">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="ps-card__body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>{{ __('Booking Code') }}:</strong></td>
                                        <td>{{ $booking->booking_code }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('Tour Date') }}:</strong></td>
                                        <td>{{ $booking->tour_date->format('F d, Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('Adults') }}:</strong></td>
                                        <td>{{ $booking->adults }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('Children') }}:</strong></td>
                                        <td>{{ $booking->children }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('Infants') }}:</strong></td>
                                        <td>{{ $booking->infants }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('Total People') }}:</strong></td>
                                        <td><span class="badge bg-info">{{ $booking->adults + $booking->children + $booking->infants }}</span></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>{{ __('Created') }}:</strong></td>
                                        <td>{{ $booking->created_at->format('F d, Y \a\t H:i') }}</td>
                                    </tr>
                                    @if($booking->timeSlot)
                                        <tr>
                                            <td><strong>{{ __('Time Slot') }}:</strong></td>
                                            <td>{{ $booking->timeSlot->start_time->format('H:i') }} - {{ $booking->timeSlot->end_time->format('H:i') }}</td>
                                        </tr>
                                    @endif
                                    @if($booking->special_requirements)
                                        <tr>
                                            <td><strong>{{ __('Special Requirements') }}:</strong></td>
                                            <td>{{ $booking->special_requirements }}</td>
                                        </tr>
                                    @endif
                                    @if($booking->notes)
                                        <tr>
                                            <td><strong>{{ __('Notes') }}:</strong></td>
                                            <td>{{ $booking->notes }}</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tour Information -->
                @if($booking->tour)
                    <div class="ps-card mb-4">
                        <div class="ps-card__header">
                            <h4>{{ __('Tour Information') }}</h4>
                        </div>
                        <div class="ps-card__body">
                            <div class="row">
                                <div class="col-md-3">
                                    @if($booking->tour->image)
                                        <img src="{{ RvMedia::getImageUrl($booking->tour->image, 'thumb') }}" 
                                             alt="{{ $booking->tour->name }}" 
                                             class="img-fluid rounded">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 120px;">
                                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-muted">
                                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                                <polyline points="21,15 16,10 5,21"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-9">
                                    <h5>{{ $booking->tour->name }}</h5>
                                    <p class="text-muted">{{ Str::limit($booking->tour->description, 200) }}</p>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <small class="text-muted">{{ __('Duration') }}:</small><br>
                                            <strong>
                                                @if($booking->tour->duration_days > 0)
                                                    {{ $booking->tour->duration_days }} {{ __('days') }}
                                                @endif
                                                @if($booking->tour->duration_hours > 0)
                                                    {{ $booking->tour->duration_hours }} {{ __('hours') }}
                                                @endif
                                            </strong>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted">{{ __('Location') }}:</small><br>
                                            <strong>{{ $booking->tour->location ?: __('Not specified') }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Customer Information -->
                <div class="ps-card">
                    <div class="ps-card__header">
                        <h4>{{ __('Customer Information') }}</h4>
                    </div>
                    <div class="ps-card__body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>{{ __('Name') }}:</strong></td>
                                        <td>{{ $booking->customer_name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('Email') }}:</strong></td>
                                        <td>
                                            <a href="mailto:{{ $booking->customer_email }}">{{ $booking->customer_email }}</a>
                                        </td>
                                    </tr>
                                    @if($booking->customer_phone)
                                        <tr>
                                            <td><strong>{{ __('Phone') }}:</strong></td>
                                            <td>
                                                <a href="tel:{{ $booking->customer_phone }}">{{ $booking->customer_phone }}</a>
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                            <div class="col-md-6">
                                @if($booking->customer_address)
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>{{ __('Address') }}:</strong></td>
                                            <td>{{ $booking->customer_address }}</td>
                                        </tr>
                                    </table>
                                @endif
                                @if($booking->customer_nationality)
                                    <table class="table table-borderless">
                                        <tr>
                                            <td><strong>{{ __('Nationality') }}:</strong></td>
                                            <td>{{ $booking->customer_nationality }}</td>
                                        </tr>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Payment Information -->
                <div class="ps-card mb-4">
                    <div class="ps-card__header">
                        <h4>{{ __('Payment Information') }}</h4>
                        <div class="ms-auto">
                            @php
                                $paymentColors = [
                                    'paid' => 'success',
                                    'pending' => 'warning',
                                    'failed' => 'danger',
                                    'refunded' => 'info'
                                ];
                                $color = $paymentColors[$booking->payment_status] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $color }}">
                                {{ ucfirst($booking->payment_status) }}
                            </span>
                        </div>
                    </div>
                    <div class="ps-card__body">
                        <table class="table table-borderless">
                            <tr>
                                <td>{{ __('Adult Price') }}:</td>
                                <td class="text-end">{{ format_price($booking->adult_price) }}</td>
                            </tr>
                            @if($booking->children > 0)
                                <tr>
                                    <td>{{ __('Child Price') }}:</td>
                                    <td class="text-end">{{ format_price($booking->child_price) }}</td>
                                </tr>
                            @endif
                            @if($booking->infants > 0)
                                <tr>
                                    <td>{{ __('Infant Price') }}:</td>
                                    <td class="text-end">{{ format_price($booking->infant_price) }}</td>
                                </tr>
                            @endif
                            @if($booking->discount_amount > 0)
                                <tr>
                                    <td>{{ __('Discount') }}:</td>
                                    <td class="text-end text-success">-{{ format_price($booking->discount_amount) }}</td>
                                </tr>
                            @endif
                            @if($booking->tax_amount > 0)
                                <tr>
                                    <td>{{ __('Tax') }}:</td>
                                    <td class="text-end">{{ $booking->currency }} {{ number_format($booking->tax_amount, 2) }}</td>
                                </tr>
                            @endif
                            <tr class="border-top">
                                <td><strong>{{ __('Total') }}:</strong></td>
                                <td class="text-end"><strong>{{ $booking->currency }} {{ number_format($booking->total_amount, 2) }}</strong></td>
                            </tr>
                        </table>
                        
                        @if($booking->payment_date)
                            <div class="mt-3">
                                <small class="text-muted">{{ __('Payment Date') }}:</small><br>
                                <strong>{{ $booking->payment_date->format('F d, Y \a\t H:i') }}</strong>
                            </div>
                        @endif
                        
                        @if($booking->payment_method)
                            <div class="mt-2">
                                <small class="text-muted">{{ __('Payment Method') }}:</small><br>
                                <strong>{{ ucfirst($booking->payment_method) }}</strong>
                            </div>
                        @endif
                        
                        @if($booking->payment_reference)
                            <div class="mt-2">
                                <small class="text-muted">{{ __('Payment Reference') }}:</small><br>
                                <strong>{{ $booking->payment_reference }}</strong>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="ps-card">
                    <div class="ps-card__header">
                        <h4>{{ __('Quick Actions') }}</h4>
                    </div>
                    <div class="ps-card__body">
                        <div class="d-grid gap-2">
                            @if($booking->status === 'pending')
                                <form method="POST" action="{{ route('marketplace.vendor.tour-bookings.update-status', $booking->id) }}" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="status" value="confirmed">
                                    <button type="submit" class="btn btn-success w-100">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="20,6 9,17 4,12"/>
                                        </svg>
                                        {{ __('Confirm Booking') }}
                                    </button>
                                </form>
                            @endif
                            
                            @if($booking->payment_status === 'pending')
                                <form method="POST" action="{{ route('marketplace.vendor.tour-bookings.update-payment-status', $booking->id) }}" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="payment_status" value="paid">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <line x1="12" y1="1" x2="12" y2="23"/>
                                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                                        </svg>
                                        {{ __('Mark as Paid') }}
                                    </button>
                                </form>
                            @endif
                            
                            @if($booking->status === 'confirmed')
                                <form method="POST" action="{{ route('marketplace.vendor.tour-bookings.update-status', $booking->id) }}" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" class="btn btn-info w-100">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                            <polyline points="22,4 12,14.01 9,11.01"/>
                                        </svg>
                                        {{ __('Mark as Completed') }}
                                    </button>
                                </form>
                            @endif
                            
                            @if(in_array($booking->status, ['pending', 'confirmed']))
                                <form method="POST" action="{{ route('marketplace.vendor.tour-bookings.update-status', $booking->id) }}" 
                                      onsubmit="return confirm('{{ __('Are you sure you want to cancel this booking?') }}')" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" class="btn btn-danger w-100">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <line x1="18" y1="6" x2="6" y2="18"/>
                                            <line x1="6" y1="6" x2="18" y2="18"/>
                                        </svg>
                                        {{ __('Cancel Booking') }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
