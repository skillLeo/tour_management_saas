<div class="form-group mb-3">
    <label for="languages" class="form-label">{{ __('Languages') }}</label>
    <select name="languages[]" id="languages" class="form-control select-multiple" multiple>
        @foreach(\Botble\Tours\Models\TourLanguage::where('status', 'published')->orderBy('order')->get() as $language)
            <option value="{{ $language->id }}" 
                {{ isset($tour) && $tour->languages->contains('id', $language->id) ? 'selected' : '' }}>
                {{ $language->name }}
            </option>
        @endforeach
    </select>
    <small class="form-text text-muted">{{ __('Select the languages available for this tour') }}</small>
</div>