@php
    Theme::set('pageTitle', __('Booking Confirmation'));
@endphp

<div class="container mb-80 mt-50">
    <div class="row">
        <div class="col-lg-8 m-auto">
            <div class="card p-5 text-center">
                <div class="mb-4">
                    <i class="fi-rs-check-circle text-success" style="font-size: 5rem;"></i>
                </div>
                
                <h2 class="mt-2 mb-4">{{ __('Thank You for Your Booking!') }}</h2>
                <p class="mb-4">{{ __('Your booking has been received and is now being processed. We will contact you shortly.') }}</p>
                
                <div class="booking-details mt-4 mb-4">
                    <h4 class="mb-3">{{ __('Booking Details') }}</h4>
                    
                    <div class="row">
                        <div class="col-md-6 text-start">
                            <p><strong>{{ __('Booking ID') }}:</strong> #{{ $booking->id }}</p>
                            <p><strong>{{ __('Tour') }}:</strong> {{ $tour->name }}</p>
                            <p><strong>{{ __('Tour Date') }}:</strong> {{ \Carbon\Carbon::parse($booking->tour_date)->format('F j, Y') }}</p>
                            @if($booking->timeSlot)
                                <p><strong>{{ __('Time Slot') }}:</strong>
                                @php
                                    $slot = $booking->timeSlot;
                                    $tour = $booking->tour;
                                    $startTime = $slot->start_time->format('H:i');
                                    $endTime = $slot->end_time->format('H:i');
                                    $durationDays = $tour->duration_days;
                                    $durationHours = $tour->duration_hours;
                                    $durationNights = $tour->duration_nights;
                                    
                                    // Format based on duration type
                                    if ($durationDays > 0) {
                                        // Multi-day tour
                                        $startDate = \Carbon\Carbon::parse($booking->tour_date)->format('j M');
                                        $endDate = \Carbon\Carbon::parse($booking->tour_date)->addDays($durationDays)->format('j M');
                                        echo $startTime . ' ' . $startDate . ' - ' . $endTime . ' ' . $endDate;
                                        if ($durationNights > 0) {
                                            echo ' (' . $durationDays . ' ' . __('days') . ' / ' . $durationNights . ' ' . __('nights') . ')';
                                        } else {
                                            echo ' (' . $durationDays . ' ' . __('days') . ')';
                                        }
                                    } else if ($durationHours > 0) {
                                        // Hours-based tour
                                        echo $startTime . ' - ' . $endTime . ' (' . $durationHours . ' ' . __('hours') . ')';
                                    } else {
                                        // Default to simple time range
                                        echo $startTime . ' - ' . $endTime;
                                    }
                                @endphp
                                </p>
                            @elseif(!empty($booking->time_slot_ids))
                                <p><strong>{{ __('Time Slot') }}:</strong>
                                @php
                                    $slotIds = is_array($booking->time_slot_ids) ? $booking->time_slot_ids : json_decode($booking->time_slot_ids, true);
                                    if (is_array($slotIds) && !empty($slotIds)) {
                                        $timeSlots = \Botble\Tours\Models\TourTimeSlot::whereIn('id', $slotIds)->get();
                                        $tour = $booking->tour;
                                        $durationDays = $tour->duration_days;
                                        $durationHours = $tour->duration_hours;
                                        $durationNights = $tour->duration_nights;
                                        
                                        $formattedSlots = [];
                                        foreach ($timeSlots as $slot) {
                                            $startTime = $slot->start_time->format('H:i');
                                            $endTime = $slot->end_time->format('H:i');
                                            
                                            // Format based on duration type
                                            if ($durationDays > 0) {
                                                // Multi-day tour
                                                $startDate = \Carbon\Carbon::parse($booking->tour_date)->format('j M');
                                                $endDate = \Carbon\Carbon::parse($booking->tour_date)->addDays($durationDays)->format('j M');
                                                $formattedTime = $startTime . ' ' . $startDate . ' - ' . $endTime . ' ' . $endDate;
                                            } else {
                                                // Hours-based tour
                                                $formattedTime = $startTime . ' - ' . $endTime;
                                            }
                                            
                                            $formattedSlots[] = $formattedTime;
                                        }
                                        
                                        echo implode(', ', $formattedSlots);
                                        
                                        // Add duration info after the times
                                        if ($durationDays > 0) {
                                            if ($durationNights > 0) {
                                                echo ' (' . $durationDays . ' ' . __('days') . ' / ' . $durationNights . ' ' . __('nights') . ')';
                                            } else {
                                                echo ' (' . $durationDays . ' ' . __('days') . ')';
                                            }
                                        } else if ($durationHours > 0) {
                                            echo ' (' . $durationHours . ' ' . __('hours') . ')';
                                        }
                                    } else {
                                        echo '';
                                    }
                                @endphp
                                </p>
                            @endif
                            <p><strong>{{ __('Number of Adults') }}:</strong> {{ $booking->adults }}</p>
                            @if($booking->children > 0)
                                <p><strong>{{ __('Number of Children') }}:</strong> {{ $booking->children }}</p>
                            @endif
                            @if($booking->infants > 0)
                                <p><strong>{{ __('Number of Infants') }}:</strong> {{ $booking->infants }}</p>
                            @endif
                        </div>
                        <div class="col-md-6 text-start">
                            <p><strong>{{ __('Customer Name') }}:</strong> {{ $booking->customer_name }}</p>
                            <p><strong>{{ __('Email') }}:</strong> {{ $booking->customer_email }}</p>
                            <p><strong>{{ __('Phone') }}:</strong> {{ $booking->customer_phone }}</p>
                            @if($booking->customer_nationality)
                                <p><strong>{{ __('plugins/tours::tours.Spoken Language') }}:</strong> {{ $booking->customer_nationality }}</p>
                            @endif
                            @if($booking->customer_address)
                                <p><strong>{{ __('Address') }}:</strong> {{ $booking->customer_address }}</p>
                            @endif
                            <p><strong>{{ __('Total Amount') }}:</strong> {{ format_price($booking->total_amount) }}</p>
                            <p><strong>{{ __('Payment Status') }}:</strong> <span class="badge bg-warning">{{ __('Pending') }}</span></p>
                        </div>
                    </div>
                    
                    @if($booking->special_requirements)
                        <div class="row mt-3">
                            <div class="col-12 text-start">
                                <p><strong>{{ __('Special Requirements') }}:</strong></p>
                                <p>{{ $booking->special_requirements }}</p>
                            </div>
                        </div>
                    @endif
                </div>
                
                <div class="mt-4">
                    <a href="{{ route('public.tours.index') }}" class="btn btn-primary">{{ __('Browse More Tours') }}</a>
                    <a href="{{ route('public.index') }}" class="btn btn-outline-primary ms-2">{{ __('Back to Home') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>