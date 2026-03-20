<div class="row">
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="included_services" class="form-label">{{ __('Included Services') }}</label>
            <div id="included_services_container">
                @php
                    $includedServices = old('included_services', $tour->included_services ?? []);
                    if (!is_array($includedServices)) {
                        $includedServices = [];
                    }
                @endphp
                @if(count($includedServices) > 0)
                    @foreach($includedServices as $index => $service)
                        <div class="input-group mb-2 service-item">
                            <input type="text" name="included_services[]" class="form-control" 
                                   value="{{ $service }}" placeholder="{{ __('Service description') }}">
                            <button type="button" class="btn btn-outline-danger btn-sm remove-service">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    @endforeach
                @else
                    <div class="input-group mb-2 service-item">
                        <input type="text" name="included_services[]" class="form-control" 
                               placeholder="{{ __('Service description') }}">
                        <button type="button" class="btn btn-outline-danger btn-sm remove-service">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                @endif
            </div>
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="addIncludedService()">
                <i class="fa fa-plus"></i> {{ __('Add Service') }}
            </button>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="excluded_services" class="form-label">{{ __('Excluded Services') }}</label>
            <div id="excluded_services_container">
                @php
                    $excludedServices = old('excluded_services', $tour->excluded_services ?? []);
                    if (!is_array($excludedServices)) {
                        $excludedServices = [];
                    }
                @endphp
                @if(count($excludedServices) > 0)
                    @foreach($excludedServices as $index => $service)
                        <div class="input-group mb-2 service-item">
                            <input type="text" name="excluded_services[]" class="form-control" 
                                   value="{{ $service }}" placeholder="{{ __('Service description') }}">
                            <button type="button" class="btn btn-outline-danger btn-sm remove-service">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    @endforeach
                @else
                    <div class="input-group mb-2 service-item">
                        <input type="text" name="excluded_services[]" class="form-control" 
                               placeholder="{{ __('Service description') }}">
                        <button type="button" class="btn btn-outline-danger btn-sm remove-service">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                @endif
            </div>
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="addExcludedService()">
                <i class="fa fa-plus"></i> {{ __('Add Service') }}
            </button>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="activities" class="form-label">{{ __('Activities') }}</label>
            <div id="activities_container">
                @php
                    $activities = old('activities', $tour->activities ?? []);
                    if (!is_array($activities)) {
                        $activities = [];
                    }
                @endphp
                @if(count($activities) > 0)
                    @foreach($activities as $index => $activity)
                        <div class="input-group mb-2 service-item">
                            <input type="text" name="activities[]" class="form-control" 
                                   value="{{ $activity }}" placeholder="{{ __('Activity description') }}">
                            <button type="button" class="btn btn-outline-danger btn-sm remove-service">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    @endforeach
                @else
                    <div class="input-group mb-2 service-item">
                        <input type="text" name="activities[]" class="form-control" 
                               placeholder="{{ __('Activity description') }}">
                        <button type="button" class="btn btn-outline-danger btn-sm remove-service">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                @endif
            </div>
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="addActivity()">
                <i class="fa fa-plus"></i> {{ __('Add Activity') }}
            </button>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="tour_highlights" class="form-label">{{ __('Tour Highlights') }}</label>
            <div id="tour_highlights_container">
                @php
                    $highlights = old('tour_highlights', $tour->tour_highlights ?? []);
                    if (!is_array($highlights)) {
                        $highlights = [];
                    }
                @endphp
                @if(count($highlights) > 0)
                    @foreach($highlights as $index => $highlight)
                        <div class="input-group mb-2 service-item">
                            <input type="text" name="tour_highlights[]" class="form-control" 
                                   value="{{ $highlight }}" placeholder="{{ __('Highlight description') }}">
                            <button type="button" class="btn btn-outline-danger btn-sm remove-service">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    @endforeach
                @else
                    <div class="input-group mb-2 service-item">
                        <input type="text" name="tour_highlights[]" class="form-control" 
                               placeholder="{{ __('Highlight description') }}">
                        <button type="button" class="btn btn-outline-danger btn-sm remove-service">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                @endif
            </div>
            <button type="button" class="btn btn-outline-primary btn-sm" onclick="addHighlight()">
                <i class="fa fa-plus"></i> {{ __('Add Highlight') }}
            </button>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Remove service/activity/highlight
    $(document).on('click', '.remove-service', function() {
        $(this).closest('.service-item').remove();
    });
});

function addIncludedService() {
    const container = document.getElementById('included_services_container');
    const newItem = document.createElement('div');
    newItem.className = 'input-group mb-2 service-item';
    newItem.innerHTML = `
        <input type="text" name="included_services[]" class="form-control" placeholder="{{ __('Service description') }}">
        <button type="button" class="btn btn-outline-danger btn-sm remove-service">
            <i class="fa fa-trash"></i>
        </button>
    `;
    container.appendChild(newItem);
}

function addExcludedService() {
    const container = document.getElementById('excluded_services_container');
    const newItem = document.createElement('div');
    newItem.className = 'input-group mb-2 service-item';
    newItem.innerHTML = `
        <input type="text" name="excluded_services[]" class="form-control" placeholder="{{ __('Service description') }}">
        <button type="button" class="btn btn-outline-danger btn-sm remove-service">
            <i class="fa fa-trash"></i>
        </button>
    `;
    container.appendChild(newItem);
}

function addActivity() {
    const container = document.getElementById('activities_container');
    const newItem = document.createElement('div');
    newItem.className = 'input-group mb-2 service-item';
    newItem.innerHTML = `
        <input type="text" name="activities[]" class="form-control" placeholder="{{ __('Activity description') }}">
        <button type="button" class="btn btn-outline-danger btn-sm remove-service">
            <i class="fa fa-trash"></i>
        </button>
    `;
    container.appendChild(newItem);
}

function addHighlight() {
    const container = document.getElementById('tour_highlights_container');
    const newItem = document.createElement('div');
    newItem.className = 'input-group mb-2 service-item';
    newItem.innerHTML = `
        <input type="text" name="tour_highlights[]" class="form-control" placeholder="{{ __('Highlight description') }}">
        <button type="button" class="btn btn-outline-danger btn-sm remove-service">
            <i class="fa fa-trash"></i>
        </button>
    `;
    container.appendChild(newItem);
}
</script>
