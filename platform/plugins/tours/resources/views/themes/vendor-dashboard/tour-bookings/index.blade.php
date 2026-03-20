@extends('plugins/marketplace::themes.vendor-dashboard.layouts.master')

@section('content')
    <div class="ps-page__content">
        <div class="ps-page__header mb-3">
            <h1>{{ __('Tour Bookings') }}</h1>
            <small class="text-muted">{{ __('Manage your tour bookings') }}</small>
        </div>

        <!-- Search and Filter Bar -->
        <div class="ps-page__filter mb-4">
            <form method="GET" action="{{ route('marketplace.vendor.tour-bookings.index') }}" class="row g-3">
                <div class="col-md-4">
                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="{{ __('Search by booking code, customer name, email...') }}"
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">{{ __('All Statuses') }}</option>
                        <option value="pending"   {{ request('status') === 'pending'   ? 'selected' : '' }}>{{ __('Pending') }}</option>
                        <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>{{ __('Confirmed') }}</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="payment_status" class="form-select">
                        <option value="">{{ __('All Payment Status') }}</option>
                        <option value="pending"  {{ request('payment_status') === 'pending'  ? 'selected' : '' }}>{{ __('Pending') }}</option>
                        <option value="paid"     {{ request('payment_status') === 'paid'     ? 'selected' : '' }}>{{ __('Paid') }}</option>
                        <option value="failed"   {{ request('payment_status') === 'failed'   ? 'selected' : '' }}>{{ __('Failed') }}</option>
                        <option value="refunded" {{ request('payment_status') === 'refunded' ? 'selected' : '' }}>{{ __('Refunded') }}</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">{{ __('Filter') }}</button>
                </div>
            </form>
        </div>

        <!-- Bookings Table -->
        <div class="ps-card">
            <div class="ps-card__header">
                <h4>{{ __('All Bookings') }}</h4>
            </div>
            <div class="ps-card__body">
                @if($bookings->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('Booking Code') }}</th>
                                    <th>{{ __('Tour') }}</th>
                                    <th>{{ __('Customer') }}</th>
                                    <th>{{ __('Contact') }}</th>
                                    <th>{{ __('People') }}</th>
                                    <th>{{ __('Tour Date') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Payment') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bookings as $booking)
                                    <tr>
                                        <td>
                                            <strong>{{ $booking->booking_code }}</strong>
                                        </td>
                                        <td>
                                            @if($booking->tour)
                                                <a href="{{ route('marketplace.vendor.tours.edit', $booking->tour->id) }}"
                                                   class="text-primary">
                                                    {{ Str::limit($booking->tour->name, 30) }}
                                                </a>
                                            @else
                                                <span class="text-muted">{{ __('Tour deleted') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{-- FIX: white text on customer name --}}
                                            <strong style="color: #fff !important;">{{ $booking->customer_name }}</strong>
                                        </td>
                                        <td>
                                            <div class="small">
                                                <div>{{ $booking->customer_email }}</div>
                                                @if($booking->customer_phone)
                                                    <div class="text-muted">{{ $booking->customer_phone }}</div>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info" style="color:#fff !important;">
                                                {{ $booking->adults + $booking->children + $booking->infants }}
                                            </span>
                                            <div class="small text-muted">
                                                A:{{ $booking->adults }} C:{{ $booking->children }} I:{{ $booking->infants }}
                                            </div>
                                        </td>
                                        <td>
                                            {{ $booking->tour_date->format('M d, Y') }}
                                        </td>
                                        <td>
                                            <strong>{{ format_price($booking->total_amount) }}</strong>
                                        </td>

                                        {{-- PAYMENT STATUS badge — white text --}}
                                        <td>
                                            @php
                                                $paymentBg = [
                                                    'paid'     => '#28a745',
                                                    'pending'  => '#fd7e14',
                                                    'failed'   => '#dc3545',
                                                    'refunded' => '#17a2b8',
                                                ];
                                                $pBg = $paymentBg[$booking->payment_status] ?? '#6c757d';
                                            @endphp
                                            <span style="
                                                background: {{ $pBg }};
                                                color: #ffffff !important;
                                                font-weight: 600;
                                                font-size: 12px;
                                                padding: 5px 10px;
                                                border-radius: 6px;
                                                display: inline-block;
                                                min-width: 70px;
                                                text-align: center;
                                            ">
                                                {{ ucfirst($booking->payment_status) }}
                                            </span>
                                        </td>

                                        {{-- BOOKING STATUS badge — white text --}}
                                        <td>
                                            @php
                                                $statusBg = [
                                                    'confirmed' => '#28a745',
                                                    'pending'   => '#fd7e14',
                                                    'cancelled' => '#dc3545',
                                                    'completed' => '#17a2b8',
                                                ];
                                                $sBg = $statusBg[$booking->status] ?? '#6c757d';
                                            @endphp
                                            <span style="
                                                background: {{ $sBg }};
                                                color: #ffffff !important;
                                                font-weight: 600;
                                                font-size: 12px;
                                                padding: 5px 10px;
                                                border-radius: 6px;
                                                display: inline-block;
                                                min-width: 80px;
                                                text-align: center;
                                            ">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>

                                        <td>
                                            {{ $booking->created_at->format('M d, Y') }}
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                        type="button"
                                                        data-bs-toggle="dropdown">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <circle cx="12" cy="12" r="1"></circle>
                                                        <circle cx="12" cy="5" r="1"></circle>
                                                        <circle cx="12" cy="19" r="1"></circle>
                                                    </svg>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item"
                                                           href="{{ route('marketplace.vendor.tour-bookings.show', $booking->id) }}">
                                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                <path d="m9 18 6-6-6-6"/>
                                                            </svg>
                                                            {{ __('View Details') }}
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item"
                                                           href="{{ route('marketplace.vendor.tour-bookings.edit', $booking->id) }}">
                                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                                                <path d="m18.5 2.5 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                            </svg>
                                                            {{ __('Edit') }}
                                                        </a>
                                                    </li>
                                                    @if(in_array($booking->status, ['pending', 'cancelled']))
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <form method="POST"
                                                                  action="{{ route('marketplace.vendor.tour-bookings.destroy', $booking->id) }}"
                                                                  onsubmit="return confirm('{{ __('Are you sure you want to delete this booking?') }}')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger">
                                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                        <polyline points="3,6 5,6 21,6"/>
                                                                        <path d="m19,6v14a2,2 0 0,1 -2,2H7a2,2 0 0,1 -2,-2V6m3,0V4a2,2 0 0,1 2,-2h4a2,2 0 0,1 2,2v2"/>
                                                                    </svg>
                                                                    {{ __('Delete') }}
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" class="text-muted mb-3">
                            <path d="M8 2v4"/>
                            <path d="M16 2v4"/>
                            <rect width="18" height="18" x="3" y="4" rx="2"/>
                            <path d="M3 10h18"/>
                            <path d="M8 14h.01"/>
                            <path d="M12 14h.01"/>
                            <path d="M16 14h.01"/>
                            <path d="M8 18h.01"/>
                            <path d="M12 18h.01"/>
                            <path d="M16 18h.01"/>
                        </svg>
                        <h5>{{ __('No bookings found') }}</h5>
                        <p class="text-muted">{{ __('You don\'t have any tour bookings yet.') }}</p>
                    </div>
                @endif
            </div>

            @if($bookings->hasPages())
                <div class="ps-card__footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">
                                {{ __('Showing :from to :to of :total results', [
                                    'from' => $bookings->firstItem() ?: 0,
                                    'to'   => $bookings->lastItem()  ?: 0,
                                    'total'=> $bookings->total()
                                ]) }}
                            </small>
                        </div>
                        <div>
                            {!! $bookings->withQueryString()->links() !!}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection