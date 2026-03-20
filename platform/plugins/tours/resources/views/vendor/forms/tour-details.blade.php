<div class="row">
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="duration_days" class="form-label">{{ __('Duration (Days)') }}</label>
            <input type="number" name="duration_days" id="duration_days" class="form-control" 
                   value="{{ old('duration_days', $tour->duration_days ?? 0) }}" min="0" max="365">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="duration_nights" class="form-label">{{ __('Duration (Nights)') }}</label>
            <input type="number" name="duration_nights" id="duration_nights" class="form-control" 
                   value="{{ old('duration_nights', $tour->duration_nights ?? 0) }}" min="0" max="365">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="duration_hours" class="form-label">{{ __('Duration (Hours)') }}</label>
            <input type="number" name="duration_hours" id="duration_hours" class="form-control" 
                   value="{{ old('duration_hours', $tour->duration_hours ?? 0) }}" min="0" max="24">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="booking_advance_days" class="form-label">{{ __('Booking Advance Days') }}</label>
            <input type="number" name="booking_advance_days" id="booking_advance_days" class="form-control" 
                   value="{{ old('booking_advance_days', $tour->booking_advance_days ?? 1) }}" min="0" max="365">
            <small class="form-text text-muted">{{ __('How many days in advance booking should be allowed') }}</small>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="min_people" class="form-label">{{ __('Minimum People') }} <span class="text-danger">*</span></label>
            <input type="number" name="min_people" id="min_people" class="form-control" 
                   value="{{ old('min_people', $tour->min_people ?? 1) }}" min="1" max="1000" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="max_people" class="form-label">{{ __('Maximum People') }} <span class="text-danger">*</span></label>
            <input type="number" name="max_people" id="max_people" class="form-control" 
                   value="{{ old('max_people', $tour->max_people ?? 10) }}" min="1" max="1000" required>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="price" class="form-label">{{ __('Price') }} <span class="text-danger">*</span></label>
            <div class="input-group">
                <input type="number" name="price" id="price" class="form-control" 
                       value="{{ old('price', $tour->price ?? 0) }}" min="0" step="0.01" required>
                <span class="input-group-text">{{ get_application_currency()->symbol }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="children_price" class="form-label">{{ __('Children Price') }}</label>
            <div class="input-group">
                <input type="number" name="children_price" id="children_price" class="form-control" 
                       value="{{ old('children_price', $tour->children_price ?? '') }}" min="0" step="0.01">
                <span class="input-group-text">{{ get_application_currency()->symbol }}</span>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="infants_price" class="form-label">{{ __('Infants Price') }}</label>
            <div class="input-group">
                <input type="number" name="infants_price" id="infants_price" class="form-control" 
                       value="{{ old('infants_price', $tour->infants_price ?? '') }}" min="0" step="0.01">
                <span class="input-group-text">{{ get_application_currency()->symbol }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="sale_percentage" class="form-label">{{ __('Sale Percentage') }}</label>
            <div class="input-group">
                <input type="number" name="sale_percentage" id="sale_percentage" class="form-control" 
                       value="{{ old('sale_percentage', $tour->sale_percentage ?? '') }}" min="0" max="100" step="0.01">
                <span class="input-group-text">%</span>
            </div>
        </div>
    </div>
</div>

@if($currencies && count($currencies) > 1)
<div class="row">
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="currency" class="form-label">{{ __('Currency') }}</label>
            <select name="currency" id="currency" class="form-control">
                @foreach($currencies as $currency)
                    <option value="{{ $currency['symbol'] }}" 
                            @if(old('currency', $tour->currency ?? get_application_currency()->symbol) == $currency['symbol']) selected @endif>
                        {{ $currency['symbol'] }} - {{ $currency['title'] }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>
@endif
