<!-- Tour Calendar Time Slots -->
<div class="tour-calendar-container" data-tour-slug="{{ $tour->slug }}">
    <div class="form-group mb-3">
        <label class="fw-bold mb-2">{{ __('plugins/tours::tours.Select Date & Time') }}</label>
        <p class="text-muted small">{{ __('plugins/tours::tours.Choose a date from the calendar, then select your preferred time slot') }}</p>
        
        <div class="calendar-wrapper">
            <!-- Calendar Container -->
            <div id="tourCalendar" class="tour-calendar"></div>
            
            <!-- Selected Slots Display -->
            <div id="selectedSlots" class="selected-slots mt-3">
                <h6>{{ __('plugins/tours::tours.Selected Time Slot') }}:</h6>
                <div id="selectedSlotsList" class="row g-2">
                    <div class="col-12"><p class="text-muted">{{ __('plugins/tours::tours.No time slot selected') }}</p></div>
                </div>
            </div>
            
            <!-- Hidden input for form submission -->
            <input type="hidden" name="time_slot_ids" id="timeSlotIds" value="">
        </div>
    </div>
</div>

@push('styles')
    <link rel="stylesheet" href="{{ asset('vendor/core/plugins/tours/css/tour-calendar.css') }}">
@endpush
