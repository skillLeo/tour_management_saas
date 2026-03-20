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
            <h3>{{ __('Edit Tour: :name', ['name' => $tour->name]) }}</h3>
            <div class="ps-page__actions">
                <a href="{{ route('marketplace.vendor.tours.index') }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left"></i> {{ __('Back to Tours') }}
                </a>
            </div>
        </div>

        <div class="ps-page__body mb-4 mt-3">
            <form action="{{ route('marketplace.vendor.tours.update', $tour->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
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
                                           value="{{ old('name', $tour->name) }}" required>
                                    @if($errors->has('name'))
                                        <div class="text-danger">{{ $errors->first('name') }}</div>
                                    @endif
                                </div>

                                <div class="form-group mb-3 permalink-field-wrapper" data-field-name="name">
                                    <label for="slug" class="form-label">{{ __('Permalink') }}:</label>
                                    <div class="permalink-wrapper">
                                        <span class="permalink-prefix">{{ url('/tours') }}/</span>
                                        <input type="text" name="slug" id="slug" class="form-control permalink-input" 
                                               value="{{ old('slug', $tour->slug) }}" placeholder="{{ __('auto-generated-from-name') }}">
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
                                              placeholder="{{ __('Brief description of the tour') }}">{{ old('description', $tour->description) }}</textarea>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="content" class="form-label">{{ __('Detailed Content') }}</label>
                                    <textarea name="content" id="content" class="form-control editor-ckeditor" rows="6" 
                                              placeholder="{{ __('Detailed description with itinerary, highlights, etc.') }}">{{ old('content', $tour->content) }}</textarea>
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
                                                <option value="shared" {{ old('tour_type', $tour->tour_type) == 'shared' ? 'selected' : '' }}>{{ __('Shared Tour') }}</option>
                                                <option value="private" {{ old('tour_type', $tour->tour_type) == 'private' ? 'selected' : '' }}>{{ __('Private Tour') }}</option>
                                                <option value="transfer" {{ old('tour_type', $tour->tour_type) == 'transfer' ? 'selected' : '' }}>{{ __('Transfer') }}</option>
                                                <option value="small_group" {{ old('tour_type', $tour->tour_type) == 'small_group' ? 'selected' : '' }}>{{ __('Small Group') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="tour_length" class="form-label">{{ __('Tour Length') }}</label>
                                            <select name="tour_length" id="tour_length" class="form-select">
                                                <option value="">{{ trans('plugins/tours::tours.form.select_tour_length') }}</option>
                                                <option value="half_day" {{ old('tour_length', $tour->tour_length) == 'half_day' ? 'selected' : '' }}>{{ __('Half Day Activities') }}</option>
                                                <option value="full_day" {{ old('tour_length', $tour->tour_length) == 'full_day' ? 'selected' : '' }}>{{ __('Full Day Activities') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="duration_days" class="form-label">{{ __('Duration (Days)') }}</label>
                                            <input type="number" name="duration_days" id="duration_days" class="form-control" 
                                                   value="{{ old('duration_days', $tour->duration_days) }}" min="0" max="365">
                                            <small class="form-text text-muted">{{ __('Leave 0 for hour-based tours') }}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="duration_hours" class="form-label">{{ __('Duration (Hours)') }}</label>
                                            <input type="number" name="duration_hours" id="duration_hours" class="form-control" 
                                                   value="{{ old('duration_hours', $tour->duration_hours ?? 0) }}" min="0" max="24">
                                            <small class="form-text text-muted">{{ __('For hourly tours') }}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="duration_nights" class="form-label">{{ __('Duration (Nights)') }}</label>
                                            <input type="number" name="duration_nights" id="duration_nights" class="form-control" 
                                                   value="{{ old('duration_nights', $tour->duration_nights) }}" min="0" max="365">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="min_people" class="form-label">{{ __('Minimum People') }} <span class="text-danger">*</span></label>
                                            <input type="number" name="min_people" id="min_people" class="form-control" 
                                                   value="{{ old('min_people', $tour->min_people) }}" min="1" max="1000" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="max_people" class="form-label">{{ __('Maximum People') }} <span class="text-danger">*</span></label>
                                            <input type="number" name="max_people" id="max_people" class="form-control" 
                                                   value="{{ old('max_people', $tour->max_people) }}" min="1" max="1000" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="price" class="form-label">{{ __('Adult Price') }} <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" name="price" id="price" class="form-control" 
                                                       value="{{ old('price', $tour->price) }}" min="0" step="0.01" required>
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
                                                       value="{{ old('children_price', $tour->children_price) }}" min="0" step="0.01">
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
                                                       value="{{ old('infants_price', $tour->infants_price ?? '') }}" min="0" step="0.01">
                                                <span class="input-group-text">{{ get_application_currency()->symbol }}</span>
                                            </div>
                                            <small class="form-text text-muted">{{ __('Price for infants (0-1 years)') }}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="sale_percentage" class="form-label">{{ __('Sale Percentage (%)') }}</label>
                                            <input type="number" name="sale_percentage" id="sale_percentage" class="form-control" 
                                                   value="{{ old('sale_percentage', $tour->sale_percentage ?? '') }}" min="0" max="100" step="0.01">
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
                                           value="{{ old('location', $tour->location) }}" placeholder="{{ __('Tour location') }}">
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="departure_location" class="form-label">{{ __('Departure Location') }}</label>
                                            <input type="text" name="departure_location" id="departure_location" class="form-control" 
                                                   value="{{ old('departure_location', $tour->departure_location) }}" 
                                                   placeholder="{{ __('Where the tour starts') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="return_location" class="form-label">{{ __('Return Location') }}</label>
                                            <input type="text" name="return_location" id="return_location" class="form-control" 
                                                   value="{{ old('return_location', $tour->return_location) }}" 
                                                   placeholder="{{ __('Where the tour ends') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="latitude" class="form-label">{{ __('Latitude') }}</label>
                                            <input type="number" name="latitude" id="latitude" class="form-control" 
                                                   value="{{ old('latitude', $tour->latitude ?? '') }}" step="any" min="-90" max="90"
                                                   placeholder="{{ __('e.g. 25.276987') }}">
                                            <small class="form-text text-muted">{{ __('Decimal degrees format') }}</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="longitude" class="form-label">{{ __('Longitude') }}</label>
                                            <input type="number" name="longitude" id="longitude" class="form-control" 
                                                   value="{{ old('longitude', $tour->longitude ?? '') }}" step="any" min="-180" max="180"
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
                                            <option value="{{ $id }}" {{ old('category_id', $tour->category_id) == $id ? 'selected' : '' }}>
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
                                            <option value="{{ $id }}" {{ old('city_id', $tour->city_id) == $id ? 'selected' : '' }}>
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
                                           value="1" {{ old('is_featured', $tour->is_featured) ? 'checked' : '' }}>
                                    <label for="is_featured" class="form-check-label">{{ __('Featured Tour') }}</label>
                                </div>

                                <div class="form-check mb-3">
                                    <input type="checkbox" name="allow_booking" id="allow_booking" class="form-check-input" 
                                           value="1" {{ old('allow_booking', $tour->allow_booking) ? 'checked' : '' }}>
                                    <label for="allow_booking" class="form-check-label">{{ __('Allow Booking') }}</label>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="booking_advance_days" class="form-label">{{ __('Booking Advance Days') }}</label>
                                    <input type="number" name="booking_advance_days" id="booking_advance_days" class="form-control" 
                                           value="{{ old('booking_advance_days', $tour->booking_advance_days ?? 1) }}" min="0" max="365">
                                    <small class="form-text text-muted">{{ __('How many days in advance can customers book') }}</small>
                                </div>
                                
                                <!-- Tour Languages -->
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
                            </div>
                        </div>

                        <!-- Featured Image -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>{{ __('Featured Image') }}</h5>
                            </div>
                            <div class="card-body">
                                @if($tour->image)
                                    <div class="mb-3">
                                        <img src="{{ RvMedia::getImageUrl($tour->image, 'thumb') }}" 
                                             alt="{{ $tour->name }}" class="img-thumbnail" style="max-width: 200px;">
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label for="image" class="form-label">{{ __('Upload New Featured Image') }}</label>
                                    <input type="file" name="image" id="image" class="form-control" accept="image/*">
                                    <small class="form-text text-muted">{{ __('Leave empty to keep current image. Recommended size: 800x600px, Max 5MB') }}</small>
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
                                @if($tour->gallery && count($tour->gallery))
                                    <div class="current-gallery mb-3">
                                        <label class="form-label">{{ __('Current Gallery') }}</label>
                                        <div class="d-flex flex-wrap" id="current-gallery-container">
                                            @foreach($tour->gallery as $index => $image)
                                                <div class="gallery-item-current position-relative me-2 mb-2" data-image="{{ $image }}">
                                                    <img src="{{ RvMedia::getImageUrl($image, 'thumb') }}" 
                                                         alt="Gallery {{ $index + 1 }}" 
                                                         class="img-thumbnail"
                                                         style="width: 100px; height: 100px; object-fit: cover;">
                                                    <button type="button" 
                                                            class="btn btn-danger btn-sm position-absolute top-0 end-0 remove-gallery-image"
                                                            style="width: 24px; height: 24px; padding: 0; border-radius: 50%;"
                                                            title="{{ __('Remove Image') }}">
                                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <line x1="18" y1="6" x2="6" y2="18"></line>
                                                            <line x1="6" y1="6" x2="18" y2="18"></line>
                                                        </svg>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                        <input type="hidden" name="removed_gallery_images" id="removed_gallery_images" value="">
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label for="gallery" class="form-label">{{ __('Add New Gallery Images') }}</label>
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
                                    <small class="form-text text-muted">{{ __('Upload multiple images. These will be added to existing gallery. Max 10 images total, 5MB each.') }}</small>
                                    <div id="gallery-preview" class="mt-2 d-flex flex-wrap"></div>
                                </div>
                                
                                <!-- Gallery container for new uploads preview -->
                                <div id="tour-gallery-container" class="tour-gallery-container"></div>
                            </div>
                        </div>

                        <!-- Search Engine Optimize -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>{{ __('Search Engine Optimize') }}</h5>
                            </div>
                            <div class="card-body">
                                @php
                                    $seoMeta = \Botble\Base\Facades\MetaBox::getMetaData($tour, 'seo_meta', true) ?: [];
                                @endphp
                                <div class="form-group mb-3">
                                    <label for="seo_meta[seo_title]" class="form-label">{{ __('SEO Title') }}</label>
                                    <input type="text" name="seo_meta[seo_title]" id="seo_title" class="form-control" 
                                           value="{{ old('seo_meta.seo_title', $seoMeta['seo_title'] ?? '') }}" maxlength="70"
                                           placeholder="{{ __('SEO title for search engines') }}">
                                    <small class="form-text text-muted">{{ __('Recommended: 50-60 characters') }}</small>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="seo_meta[seo_description]" class="form-label">{{ __('SEO Description') }}</label>
                                    <textarea name="seo_meta[seo_description]" id="seo_description" class="form-control" rows="3" 
                                              maxlength="160" placeholder="{{ __('SEO description for search engines') }}">{{ old('seo_meta.seo_description', $seoMeta['seo_description'] ?? '') }}</textarea>
                                    <small class="form-text text-muted">{{ __('Recommended: 150-160 characters') }}</small>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="seo_meta[index]" class="form-label">{{ __('Search Engine Index') }}</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="seo_meta[index]" id="index_yes" value="index" {{ old('seo_meta.index', $seoMeta['index'] ?? 'index') === 'index' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="index_yes">
                                            {{ __('Index') }}
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="seo_meta[index]" id="index_no" value="noindex" {{ old('seo_meta.index', $seoMeta['index'] ?? 'index') === 'noindex' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="index_no">
                                            {{ __('No Index') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tour Status -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>{{ __('Tour Status') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <strong>{{ __('Current Status:') }}</strong> 
                                    <span class="badge badge-{{ $tour->status->getValue() === 'published' ? 'success' : ($tour->status->getValue() === 'pending' ? 'warning' : 'secondary') }}">
                                        {{ $tour->status->label() }}
                                    </span>
                                </div>
                                @if($tour->status->getValue() === 'pending')
                                    <small class="text-muted">
                                        {{ __('Your tour is pending approval. It will be visible once approved by admin.') }}
                                    </small>
                                @endif
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
                                              placeholder="{{ __('e.g. Transportation, Guide, Lunch, Entry fees') }}">{{ old('included_services', is_array($tour->included_services ?? null) ? implode(', ', $tour->included_services) : ($tour->included_services ?? '')) }}</textarea>
                                    <small class="form-text text-muted">{{ __('Separate items with commas or new lines') }}</small>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="excluded_services" class="form-label">{{ __('What\'s Excluded') }}</label>
                                    <textarea name="excluded_services" id="excluded_services" class="form-control" rows="4" 
                                              placeholder="{{ __('e.g. Personal expenses, Tips, Travel insurance') }}">{{ old('excluded_services', is_array($tour->excluded_services ?? null) ? implode(', ', $tour->excluded_services) : ($tour->excluded_services ?? '')) }}</textarea>
                                    <small class="form-text text-muted">{{ __('Separate items with commas or new lines') }}</small>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="activities" class="form-label">{{ __('Activities') }}</label>
                                    <textarea name="activities" id="activities" class="form-control" rows="3" 
                                              placeholder="{{ __('e.g. Snorkeling, Camel ride, Quad bike') }}">{{ old('activities', is_array($tour->activities ?? null) ? implode(', ', $tour->activities) : ($tour->activities ?? '')) }}</textarea>
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
                                              placeholder="{{ __('e.g. Best sunset views, Authentic local experience') }}">{{ old('tour_highlights', is_array($tour->tour_highlights ?? null) ? implode(', ', $tour->tour_highlights) : ($tour->tour_highlights ?? '')) }}</textarea>
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
                                <div id="tour-faqs-container">
                                    @if($tour->faqs && $tour->faqs->count() > 0)
                                        @foreach($tour->faqs as $index => $faq)
                                            <div class="faq-item border rounded p-3 mb-3" data-index="{{ $index }}" style="background: #f8f9fa;">
                                                <input type="hidden" name="faqs[{{ $index }}][id]" value="{{ $faq->id }}">
                                                
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">{{ __('Question') }}</label>
                                                    <textarea name="faqs[{{ $index }}][question]" class="form-control" rows="2" required placeholder="{{ __('Enter question here...') }}">{{ old("faqs.{$index}.question", $faq->question) }}</textarea>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">{{ __('Answer') }}</label>
                                                    <textarea name="faqs[{{ $index }}][answer]" class="form-control" rows="4" required placeholder="{{ __('Enter answer here...') }}">{{ old("faqs.{$index}.answer", $faq->answer) }}</textarea>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">{{ __('Order') }}</label>
                                                        <input type="number" name="faqs[{{ $index }}][order]" class="form-control" value="{{ old("faqs.{$index}.order", $faq->order) }}" min="0" placeholder="{{ __('Order') }}">
                                                    </div>
                                                    <div class="col-md-6 mb-3 d-flex align-items-end">
                                                        <button type="button" class="btn btn-danger btn-sm remove-faq w-100">
                                                            <i class="ti ti-trash"></i> {{ __('Remove') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <button type="button" class="btn btn-success btn-sm" id="add-faq">
                                    <i class="ti ti-plus"></i> {{ __('Add FAQ') }}
                                </button>
                            </div>
                        </div>

                        <!-- Tour Places -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>{{ __('Tour Places') }}</h5>
                                <small class="text-muted">{{ __('Add places that will be visited during this tour') }}</small>
                            </div>
                            <div class="card-body">
                                <div id="tour-places-container">
                                    @if($tour->places && $tour->places->count() > 0)
                                        @foreach($tour->places as $index => $place)
                                            <div class="place-item border rounded p-3 mb-3" data-index="{{ $index }}" style="background: #f0f8ff;">
                                                <input type="hidden" name="places[{{ $index }}][id]" value="{{ $place->id }}">
                                                
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">{{ __('Place Name') }}</label>
                                                    <input type="text" name="places[{{ $index }}][name]" class="form-control" value="{{ old("places.{$index}.name", $place->name) }}" required placeholder="{{ __('Enter place name...') }}">
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">{{ __('Place Image') }}</label>
                                                    @if($place->image)
                                                        <div class="mb-3">
                                                            <img src="{{ RvMedia::getImageUrl($place->image, 'thumb') }}" 
                                                                 alt="{{ $place->name }}" class="img-thumbnail" style="max-width: 200px;">
                                                        </div>
                                                    @endif
                                                    <div class="form-group">
                                                        <label for="place_image_{{ $index }}" class="form-label">{{ __('Upload New Place Image') }}</label>
                                                        <input type="file" name="places[{{ $index }}][image_file]" id="place_image_{{ $index }}" class="form-control place-image-file" accept="image/*">
                                                        <input type="hidden" name="places[{{ $index }}][image]" value="{{ old("places.{$index}.image", $place->image) }}">
                                                        <small class="form-text text-muted">{{ __('Leave empty to keep current image. Recommended size: 800x600px, Max 5MB') }}</small>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">{{ __('Order') }}</label>
                                                        <input type="number" name="places[{{ $index }}][order]" class="form-control" value="{{ old("places.{$index}.order", $place->order) }}" min="0" placeholder="{{ __('Order') }}">
                                                    </div>
                                                    <div class="col-md-6 mb-3 d-flex align-items-end">
                                                        <button type="button" class="btn btn-danger btn-sm remove-place w-100">
                                                            <i class="ti ti-trash"></i> {{ __('Remove') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <button type="button" class="btn btn-success btn-sm" id="add-place">
                                    <i class="ti ti-plus"></i> {{ __('Add Place') }}
                                </button>
                            </div>
                        </div>

                        <!-- Tour Schedules -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>{{ __('Tour Schedule') }}</h5>
                                <small class="text-muted">{{ __('Add detailed day-by-day schedule for this tour') }}</small>
                            </div>
                            <div class="card-body">
                                <div id="tour-schedules-container">
                                    @if($tour->schedules && $tour->schedules->count() > 0)
                                        @foreach($tour->schedules as $index => $schedule)
                                            <div class="schedule-item border rounded p-3 mb-3" data-index="{{ $index }}" style="background: #f0fff0;">
                                                <input type="hidden" name="schedules[{{ $index }}][id]" value="{{ $schedule->id }}">
                                                
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">{{ __('Day') }}</label>
                                                        <input type="number" name="schedules[{{ $index }}][day]" class="form-control" value="{{ old("schedules.{$index}.day", $schedule->day) }}" required min="1" placeholder="{{ __('Day number') }}">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">{{ __('Time') }}</label>
                                                        <input type="text" name="schedules[{{ $index }}][time]" class="form-control" value="{{ old("schedules.{$index}.time", $schedule->time) }}" placeholder="{{ __('e.g. 09:00 AM') }}">
                                                    </div>
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">{{ __('Title') }}</label>
                                                    <input type="text" name="schedules[{{ $index }}][title]" class="form-control" value="{{ old("schedules.{$index}.title", $schedule->title) }}" required placeholder="{{ __('Enter schedule title...') }}">
                                                </div>
                                                
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">{{ __('Description') }}</label>
                                                    <textarea name="schedules[{{ $index }}][description]" class="form-control" rows="3" placeholder="{{ __('Enter schedule description...') }}">{{ old("schedules.{$index}.description", $schedule->description) }}</textarea>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label class="form-label fw-bold">{{ __('Order') }}</label>
                                                        <input type="number" name="schedules[{{ $index }}][order]" class="form-control" value="{{ old("schedules.{$index}.order", $schedule->order) }}" min="0" placeholder="{{ __('Order') }}">
                                                    </div>
                                                    <div class="col-md-6 mb-3 d-flex align-items-end">
                                                        <button type="button" class="btn btn-danger btn-sm remove-schedule w-100">
                                                            <i class="ti ti-trash"></i> {{ __('Remove') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <button type="button" class="btn btn-success btn-sm" id="add-schedule">
                                    <i class="ti ti-plus"></i> {{ __('Add Schedule') }}
                                </button>
                            </div>
                        </div>

                        <!-- Tour Time Slots -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>{{ __('Tour Time Slots') }}</h5>
                                <small class="text-muted">{{ __('Add available time slots for booking this tour') }}</small>
                            </div>
                            <div class="card-body">
                                <div id="tour-time-slots-container">
                                    @if($tour->timeSlots && $tour->timeSlots->count() > 0)
                                        @foreach($tour->timeSlots as $index => $slot)
                                            <div class="time-slot-item border rounded p-3 mb-3" data-index="{{ $index }}" style="background: #f0f8ff;">
                                                <input type="hidden" name="time_slots[{{ $index }}][id]" value="{{ $slot->id }}">
                                                
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">{{ __('Start Time') }}</label>
                                                        <input type="time" name="time_slots[{{ $index }}][start_time]" class="form-control" value="{{ old("time_slots.{$index}.start_time", $slot->start_time->format('H:i')) }}" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">{{ __('Order') }}</label>
                                                        <input type="number" name="time_slots[{{ $index }}][order]" class="form-control" value="{{ old("time_slots.{$index}.order", $slot->order) }}" min="0" placeholder="{{ __('Order') }}">
                                                    </div>
                                                </div>
                                                
                                                <div class="row mb-3">
                                                    <div class="col-12">
                                                        <label class="form-label fw-bold">{{ __('Unavailable Days') }}</label>
                                                        <div class="d-flex flex-wrap">
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
                                                            @foreach($days as $dayValue => $dayLabel)
                                                                <div class="form-check form-check-inline mr-3">
                                                                    <input class="form-check-input" 
                                                                           type="checkbox" 
                                                                           name="time_slots[{{ $index }}][restricted_days][]"
                                                                           id="day-{{ $dayValue }}-{{ $index }}" 
                                                                           value="{{ $dayValue }}"
                                                                           {{ in_array($dayValue, $slot->restricted_days ?? []) ? 'checked' : '' }}>
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
                                                                    $slot->restricted_days ?? []
                                                                );
                                                                $availableDaysLabels = array_map('ucfirst', $availableDays);
                                                            @endphp
                                                            {{ __('Available days: :days', ['days' => implode(', ', $availableDaysLabels)]) }}
                                                        </small>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-12 d-flex justify-content-end">
                                                        <button type="button" class="btn btn-danger btn-sm remove-time-slot">
                                                            <i class="ti ti-trash"></i> {{ __('Remove') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <button type="button" class="btn btn-success btn-sm" id="add-time-slot">
                                    <i class="ti ti-plus"></i> {{ __('Add Time Slot') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const addButton = document.getElementById('add-time-slot');
                    if (!addButton) return;
                    
                    // Remove any existing event listeners by cloning
                    const newAddButton = addButton.cloneNode(true);
                    addButton.parentNode.replaceChild(newAddButton, addButton);
                    
                    // Add time slot functionality
                    newAddButton.addEventListener('click', function() {
                        const days = {
                            'sunday': 'Sunday', 
                            'monday': 'Monday', 
                            'tuesday': 'Tuesday', 
                            'wednesday': 'Wednesday', 
                            'thursday': 'Thursday', 
                            'friday': 'Friday', 
                            'saturday': 'Saturday'
                        };
                        
                        const index = document.querySelectorAll('.time-slot-item').length;
                        const template = `
                            <div class="time-slot-item border rounded p-3 mb-3" data-index="${index}" style="background: #f0f8ff;">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">{{ __('Start Time') }}</label>
                                        <input type="time" name="time_slots[${index}][start_time]" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">{{ __('Order') }}</label>
                                        <input type="number" name="time_slots[${index}][order]" class="form-control" value="${index}" min="0" placeholder="{{ __('Order') }}">
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label class="form-label fw-bold">{{ __('Unavailable Days') }}</label>
                                        <div class="d-flex flex-wrap">
                                            ${Object.entries(days).map(([dayValue, dayLabel]) => `
                                                <div class="form-check form-check-inline mr-3">
                                                    <input class="form-check-input" 
                                                           type="checkbox" 
                                                           name="time_slots[${index}][restricted_days][]"
                                                           id="day-${dayValue}-${index}" 
                                                           value="${dayValue}">
                                                    <label class="form-check-label" for="day-${dayValue}-${index}">
                                                        ${dayLabel}
                                                    </label>
                                                </div>
                                            `).join('')}
                                        </div>
                                        <small class="form-text text-muted">
                                            {{ __('Select days when this time slot should NOT be available') }}
                                        </small>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="button" class="btn btn-danger btn-sm remove-time-slot">
                                            <i class="ti ti-trash"></i> {{ __('Remove') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;
                        
                        const container = document.getElementById('tour-time-slots-container');
                        container.insertAdjacentHTML('beforeend', template);
                    });
                    
                    // Remove time slot functionality
                    document.addEventListener('click', function(e) {
                        if (e.target.classList.contains('remove-time-slot') || e.target.closest('.remove-time-slot')) {
                            const timeSlotItem = e.target.closest('.time-slot-item');
                            if (timeSlotItem) {
                                timeSlotItem.remove();
                            }
                        }
                    });
                });
                </script>

                <div class="row">
                    <div class="col-12">
                        <div class="text-end">
                            <a href="{{ route('marketplace.vendor.tours.index') }}" class="btn btn-secondary me-2">{{ __('Cancel') }}</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy"></i> {{ __('Update Tour') }}
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
        // Setup image preview for existing places
        document.querySelectorAll('input.place-image-file').forEach(input => {
            input.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file && window.tourSectionsManager && window.tourSectionsManager.validatePlaceImage(file)) {
                    const placeItem = e.target.closest('.place-item');
                    window.tourSectionsManager.previewPlaceImage(file, placeItem);
                }
            });
        });
    });
</script>

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

