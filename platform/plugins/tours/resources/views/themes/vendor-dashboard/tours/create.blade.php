@extends('plugins/marketplace::themes.vendor-dashboard.layouts.master')

<link rel="stylesheet" href="{{ asset('vendor/core/plugins/tours/css/vendor-tour-form.css') }}">
{{Html::script('vendor/core/plugins/tours/js/vendor-tour-form.js')}}
{{Html::script('vendor/core/plugins/tours/js/vendor-tour-slug.js')}}

@push('header')
    <script src="{{ asset('vendor/core/core/base/libraries/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('vendor/core/core/base/js/editor.js') }}"></script>
@endpush
@section('content')
    <div class="ps-page__content">
        <div class="ps-page__header">
            <h3>{{ __('Create New Tour') }}</h3>
            <div class="ps-page__actions">
                <a href="{{ route('marketplace.vendor.tours.index') }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left"></i> {{ __('Back to Tours') }}
                </a>
            </div>
        </div>

        <div class="ps-page__body mb-4 mt-3">
            <form action="{{ route('marketplace.vendor.tours.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row">
                    <div class="col-lg-8">
                        <!-- Basic Information -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>{{ __('Basic Information') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">{{ __('Tour Name') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control" 
                                           value="{{ old('name') }}" required>
                                    @if($errors->has('name'))
                                        <div class="text-danger">{{ $errors->first('name') }}</div>
                                    @endif
                                </div>

                                <div class="form-group mb-3 permalink-field-wrapper" data-field-name="name">
                                    <label for="slug" class="form-label">{{ __('Permalink') }}:</label>
                                    <div class="permalink-wrapper">
                                        <span class="permalink-prefix">{{ url('/tours') }}/</span>
                                        <input type="text" name="slug" id="slug" class="form-control permalink-input" 
                                               value="{{ old('slug') }}" placeholder="{{ __('auto-generated-from-name') }}">
                                        <button type="button" class="btn btn-sm btn-outline-secondary permalink-edit-btn" id="auto-slug-btn" title="{{ __('Auto-generate from name') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z"/>
                                                <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5.002 5.002 0 0 0 8 3zM3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9H3.1z"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <small class="form-text text-muted">{{ __('Leave blank to generate URL from tour name.') }}</small>
                                    @if($errors->has('slug'))
                                        <div class="text-danger mt-1">{{ $errors->first('slug') }}</div>
                                    @endif
                                </div>

                                <div class="form-group mb-3">
                                    <label for="description" class="form-label">{{ __('Description') }}</label>
                                    <textarea name="description" id="description" class="form-control editor-ckeditor" rows="4" 
                                              placeholder="{{ __('Brief description of the tour') }}">{{ old('description') }}</textarea>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="content" class="form-label">{{ __('Detailed Content') }}</label>
                                    <textarea name="content" id="content" class="form-control editor-ckeditor" rows="6" 
                                              placeholder="{{ __('Detailed description with itinerary, highlights, etc.') }}">{{ old('content') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Tour Details -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>{{ __('Tour Details') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="tour_type" class="form-label">{{ __('Tour Type') }}</label>
                                            <select name="tour_type" id="tour_type" class="form-select">
                                                <option value="">{{ trans('plugins/tours::tours.form.select_tour_type') }}</option>
                                                <option value="shared" {{ old('tour_type') == 'shared' ? 'selected' : '' }}>{{ __('Shared Tour') }}</option>
                                                <option value="private" {{ old('tour_type') == 'private' ? 'selected' : '' }}>{{ __('Private Tour') }}</option>
                                                <option value="transfer" {{ old('tour_type') == 'transfer' ? 'selected' : '' }}>{{ __('Transfer') }}</option>
                                                <option value="small_group" {{ old('tour_type') == 'small_group' ? 'selected' : '' }}>{{ __('Small Group') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="tour_length" class="form-label">{{ __('Tour Length') }}</label>
                                            <select name="tour_length" id="tour_length" class="form-select">
                                                <option value="">{{ trans('plugins/tours::tours.form.select_tour_length') }}</option>
                                                <option value="half_day" {{ old('tour_length') == 'half_day' ? 'selected' : '' }}>{{ __('Half Day Activities') }}</option>
                                                <option value="full_day" {{ old('tour_length') == 'full_day' ? 'selected' : '' }}>{{ __('Full Day Activities') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="duration_days" class="form-label">{{ __('Duration (Days)') }}</label>
                                            <input type="number" name="duration_days" id="duration_days" class="form-control" 
                                                   value="{{ old('duration_days', 0) }}" min="0" max="365">
                                            <small class="form-text text-muted">{{ __('Leave 0 for hour-based tours') }}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="duration_hours" class="form-label">{{ __('Duration (Hours)') }}</label>
                                            <input type="number" name="duration_hours" id="duration_hours" class="form-control" 
                                                   value="{{ old('duration_hours', 0) }}" min="0" max="24">
                                            <small class="form-text text-muted">{{ __('For hourly tours') }}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="duration_nights" class="form-label">{{ __('Duration (Nights)') }}</label>
                                            <input type="number" name="duration_nights" id="duration_nights" class="form-control" 
                                                   value="{{ old('duration_nights', 0) }}" min="0" max="365">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="min_people" class="form-label">{{ __('Minimum People') }} <span class="text-danger">*</span></label>
                                            <input type="number" name="min_people" id="min_people" class="form-control" 
                                                   value="{{ old('min_people', 1) }}" min="1" max="1000" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="max_people" class="form-label">{{ __('Maximum People') }} <span class="text-danger">*</span></label>
                                            <input type="number" name="max_people" id="max_people" class="form-control" 
                                                   value="{{ old('max_people', 10) }}" min="1" max="1000" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="price" class="form-label">{{ __('Adult Price') }} <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" name="price" id="price" class="form-control" 
                                                       value="{{ old('price', 0) }}" min="0" step="0.01" required>
                                                <span class="input-group-text">{{ get_application_currency()->symbol }}</span>
                                            </div>
                                            <small class="form-text text-muted">{{ __('Price for adults (12+ years)') }}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="children_price" class="form-label">{{ __('Children Price') }}</label>
                                            <div class="input-group">
                                                <input type="number" name="children_price" id="children_price" class="form-control" 
                                                       value="{{ old('children_price', '') }}" min="0" step="0.01">
                                                <span class="input-group-text">{{ get_application_currency()->symbol }}</span>
                                            </div>
                                            <small class="form-text text-muted">{{ __('Price for children (2-11 years)') }}</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="infants_price" class="form-label">{{ __('Infants Price') }}</label>
                                            <div class="input-group">
                                                <input type="number" name="infants_price" id="infants_price" class="form-control" 
                                                       value="{{ old('infants_price', '') }}" min="0" step="0.01">
                                                <span class="input-group-text">{{ get_application_currency()->symbol }}</span>
                                            </div>
                                            <small class="form-text text-muted">{{ __('Price for infants (0-1 years)') }}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="sale_percentage" class="form-label">{{ __('Sale Percentage (%)') }}</label>
                                            <input type="number" name="sale_percentage" id="sale_percentage" class="form-control" 
                                                   value="{{ old('sale_percentage', '') }}" min="0" max="100" step="0.01">
                                            <small class="form-text text-muted">{{ __('Discount percentage (0-100%)') }}</small>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>{{ __('Location Information') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label for="location" class="form-label">{{ __('Location') }}</label>
                                    <input type="text" name="location" id="location" class="form-control" 
                                           value="{{ old('location', '') }}" placeholder="{{ __('Tour location') }}">
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="departure_location" class="form-label">{{ __('Departure Location') }}</label>
                                            <input type="text" name="departure_location" id="departure_location" class="form-control" 
                                                   value="{{ old('departure_location', '') }}" 
                                                   placeholder="{{ __('Where the tour starts') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="return_location" class="form-label">{{ __('Return Location') }}</label>
                                            <input type="text" name="return_location" id="return_location" class="form-control" 
                                                   value="{{ old('return_location', '') }}" 
                                                   placeholder="{{ __('Where the tour ends') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="latitude" class="form-label">{{ __('Latitude') }}</label>
                                            <input type="number" name="latitude" id="latitude" class="form-control" 
                                                   value="{{ old('latitude', '') }}" step="any" min="-90" max="90"
                                                   placeholder="{{ __('e.g. 25.276987') }}">
                                            <small class="form-text text-muted">{{ __('Decimal degrees format') }}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="longitude" class="form-label">{{ __('Longitude') }}</label>
                                            <input type="number" name="longitude" id="longitude" class="form-control" 
                                                   value="{{ old('longitude', '') }}" step="any" min="-180" max="180"
                                                   placeholder="{{ __('e.g. 55.296249') }}">
                                            <small class="form-text text-muted">{{ __('Decimal degrees format') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <!-- Category & Settings -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>{{ __('Category & Settings') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label for="category_id" class="form-label">{{ __('Category') }} <span class="text-danger">*</span></label>
                                    <select name="category_id" id="category_id" class="form-control" required>
                                        <option value="">{{ __('-- Select Category --') }}</option>
                                        @foreach($categories as $id => $name)
                                            <option value="{{ $id }}" {{ old('category_id') == $id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('category_id'))
                                        <div class="text-danger">{{ $errors->first('category_id') }}</div>
                                    @endif
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="city_id" class="form-label">{{ __('City') }} <span class="text-danger">*</span></label>
                                    <select name="city_id" id="city_id" class="form-control" required>
                                        <option value="">{{ __('-- Select City --') }}</option>
                                        @foreach($cities as $id => $name)
                                            <option value="{{ $id }}" {{ old('city_id') == $id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('city_id'))
                                        <div class="text-danger">{{ $errors->first('city_id') }}</div>
                                    @endif
                                </div>

                                <div class="form-check mb-3">
                                    <input type="checkbox" name="is_featured" id="is_featured" class="form-check-input" 
                                           value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                    <label for="is_featured" class="form-check-label">{{ __('Featured Tour') }}</label>
                                </div>

                                <div class="form-check mb-3">
                                    <input type="checkbox" name="allow_booking" id="allow_booking" class="form-check-input" 
                                           value="1" {{ old('allow_booking', 1) ? 'checked' : '' }}>
                                    <label for="allow_booking" class="form-check-label">{{ __('Allow Booking') }}</label>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="booking_advance_days" class="form-label">{{ __('Booking Advance Days') }}</label>
                                    <input type="number" name="booking_advance_days" id="booking_advance_days" class="form-control" 
                                           value="{{ old('booking_advance_days', 1) }}" min="0" max="365">
                                    <small class="form-text text-muted">{{ __('How many days in advance can customers book') }}</small>
                                </div>
                                
                                <!-- Tour Languages -->
                                <div class="form-group mb-3">
                                    <label for="languages" class="form-label">{{ __('Languages') }}</label>
                                    <select name="languages[]" id="languages" class="form-control select-multiple" multiple>
                                        @foreach(\Botble\Tours\Models\TourLanguage::where('status', 'published')->orderBy('order')->get() as $language)
                                            <option value="{{ $language->id }}" 
                                                {{ in_array($language->id, old('languages', [])) ? 'selected' : '' }}>
                                                {{ $language->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">{{ __('Select the languages available for this tour') }}</small>
                                </div>
                            </div>
                        </div>

                        <!-- Featured Image -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>{{ __('Featured Image') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="image" class="form-label">{{ __('Upload Featured Image') }}</label>
                                    <input type="file" name="image" id="image" class="form-control" accept="image/*">
                                    <small class="form-text text-muted">{{ __('Recommended size: 800x600px, Max 5MB') }}</small>
                                    @if($errors->has('image'))
                                        <div class="text-danger">{{ $errors->first('image') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Gallery Images -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>{{ __('Gallery Images') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="gallery" class="form-label">{{ __('Gallery Images') }}</label>
                                    <div class="gallery-upload-area border border-dashed p-4 text-center" id="gallery-upload-area">
                                        <input type="file" name="gallery[]" id="gallery" class="form-control" 
                                               accept="image/*" multiple style="display:none;">
                                        <p class="text-muted mb-2">
                                            <i class="ti ti-upload me-2"></i>
                                            {{ __('Click to upload or drag and drop') }}
                                        </p>
                                        <small class="text-muted">
                                            {{ __('PNG, JPG, GIF up to 5MB (Max 10 images)') }}
                                        </small>
                                    </div>
                                    <div id="gallery-preview" class="mt-2 d-flex flex-wrap"></div>
                                </div>
                                
                                <!-- Gallery container for uploads preview -->
                                <div id="tour-gallery-container" class="tour-gallery-container"></div>

                            </div>
                        </div>

                        <!-- Search Engine Optimize -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>{{ __('Search Engine Optimize') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label for="seo_meta[seo_title]" class="form-label">{{ __('SEO Title') }}</label>
                                    <input type="text" name="seo_meta[seo_title]" id="seo_title" class="form-control" 
                                           value="{{ old('seo_meta.seo_title') }}" maxlength="70"
                                           placeholder="{{ __('SEO title for search engines') }}">
                                    <small class="form-text text-muted">{{ __('Recommended: 50-60 characters') }}</small>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="seo_meta[seo_description]" class="form-label">{{ __('SEO Description') }}</label>
                                    <textarea name="seo_meta[seo_description]" id="seo_description" class="form-control" rows="3" 
                                              maxlength="160" placeholder="{{ __('SEO description for search engines') }}">{{ old('seo_meta.seo_description') }}</textarea>
                                    <small class="form-text text-muted">{{ __('Recommended: 150-160 characters') }}</small>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="seo_meta[index]" class="form-label">{{ __('Search Engine Index') }}</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="seo_meta[index]" id="index_yes" value="index" {{ old('seo_meta.index', 'index') === 'index' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="index_yes">
                                            {{ __('Index') }}
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="seo_meta[index]" id="index_no" value="noindex" {{ old('seo_meta.index') === 'noindex' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="index_no">
                                            {{ __('No Index') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="row">
                    <div class="col-md-6">
                        <!-- Services -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>{{ __('Services & Activities') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label for="included_services" class="form-label">{{ __('What\'s Included') }}</label>
                                    <textarea name="included_services" id="included_services" class="form-control" rows="4" 
                                              placeholder="{{ __('e.g. Transportation, Guide, Lunch, Entry fees') }}">{{ old('included_services') }}</textarea>
                                    <small class="form-text text-muted">{{ __('Separate items with commas or new lines') }}</small>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="excluded_services" class="form-label">{{ __('What\'s Excluded') }}</label>
                                    <textarea name="excluded_services" id="excluded_services" class="form-control" rows="4" 
                                              placeholder="{{ __('e.g. Personal expenses, Tips, Travel insurance') }}">{{ old('excluded_services') }}</textarea>
                                    <small class="form-text text-muted">{{ __('Separate items with commas or new lines') }}</small>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="activities" class="form-label">{{ __('Activities') }}</label>
                                    <textarea name="activities" id="activities" class="form-control" rows="3" 
                                              placeholder="{{ __('e.g. Snorkeling, Camel ride, Quad bike') }}">{{ old('activities') }}</textarea>
                                    <small class="form-text text-muted">{{ __('Separate activities with commas') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Tour Highlights -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>{{ __('Tour Highlights') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label for="tour_highlights" class="form-label">{{ __('Tour Highlights') }}</label>
                                    <textarea name="tour_highlights" id="tour_highlights" class="form-control" rows="6" 
                                              placeholder="{{ __('e.g. Best sunset views, Authentic local experience') }}">{{ old('tour_highlights') }}</textarea>
                                    <small class="form-text text-muted">{{ __('Separate highlights with commas') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Advanced Tour Sections -->
                <div class="row">
                    <div class="col-12">
                        <!-- Tour FAQs -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>{{ __('Tour FAQs') }}</h5>
                                <small class="text-muted">{{ __('Add frequently asked questions about this tour') }}</small>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <i class="ti ti-info-circle"></i> {{ __('Please save the tour first to add FAQ details.') }}
                                </div>
                            </div>
                        </div>

                        <!-- Tour Places -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>{{ __('Tour Places') }}</h5>
                                <small class="text-muted">{{ __('Add places that will be visited during this tour') }}</small>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <i class="ti ti-info-circle"></i> {{ __('Please save the tour first to add place details.') }}
                                </div>
                            </div>
                        </div>

                        <!-- Tour Schedules -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>{{ __('Tour Schedule') }}</h5>
                                <small class="text-muted">{{ __('Add detailed day-by-day schedule for this tour') }}</small>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <i class="ti ti-info-circle"></i> {{ __('Please save the tour first to add schedule details.') }}
                                </div>
                            </div>
                        </div>

                        <!-- Tour Time Slots -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>{{ __('Tour Time Slots') }}</h5>
                                <small class="text-muted">{{ __('Add available time slots for booking this tour') }}</small>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <i class="ti ti-info-circle"></i> {{ __('Please save the tour first to add time slot details.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="text-end">
                            <button type="reset" class="btn btn-secondary me-2">{{ __('Reset') }}</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy"></i> {{ __('Create Tour') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('footer')
<script>
    $(document).ready(function() {
        // Initialize CKEditor for description and content fields
        if (typeof window.editorManagement !== 'undefined') {
            window.editorManagement.initCkEditor('description');
            window.editorManagement.initCkEditor('content');
        }
    });
</script>
@endpush

