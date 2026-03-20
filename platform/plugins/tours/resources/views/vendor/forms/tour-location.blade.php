<div class="row">
    <div class="col-md-12">
        <div class="form-group mb-3">
            <label for="location" class="form-label">{{ __('Location') }}</label>
            <input type="text" name="location" id="location" class="form-control" 
                   value="{{ old('location', $tour->location ?? '') }}" placeholder="{{ __('Tour location') }}">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="departure_location" class="form-label">{{ __('Departure Location') }}</label>
            <input type="text" name="departure_location" id="departure_location" class="form-control" 
                   value="{{ old('departure_location', $tour->departure_location ?? '') }}" 
                   placeholder="{{ __('Where the tour starts') }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="return_location" class="form-label">{{ __('Return Location') }}</label>
            <input type="text" name="return_location" id="return_location" class="form-control" 
                   value="{{ old('return_location', $tour->return_location ?? '') }}" 
                   placeholder="{{ __('Where the tour ends') }}">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="latitude" class="form-label">{{ __('Latitude') }}</label>
            <input type="number" name="latitude" id="latitude" class="form-control" 
                   value="{{ old('latitude', $tour->latitude ?? '') }}" 
                   step="any" min="-90" max="90" placeholder="0.000000">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="longitude" class="form-label">{{ __('Longitude') }}</label>
            <input type="number" name="longitude" id="longitude" class="form-control" 
                   value="{{ old('longitude', $tour->longitude ?? '') }}" 
                   step="any" min="-180" max="180" placeholder="0.000000">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group mb-3">
            <button type="button" class="btn btn-info btn-sm" onclick="getCurrentLocation()">
                <i class="fa fa-map-marker"></i> {{ __('Get Current Location') }}
            </button>
            <small class="form-text text-muted">{{ __('Click to automatically fill coordinates with your current location') }}</small>
        </div>
    </div>
</div>

<script>
function getCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            document.getElementById('latitude').value = position.coords.latitude;
            document.getElementById('longitude').value = position.coords.longitude;
        }, function(error) {
            alert('{{ __("Unable to get your location") }}');
        });
    } else {
        alert('{{ __("Geolocation is not supported by this browser") }}');
    }
}
</script>
