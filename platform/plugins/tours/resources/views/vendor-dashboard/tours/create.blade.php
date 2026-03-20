@extends(MarketplaceHelper::viewPath('vendor-dashboard.layouts.master'))

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

        <div class="ps-page__body">
            <form action="{{ route('marketplace.vendor.tours.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row">
                    <div class="col-md-8">
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

                                <div class="form-group mb-3">
                                    <label for="description" class="form-label">{{ __('Description') }}</label>
                                    <textarea name="description" id="description" class="form-control" rows="4" 
                                              placeholder="{{ __('Brief description of the tour') }}">{{ old('description') }}</textarea>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="content" class="form-label">{{ __('Detailed Content') }}</label>
                                    <textarea name="content" id="content" class="form-control" rows="6" 
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
                                @include('plugins/tours::vendor.forms.tour-details', ['tour' => null, 'currencies' => []])
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>{{ __('Location Information') }}</h5>
                            </div>
                            <div class="card-body">
                                @include('plugins/tours::vendor.forms.tour-location', ['tour' => null])
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
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
                            </div>
                        </div>

                        <!-- Featured Image -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>{{ __('Featured Image') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="image" class="form-label">{{ __('Upload Image') }}</label>
                                    <input type="file" name="image" id="image" class="form-control" accept="image/*">
                                    <small class="form-text text-muted">{{ __('Recommended size: 800x600px') }}</small>
                                </div>
                            </div>
                        </div>

                        <!-- SEO Settings -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5>{{ __('SEO Settings') }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group mb-3">
                                    <label for="meta_title" class="form-label">{{ __('Meta Title') }}</label>
                                    <input type="text" name="meta_title" id="meta_title" class="form-control" 
                                           value="{{ old('meta_title') }}" maxlength="255">
                                </div>

                                <div class="form-group mb-3">
                                    <label for="meta_description" class="form-label">{{ __('Meta Description') }}</label>
                                    <textarea name="meta_description" id="meta_description" class="form-control" rows="3" 
                                              maxlength="500">{{ old('meta_description') }}</textarea>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="meta_keywords" class="form-label">{{ __('Meta Keywords') }}</label>
                                    <input type="text" name="meta_keywords" id="meta_keywords" class="form-control" 
                                           value="{{ old('meta_keywords') }}" placeholder="{{ __('Comma separated keywords') }}">
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

@push('scripts')
<script src="{{ asset('vendor/core/plugins/tours/js/vendor-tour-form.js') }}"></script>
@endpush

@push('styles')
<link rel="stylesheet" href="{{ asset('vendor/core/plugins/tours/css/vendor-tour-form.css') }}">
@endpush
