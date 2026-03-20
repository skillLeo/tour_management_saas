<div class="row">
    <div class="col-md-12">
        <h5>{{ __('Itinerary Schedule') }}</h5>
        <div id="schedules_container">
            @php
                $schedules = old('schedules', $tour->schedules ?? []);
                if (!is_array($schedules) && $tour && $tour->schedules) {
                    $schedules = $tour->schedules->toArray();
                }
            @endphp
            @if(count($schedules) > 0)
                @foreach($schedules as $index => $schedule)
                    <div class="card mb-3 schedule-item">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">{{ __('Day :number', ['number' => $index + 1]) }}</h6>
                            <button type="button" class="btn btn-outline-danger btn-sm remove-schedule">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{ __('Title') }}</label>
                                        <input type="text" name="schedules[{{ $index }}][title]" class="form-control" 
                                               value="{{ $schedule['title'] ?? '' }}" placeholder="{{ __('Schedule title') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{ __('Duration') }}</label>
                                        <input type="text" name="schedules[{{ $index }}][duration]" class="form-control" 
                                               value="{{ $schedule['duration'] ?? '' }}" placeholder="{{ __('e.g., 2 hours') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{ __('Order') }}</label>
                                        <input type="number" name="schedules[{{ $index }}][order]" class="form-control" 
                                               value="{{ $schedule['order'] ?? $index }}" min="0">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">{{ __('Description') }}</label>
                                <textarea name="schedules[{{ $index }}][description]" class="form-control" rows="3" 
                                          placeholder="{{ __('Detailed description of this schedule') }}">{{ $schedule['description'] ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="card mb-3 schedule-item">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">{{ __('Day 1') }}</h6>
                        <button type="button" class="btn btn-outline-danger btn-sm remove-schedule">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">{{ __('Title') }}</label>
                                    <input type="text" name="schedules[0][title]" class="form-control" placeholder="{{ __('Schedule title') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label class="form-label">{{ __('Duration') }}</label>
                                    <input type="text" name="schedules[0][duration]" class="form-control" placeholder="{{ __('e.g., 2 hours') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label class="form-label">{{ __('Order') }}</label>
                                    <input type="number" name="schedules[0][order]" class="form-control" value="0" min="0">
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">{{ __('Description') }}</label>
                            <textarea name="schedules[0][description]" class="form-control" rows="3" 
                                      placeholder="{{ __('Detailed description of this schedule') }}"></textarea>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <button type="button" class="btn btn-outline-primary" onclick="addSchedule()">
            <i class="fa fa-plus"></i> {{ __('Add Schedule Item') }}
        </button>
    </div>
</div>

<hr class="my-4">

<div class="row">
    <div class="col-md-12">
        <h5>{{ __('Frequently Asked Questions') }}</h5>
        <div id="faqs_container">
            @php
                $faqs = old('faqs', $tour->faqs ?? []);
                if (!is_array($faqs) && $tour && $tour->faqs) {
                    $faqs = $tour->faqs->toArray();
                }
            @endphp
            @if(count($faqs) > 0)
                @foreach($faqs as $index => $faq)
                    <div class="card mb-3 faq-item">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">{{ __('FAQ :number', ['number' => $index + 1]) }}</h6>
                            <button type="button" class="btn btn-outline-danger btn-sm remove-faq">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="form-group mb-3">
                                <label class="form-label">{{ __('Question') }}</label>
                                <input type="text" name="faqs[{{ $index }}][question]" class="form-control" 
                                       value="{{ $faq['question'] ?? '' }}" placeholder="{{ __('Frequently asked question') }}">
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">{{ __('Answer') }}</label>
                                <textarea name="faqs[{{ $index }}][answer]" class="form-control" rows="3" 
                                          placeholder="{{ __('Answer to the question') }}">{{ $faq['answer'] ?? '' }}</textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">{{ __('Order') }}</label>
                                <input type="number" name="faqs[{{ $index }}][order]" class="form-control" 
                                       value="{{ $faq['order'] ?? $index }}" min="0">
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="card mb-3 faq-item">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">{{ __('FAQ 1') }}</h6>
                        <button type="button" class="btn btn-outline-danger btn-sm remove-faq">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label class="form-label">{{ __('Question') }}</label>
                            <input type="text" name="faqs[0][question]" class="form-control" placeholder="{{ __('Frequently asked question') }}">
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">{{ __('Answer') }}</label>
                            <textarea name="faqs[0][answer]" class="form-control" rows="3" placeholder="{{ __('Answer to the question') }}"></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">{{ __('Order') }}</label>
                            <input type="number" name="faqs[0][order]" class="form-control" value="0" min="0">
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <button type="button" class="btn btn-outline-primary" onclick="addFaq()">
            <i class="fa fa-plus"></i> {{ __('Add FAQ') }}
        </button>
    </div>
</div>

@php
    $days = [
        'sunday' => 'Sunday', 
        'monday' => 'Monday', 
        'tuesday' => 'Tuesday', 
        'wednesday' => 'Wednesday', 
        'thursday' => 'Thursday', 
        'friday' => 'Friday', 
        'saturday' => 'Saturday'
    ];
@endphp

<div class="row">
    <div class="col-md-12">
        <h5>{{ __('Time Slots') }}</h5>
        <div id="time_slots_container">
            @php
                $timeSlots = old('time_slots', $tour->timeSlots ?? []);
                if (!is_array($timeSlots) && $tour && $tour->timeSlots) {
                    $timeSlots = $tour->timeSlots->toArray();
                }
            @endphp
            @if(count($timeSlots) > 0)
                @foreach($timeSlots as $index => $slot)
                    <div class="card mb-3 time-slot-item">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">{{ __('Time Slot :number', ['number' => $index + 1]) }}</h6>
                            <button type="button" class="btn btn-outline-danger btn-sm remove-time-slot">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{ __('Start Time') }}</label>
                                        <input type="time" name="time_slots[{{ $index }}][start_time]" class="form-control" 
                                               value="{{ $slot['start_time'] ?? '' }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">{{ __('Order') }}</label>
                                        <input type="number" name="time_slots[{{ $index }}][order]" class="form-control" 
                                               value="{{ $slot['order'] ?? $index }}" min="0">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label class="form-label">{{ __('Unavailable Days') }}</label>
                                <div class="d-flex flex-wrap">
                                    @foreach($days as $dayValue => $dayLabel)
                                        <div class="form-check form-check-inline mr-3">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   name="time_slots[{{ $index }}][restricted_days][]"
                                                   id="day-{{ $dayValue }}-{{ $index }}" 
                                                   value="{{ $dayValue }}"
                                                   {{ in_array($dayValue, $slot['restricted_days'] ?? []) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="day-{{ $dayValue }}-{{ $index }}">
                                                {{ $dayLabel }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                <small class="form-text text-muted">
                                    @php
                                        $availableDays = array_diff(
                                            array_keys($days), 
                                            $slot['restricted_days'] ?? []
                                        );
                                        $availableDaysLabels = array_map('ucfirst', $availableDays);
                                    @endphp
                                    {{ __('Available days: :days', ['days' => implode(', ', $availableDaysLabels)]) }}
                                </small>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="card mb-3 time-slot-item">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">{{ __('Time Slot 1') }}</h6>
                        <button type="button" class="btn btn-outline-danger btn-sm remove-time-slot">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">{{ __('Start Time') }}</label>
                                    <input type="time" name="time_slots[0][start_time]" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">{{ __('Order') }}</label>
                                    <input type="number" name="time_slots[0][order]" class="form-control" value="0" min="0">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label class="form-label">{{ __('Unavailable Days') }}</label>
                            <div class="d-flex flex-wrap">
                                @foreach($days as $dayValue => $dayLabel)
                                    <div class="form-check form-check-inline mr-3">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               name="time_slots[0][restricted_days][]"
                                               id="day-{{ $dayValue }}-0" 
                                               value="{{ $dayValue }}">
                                        <label class="form-check-label" for="day-{{ $dayValue }}-0">
                                            {{ $dayLabel }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <small class="form-text text-muted">
                                {{ __('Available days: :days', ['days' => implode(', ', array_keys($days))]) }}
                            </small>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <button type="button" class="btn btn-success btn-sm" onclick="addTimeSlot()">
            <i class="fa fa-plus"></i> {{ __('Add Time Slot') }}
        </button>
    </div>
</div>

<script>
let scheduleIndex = {{ count($schedules) }};
let faqIndex = {{ count($faqs) }};
let timeSlotIndex = {{ count($timeSlots) }};
const days = @json($days);

$(document).ready(function() {
    // Remove schedule
    $(document).on('click', '.remove-schedule', function() {
        $(this).closest('.schedule-item').remove();
    });
    
    // Remove FAQ
    $(document).on('click', '.remove-faq', function() {
        $(this).closest('.faq-item').remove();
    });

    // Remove time slot
    $(document).on('click', '.remove-time-slot', function() {
        $(this).closest('.time-slot-item').remove();
    });
});

function addSchedule() {
    const container = document.getElementById('schedules_container');
    const newItem = document.createElement('div');
    newItem.className = 'card mb-3 schedule-item';
    newItem.innerHTML = `
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">{{ __('Day') }} ${scheduleIndex + 1}</h6>
            <button type="button" class="btn btn-outline-danger btn-sm remove-schedule">
                <i class="fa fa-trash"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label">{{ __('Title') }}</label>
                        <input type="text" name="schedules[${scheduleIndex}][title]" class="form-control" placeholder="{{ __('Schedule title') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mb-3">
                        <label class="form-label">{{ __('Duration') }}</label>
                        <input type="text" name="schedules[${scheduleIndex}][duration]" class="form-control" placeholder="{{ __('e.g., 2 hours') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group mb-3">
                        <label class="form-label">{{ __('Order') }}</label>
                        <input type="number" name="schedules[${scheduleIndex}][order]" class="form-control" value="${scheduleIndex}" min="0">
                    </div>
                </div>
            </div>
            <div class="form-group mb-3">
                <label class="form-label">{{ __('Description') }}</label>
                <textarea name="schedules[${scheduleIndex}][description]" class="form-control" rows="3" placeholder="{{ __('Detailed description of this schedule') }}"></textarea>
            </div>
        </div>
    `;
    container.appendChild(newItem);
    scheduleIndex++;
}

function addFaq() {
    const container = document.getElementById('faqs_container');
    const newItem = document.createElement('div');
    newItem.className = 'card mb-3 faq-item';
    newItem.innerHTML = `
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">{{ __('FAQ') }} ${faqIndex + 1}</h6>
            <button type="button" class="btn btn-outline-danger btn-sm remove-faq">
                <i class="fa fa-trash"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="form-group mb-3">
                <label class="form-label">{{ __('Question') }}</label>
                <input type="text" name="faqs[${faqIndex}][question]" class="form-control" placeholder="{{ __('Frequently asked question') }}">
            </div>
            <div class="form-group mb-3">
                <label class="form-label">{{ __('Answer') }}</label>
                <textarea name="faqs[${faqIndex}][answer]" class="form-control" rows="3" placeholder="{{ __('Answer to the question') }}"></textarea>
            </div>
            <div class="form-group mb-3">
                <label class="form-label">{{ __('Order') }}</label>
                <input type="number" name="faqs[${faqIndex}][order]" class="form-control" value="${faqIndex}" min="0">
            </div>
        </div>
    `;
    container.appendChild(newItem);
    faqIndex++;
}

function addTimeSlot() {
    const container = document.getElementById('time_slots_container');
    const newItem = document.createElement('div');
    newItem.className = 'card mb-3 time-slot-item';
    newItem.innerHTML = `
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">{{ __('Time Slot') }} ${timeSlotIndex + 1}</h6>
            <button type="button" class="btn btn-outline-danger btn-sm remove-time-slot">
                <i class="fa fa-trash"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label">{{ __('Start Time') }}</label>
                        <input type="time" name="time_slots[${timeSlotIndex}][start_time]" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label">{{ __('Order') }}</label>
                        <input type="number" name="time_slots[${timeSlotIndex}][order]" class="form-control" value="${timeSlotIndex}" min="0">
                    </div>
                </div>
            </div>
            
            <div class="form-group mb-3">
                <label class="form-label">{{ __('Unavailable Days') }}</label>
                <div class="d-flex flex-wrap">
                    ${Object.entries(days).map(([dayValue, dayLabel]) => `
                        <div class="form-check form-check-inline mr-3">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   name="time_slots[${timeSlotIndex}][restricted_days][]"
                                   id="day-${dayValue}-${timeSlotIndex}" 
                                   value="${dayValue}">
                            <label class="form-check-label" for="day-${dayValue}-${timeSlotIndex}">
                                ${dayLabel}
                            </label>
                        </div>
                    `).join('')}
                </div>
                <small class="form-text text-muted">
                    {{ __('Available days: :days', ['days' => implode(', ', array_keys($days))]) }}
                </small>
            </div>
        </div>
    `;
    container.appendChild(newItem);
    timeSlotIndex++;
}
</script>
