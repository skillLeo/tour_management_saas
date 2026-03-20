@php
    Theme::set('pageTitle', $tour->name);
    Theme::set('pageName', $tour->name);
    $fixedPrice = $tour->price;
@endphp

@section('header')
    {!! Theme::partial('header', ['withoutSearch' => true]) !!}
@endsection
<link rel="stylesheet" href="{{ asset('vendor/core/plugins/tours/assets/css/tour-style.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/core/plugins/tours/css/tour-details-enhanced.css') }}">
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof jQuery === 'undefined') {
            console.error('jQuery is not loaded! Loading jQuery dynamically...');
            var jqScript = document.createElement('script');
            jqScript.src = 'https://code.jquery.com/jquery-3.6.0.min.js';
            jqScript.onload = function() {
                console.log('jQuery loaded dynamically');
                loadTourScripts();
            };
            document.head.appendChild(jqScript);
        } else {
            loadTourScripts();
        }
        
        function loadTourScripts() {
            var tourScript = document.createElement('script');
            tourScript.src = '{{ asset('vendor/core/plugins/tours/assets/js/tour.js') }}';
            document.head.appendChild(tourScript);
            
            tourScript.onload = function() {
                var enhancedScript = document.createElement('script');
                enhancedScript.src = '{{ asset('vendor/core/plugins/tours/js/tour-details-enhanced.js') }}';
                document.head.appendChild(enhancedScript);
            };
        }
    });
</script>

<!-- Shimmer Loading Effect Container -->
<div class="shimmer-container">
    <div class="shimmer shimmer-image mb-4"></div>
    <div class="shimmer shimmer-text-lg"></div>
    <div class="shimmer shimmer-text-md"></div>
    <div class="row mt-4">
        <div class="col-md-8">
            <div class="shimmer shimmer-box"></div>
            <div class="shimmer shimmer-line"></div>
            <div class="shimmer shimmer-line"></div>
            <div class="shimmer shimmer-line"></div>
        </div>
        <div class="col-md-4">
            <div class="shimmer shimmer-box"></div>
        </div>
    </div>
</div>

<div class="container mb-30">
    <div class="row">
        <div class="col-xl-10 col-lg-12 m-auto">
            <div class="product-detail tour-detail-container">
                <h1 class="title-detail mb-3 tour-detail-section">{{ $tour->name }}</h1>
                
                <div class="d-flex align-items-center mb-3 tour-detail-section" style="animation-delay: 0.1s;">
                    @php
                        $averageRating = $tour->average_rating;
                        $reviewsCount = $tour->reviews_count;
                        $fullStars = floor($averageRating);
                        $halfStar = ($averageRating - $fullStars) >= 0.5;
                        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                    @endphp
                    <div class="rating-stars me-2">
                        <span class="star-icon">
                            @for($i = 0; $i < $fullStars; $i++)
                                <span style="color: #ffc107;">★</span>
                            @endfor
                            @if($halfStar)
                                <span style="color: #ffc107;">☆</span>
                            @endif
                            @for($i = 0; $i < $emptyStars; $i++)
                                <span style="color: #e0e0e0;">☆</span>
                            @endfor
                        </span>
                        @if($averageRating > 0)
                            <span class="rating-number ms-1" style="font-size: 14px; color: #666;">{{ number_format($averageRating, 1) }}</span>
                        @endif
                    </div>
                    <span>{{ $reviewsCount }} {{ $reviewsCount == 1 ? __('Review') : __('Reviews') }}</span>
                    <span class="mx-2">|</span>
                    @if($tour->city)
                        <span><i class="fi-rs-building me-1"></i> {{ $tour->city->name }}</span>
                        <span class="mx-2">|</span>
                    @endif
                    <span><i class="fi-rs-marker me-1"></i> {{ $tour->location ?? __('Location not specified') }}</span>
                </div>
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="tour-gallery mb-4 tour-detail-section" style="animation-delay: 0.2s;">
                            <div class="row">
                                <!-- الصورة الرئيسية على اليسار في الكمبيوتر وفوق في الموبايل -->
                                <div class="col-md-8 order-md-1 mb-3 mb-md-0 animate__animated animate__fadeIn">
                                    <div class="main-image-container position-relative">
                                    <div class="main-image">
                                        <a href="#" class="gallery-trigger" data-src="{{ RvMedia::getImageUrl($tour->image, 'large', false, RvMedia::getDefaultImage()) }}" data-fancybox="tour-gallery" data-caption="{{ $tour->name }}">
                                                <img id="main-tour-image" src="{{ RvMedia::getImageUrl($tour->image, 'large', false, RvMedia::getDefaultImage()) }}" alt="{{ $tour->name }}" class="img-fluid rounded w-100" style="height: 400px; object-fit: cover;" />
                                            <div class="image-overlay">
                                                <i class="fi-rs-search-plus"></i>
                                            </div>
                                        </a>
                                        </div>
                                        
                                        @php
                                            $galleryCount = ($tour->gallery && is_array($tour->gallery)) ? count(array_filter($tour->gallery, function($img) { return !empty($img); })) : 0;
                                        @endphp
                                        @if($galleryCount > 0)
                                        <!-- أزرار التنقل بين الصور -->
                                        <button type="button" class="btn btn-light btn-sm image-nav-btn image-nav-prev js-prev-image" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); z-index: 10; opacity: 0.8; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fi-rs-angle-left"></i>
                                        </button>
                                        <button type="button" class="btn btn-light btn-sm image-nav-btn image-nav-next js-next-image" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); z-index: 10; opacity: 0.8; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fi-rs-angle-right"></i>
                                        </button>
                                        
                                        <!-- مؤشر الصورة الحالية -->
                                        <div class="image-counter" id="image-counter">
                                            <span id="current-image-number">1</span> / <span id="total-images-number">{{ 1 + $galleryCount }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- الصور الأخرى على اليمين في الكمبيوتر وتحت في الموبايل -->
                                <div class="col-md-4 order-md-2">
                                    @if($tour->gallery && is_array($tour->gallery) && count($tour->gallery) > 0)
                                        <div class="gallery-grid">
                                            @php
                                                $validImages = array_filter($tour->gallery, function($img) {
                                                    return !empty($img);
                                                });
                                                $imageCount = count($validImages);
                                            @endphp
                                            
                                            <div class="row g-2">
                                                @foreach($validImages as $key => $image)
                                                    @if($key < 3)
                                                        <div class="col-4 col-md-12 mb-2 animate__fadeIn" style="animation-delay: {{ ($key + 1) * 0.2 }}s">
                                                            <a href="#" class="gallery-trigger gallery-item thumbnail-image js-set-main-image" data-src="{{ RvMedia::getImageUrl($image, 'large') }}" data-fancybox="tour-gallery" data-caption="{{ $tour->name }} - Image {{ $key + 1 }}" data-image-index="{{ $key + 1 }}" data-index="{{ $key + 1 }}">
                                                                <img src="{{ RvMedia::getImageUrl($image, 'thumb') }}" alt="{{ $tour->name }} - Image {{ $key + 1 }}" class="img-fluid rounded w-100" style="height: 120px; object-fit: cover;" />
                                                                <div class="image-overlay">
                                                                    <i class="fi-rs-search-plus"></i>
                                                                </div>
                                                            </a>
                                                        </div>
                                                    @elseif($key == 3)
                                                        <div class="col-4 col-md-12 mb-2 animate__fadeIn" style="animation-delay: {{ ($key + 1) * 0.2 }}s">
                                                            <a href="#" class="gallery-trigger gallery-item position-relative" data-src="{{ RvMedia::getImageUrl($image, 'large') }}" data-fancybox="tour-gallery" data-caption="{{ $tour->name }} - Image {{ $key + 1 }}">
                                                                <img src="{{ RvMedia::getImageUrl($image, 'thumb') }}" alt="{{ $tour->name }} - Image {{ $key + 1 }}" class="img-fluid rounded w-100" style="height: 120px; object-fit: cover;" />
                                                                @if($imageCount > 4)
                                                                    <div class="more-images position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-50 rounded">
                                                                        <span class="text-white fw-bold">+{{ $imageCount - 4 }}</span>
                                                                    </div>
                                                                @endif
                                                                <div class="image-overlay">
                                                                    <i class="fi-rs-search-plus"></i>
                                                                </div>
                                                            </a>
                                                        </div>
                                                    @else
                                                        <a href="#" class="gallery-trigger d-none" data-src="{{ RvMedia::getImageUrl($image, 'large') }}" data-fancybox="tour-gallery" data-caption="{{ $tour->name }} - Image {{ $key + 1 }}">
                                                            <img src="{{ RvMedia::getImageUrl($image, 'thumb') }}" alt="{{ $tour->name }} - Image {{ $key + 1 }}" />
                                                        </a>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="tour-details mt-4 tour-detail-section" style="animation-delay: 0.3s;">
                            <div class="tour-info d-flex flex-wrap mb-4">
                                <div class="tour-info-item me-4">
                                    <i class="fi-rs-clock me-1"></i>
                                    @if(!empty($tour->duration_hours) && $tour->duration_hours > 0)
                                        {{ $tour->duration_hours }} {{ __('plugins/tours::tours.hours') }}
                                    @else
                                        {{ $tour->duration_days }} {{ __('days') }} {{ $tour->duration_nights }} {{ __('nights') }}
                                    @endif
                                </div>
                                <div class="tour-info-item me-4">
                                    <i class="fi-rs-users me-1"></i> {{ __('Max') }}: {{ $tour->max_people }} {{ __('people') }}
                                </div>
                                                @if($tour->start_time)
                    <div class="tour-info-item me-4">
                        <i class="fi-rs-time-past me-1"></i> {{ __('Start Time') }}: {{ $tour->start_time->format('g:i A') }}
                    </div>
                @endif
                @if($tour->departure_time)
                    <div class="tour-info-item me-4">
                        <i class="fi-rs-arrow-right me-1"></i> {{ __('Departure') }}: {{ $tour->departure_time->format('g:i A') }}
                    </div>
                @endif
                                @if($tour->is_featured)
                                    <div class="tour-info-item featured-badge">
                                        {{ __('Featured') }}
                                    </div>
                                @endif
                            </div>
                            
                  
{{-- REPLACE WITH: --}}
<div class="tour-overview mb-4 tour-detail-section" style="animation-delay: 0.4s;">
    <h3>{{ __('Overview') }}</h3>
    <div>{!! $tour->description !!}</div>
    <div class="tour-content mt-3">
        {!! $tour->content !!}
    </div>
</div>
                            
                            <!-- Places You'll See Section -->
                            @if($tour->places && $tour->places->count() > 0)
                            <div class="tour-places-container mb-4 tour-detail-section" style="animation-delay: 0.5s;">
                                <div class="places-main-box" style="background: #fff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); overflow: hidden; border: 1px solid #e9ecef;">
                                    <div class="places-header" style="background: #3BB77E !important; color: white !important; padding: 15px 25px; text-align: left;">
                                        <h3 class="places-title" style="color: white !important; margin: 0; font-size: 20px; font-weight: 600;">{{ __('Places You\'ll See') }} :</h3>
                                    </div>
                                    <div class="places-content" style="padding: 20px;">
                                        <div class="row">
                                            @foreach($tour->places as $index => $place)
                                            <div class="col-md-4 mb-3">
                                                <div class="place-item" style="text-align: center;">
                                                    @if($place->image)
                                                        <div class="place-image mb-2">
                                                            <img src="{{ RvMedia::getImageUrl($place->image, 'medium') }}" 
                                                                 alt="{{ $place->name }}" 
                                                                 style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px;">
                                                        </div>
                                                    @endif
                                                    <div class="place-number-name">
                                                        <span class="place-number" style="font-size: 18px; font-weight: 700; color: #333; margin-right: 8px;">{{ $index + 1 }}</span>
                                                        <span class="place-name" style="font-size: 16px; font-weight: 600; color: #333;">{{ $place->name }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <!-- Schedule Details Section -->
                            @if($tour->schedules && $tour->schedules->count() > 0)
                            <div class="tour-schedule-details mb-4">
                                <div class="schedule-main-box" style="background: #fff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); overflow: hidden; border: 1px solid #e9ecef;">
                                    <div class="schedule-header" style="background: #3BB77E !important; color: white !important; padding: 15px 25px; text-align: left;">
                                        <h3 class="schedule-title" style="color: white !important; margin: 0; font-size: 20px; font-weight: 600;">{{ __('Schedule Details') }}</h3>
                                    </div>
                                    <div class="schedule-content" style="padding: 20px;">
                                        <div class="accordion" id="scheduleAccordion">
                                            @foreach($tour->schedules as $index => $schedule)
                                            <div class="accordion-item mb-3" style="border: 1px solid #e9ecef; border-radius: 8px;">
                                                <h2 class="accordion-header" id="schedule-heading-{{ $index }}">
                                                    <button class="accordion-button {{ $index == 0 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#schedule-collapse-{{ $index }}" aria-expanded="{{ $index == 0 ? 'true' : 'false' }}" aria-controls="schedule-collapse-{{ $index }}" style="background: #f8f9fa; border: none; font-weight: 600; color: #333; padding: 15px 20px;">
                                                        <span class="schedule-day-number" style="background: #3BB77E; color: white; border-radius: 50%; width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center; margin-right: 15px; font-size: 14px; font-weight: 700;">{{ $index + 1 }}</span>
                                                        {{ $schedule->title }}
                                                    </button>
                                                </h2>
                                                <div id="schedule-collapse-{{ $index }}" class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}" aria-labelledby="schedule-heading-{{ $index }}" data-bs-parent="#scheduleAccordion">
                                                    <div class="accordion-body" style="padding: 20px; background: #fff;">
                                                        <div class="schedule-description" style="line-height: 1.6; color: #555;">
                                                            {!! nl2br(e($schedule->description)) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <!-- Map Section - Moved here after Overview -->
                            @if($tour->latitude && $tour->longitude)
                            <div class="tour-map-inline mb-4">
                                <h3 class="mb-3">{{ __('Location') }}</h3>
                                
                                <div class="tour-map-container">
                                    <div class="map-info mb-3">
                                        @if($tour->location)
                                            <p class="mb-2">
                                                <i class="fi-rs-marker me-2 text-primary"></i>
                                                <strong>{{ $tour->location }}</strong>
                                            </p>
                                        @endif
                                        @if($tour->departure_location)
                                            <p class="mb-2">
                                                <i class="fi-rs-arrow-right me-2 text-success"></i>
                                                <span>{{ __('Departure from') }}: {{ $tour->departure_location }}</span>
                                            </p>
                                        @endif
                                        @if($tour->return_location)
                                            <p class="mb-0">
                                                <i class="fi-rs-arrow-left me-2 text-info"></i>
                                                <span>{{ __('Return to') }}: {{ $tour->return_location }}</span>
                                            </p>
                                        @endif
                                    </div>
                                    
                                    <div class="map-wrapper" style="height: 300px; border-radius: 12px; overflow: hidden; border: 1px solid #e9ecef;">
                                        <iframe 
                                            src="https://maps.google.com/maps?q={{ $tour->latitude }},{{ $tour->longitude }}&t=&z=14&ie=UTF8&iwloc=&output=embed"
                                            width="100%" 
                                            height="100%" 
                                            frameborder="0" 
                                            style="border:0;" 
                                            allowfullscreen="" 
                                            loading="lazy"
                                            title="Tour Location Map">
                                        </iframe>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <!-- FAQ Section - After Map -->
                            @if($tour->faqs && $tour->faqs->count() > 0)
                            <div class="tour-faq-inline mb-4 tour-detail-section" style="animation-delay: 0.6s;">
                                <h3 class="mb-3">{{ __('Frequently Asked Questions') }}</h3>
                                
                                <div class="accordion" id="tourFaqAccordion">
                                    @foreach($tour->faqs as $index => $faq)
                                    <div class="accordion-item mb-3">
                                        <h5 class="accordion-header" id="faq-heading-{{ $index }}">
                                            <button class="accordion-button {{ $index == 0 ? '' : 'collapsed' }}" 
                                                    type="button" 
                                                    data-bs-toggle="collapse" 
                                                    data-bs-target="#faq-collapse-{{ $index }}" 
                                                    aria-expanded="{{ $index == 0 ? 'true' : 'false' }}" 
                                                    aria-controls="faq-collapse-{{ $index }}">
                                                {{ $faq->question }}
                                            </button>
                                        </h5>
                                        <div id="faq-collapse-{{ $index }}" 
                                             class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}" 
                                             aria-labelledby="faq-heading-{{ $index }}" 
                                             data-bs-parent="#tourFaqAccordion">
                                            <div class="accordion-body">
                                                {!! $faq->answer !!}
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            
                            @if($tour->tour_highlights && !empty($tour->tour_highlights))
                            <div class="tour-highlights mb-4 tour-detail-section" style="animation-delay: 0.7s;">
                                <h3>{{ __('Key Highlights') }}</h3>
                                <ul class="highlights-list">
                                    @foreach($tour->tour_highlights as $highlight)
                                        <li>{{ $highlight }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            
                            @if($tour->start_time || $tour->end_time || $tour->departure_time || $tour->return_time)
                            <div class="tour-schedule mb-4 tour-detail-section" style="animation-delay: 0.8s;">
                                <h3>{{ __('Schedule & Timing') }}</h3>
                                <div class="schedule-details">

                    @if($tour->departure_location && $tour->departure_time)
                        <div class="schedule-item mb-2">
                            <strong><i class="fi-rs-arrow-right me-2"></i>{{ __('Departure') }}:</strong>
                            {{ $tour->departure_location }} {{ __('at') }} {{ $tour->departure_time->format('g:i A') }}
                        </div>
                    @endif
                    @if($tour->return_location && $tour->return_time)
                        <div class="schedule-item mb-2">
                            <strong><i class="fi-rs-arrow-left me-2"></i>{{ __('Return') }}:</strong>
                            {{ $tour->return_location }} {{ __('at') }} {{ $tour->return_time->format('g:i A') }}
                        </div>
                    @endif
                                </div>
                            </div>
                            @endif
                            
                            @if($tour->itinerary && !empty($tour->itinerary))
                            <div class="tour-itinerary mb-4">
                                <h3>{{ __('Itinerary') }}</h3>
                                <div class="itinerary-content">
                                    @foreach($tour->itinerary as $day => $activities)
                                        <div class="itinerary-day mb-3">
                                            <h4>{{ __('Day') }} {{ $day + 1 }}</h4>
                                            <p>{{ $activities }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            
                            <!-- Review Now Button -->
                            <div class="review-now-section mb-4">
                                <button type="button" class="btn btn-primary btn-lg w-100" data-bs-toggle="modal" data-bs-target="#reviewModal">
                                    <i class="fi-rs-star me-2"></i>
                                    {{ __('Review Now') }}
                                </button>
                            </div>
                            
                            <div class="social-share mt-4">
                                <div class="single-social-share clearfix mb-15">
                                    <p class="mb-15 font-sm">
                                        <i class="fi-rs-share me-2"></i>
                                        <span class="d-inline-block">Share this</span>
                                    </p>
                                    

                                    <ul class="bb-social-sharing">
                                        <li class="bb-social-sharing__item">
                                            <a href="https://www.facebook.com/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" title="Share on Facebook" style="">
                                                <svg class="icon svg-icon-ti-ti-brand-facebook" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <path d="M7 10v4h3v7h4v-7h3l1 -4h-4v-2a1 1 0 0 1 1 -1h3v-4h-3a5 5 0 0 0 -5 5v2h-3"></path>
                                                </svg>
                                                <span class="bb-social-sharing-text">Facebook</span>
                                            </a>
                                        </li>
                                        <li class="bb-social-sharing__item">
                                            <a href="https://x.com/intent/tweet?url={{ urlencode(url()->current()) }}&amp;text={{ urlencode($tour->name) }}" target="_blank" title="Share on X (Twitter)" style="">
                                                <svg class="icon svg-icon-ti-ti-brand-x" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <path d="M4 4l11.733 16h4.267l-11.733 -16z"></path>
                                                    <path d="M4 20l6.768 -6.768m2.46 -2.46l6.772 -6.772"></path>
                                                </svg>
                                                <span class="bb-social-sharing-text">X (Twitter)</span>
                                            </a>
                                        </li>
                                        <li class="bb-social-sharing__item">
                                            <a href="https://pinterest.com/pin/create/button/?url={{ urlencode(url()->current()) }}&amp;description={{ urlencode($tour->name) }}&amp;media={{ urlencode(RvMedia::getImageUrl($tour->image, 'large', false, RvMedia::getDefaultImage())) }}" target="_blank" title="Share on Pinterest" style="">
                                                <svg class="icon svg-icon-ti-ti-brand-pinterest" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <path d="M8 20l4 -9"></path>
                                                    <path d="M10.7 14c.437 1.263 1.43 2 2.55 2c2.071 0 3.75 -1.554 3.75 -4a5 5 0 1 0 -9.7 1.7"></path>
                                                    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>
                                                </svg>
                                                <span class="bb-social-sharing-text">Pinterest</span>
                                            </a>
                                        </li>
                                        <li class="bb-social-sharing__item">
                                            <a href="https://www.linkedin.com/sharing/share-offsite?url={{ urlencode(url()->current()) }}&amp;sumary={{ urlencode($tour->description) }}" target="_blank" title="Share on LinkedIn" style="">
                                                <svg class="icon svg-icon-ti-ti-brand-linkedin" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <path d="M8 11v5"></path>
                                                    <path d="M8 8v.01"></path>
                                                    <path d="M12 16v-5"></path>
                                                    <path d="M16 16v-3a2 2 0 1 0 -4 0"></path>
                                                    <path d="M3 7a4 4 0 0 1 4 -4h10a4 4 0 0 1 4 4v10a4 4 0 0 1 -4 4h-10a4 4 0 0 1 -4 -4z"></path>
                                                </svg>
                                                <span class="bb-social-sharing-text">LinkedIn</span>
                                            </a>
                                        </li>
                                        <li class="bb-social-sharing__item">
                                            <a href="https://api.whatsapp.com/send?text={{ urlencode($tour->name . ' ' . url()->current()) }}" target="_blank" title="Share on WhatsApp" style="">
                                                <svg class="icon svg-icon-ti-ti-brand-whatsapp" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <path d="M3 21l1.65 -3.8a9 9 0 1 1 3.4 2.9l-5.05 .9"></path>
                                                    <path d="M9 10a.5 .5 0 0 0 1 0v-1a.5 .5 0 0 0 -1 0v1a5 5 0 0 0 5 5h1a.5 .5 0 0 0 0 -1h-1a.5 .5 0 0 0 0 1"></path>
                                                </svg>
                                                <span class="bb-social-sharing-text">WhatsApp</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="booking-card border rounded shadow-sm p-4">
                            <div class="booking-header mb-4">
                                <h4 class="mb-2">{{ __('Make your booking') }}</h4>
                                
                                <div class="price-display mb-3">
                                    <span class="current-price">{{ format_tour_price($fixedPrice) }}</span>
                                </div>
                                
                                <div class="booking-meta d-flex flex-wrap">
                                    <div class="booking-meta-item me-3">
                                    <i class="fi-rs-clock me-1"></i>
                                    @if(!empty($tour->duration_hours) && $tour->duration_hours > 0)
                                        {{ $tour->duration_hours }} {{ __('plugins/tours::tours.hours') }}
                                    @else
                                        {{ $tour->duration_days }} {{ __('days') }}
                                    @endif
                                    </div>
                                    <div class="booking-meta-item me-3">
                                        <i class="fi-rs-users me-1"></i> {{ __('Min') }}: {{ $tour->min_people }}
                                    </div>
                                    @if($tour->start_time)
                                        <div class="booking-meta-item me-3">
                                            <i class="fi-rs-time-past me-1"></i> {{ $tour->start_time->format('g:i A') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="whats-included mb-4">
                                <h5 class="mb-3">{{ __("What's Included") }}</h5>
                                <ul class="included-list list-unstyled">
                                    @if($tour->included_services && count($tour->included_services) > 0)
                                        @foreach($tour->included_services as $service)
                                            <li class="mb-2"><i class="fi-rs-check me-2 text-success"></i> {{ $service }}</li>
                                        @endforeach
                                  
                                    @endif
                                </ul>
                            </div>
                            
                            <div class="whats-excluded mb-4">
                                <h5 class="mb-3">{{ __("What's Excluded") }}</h5>
                                <ul class="excluded-list list-unstyled">
                                    @if($tour->excluded_services && count($tour->excluded_services) > 0)
                                        @foreach($tour->excluded_services as $service)
                                            <li class="mb-2"><i class="fi-rs-cross me-2 text-danger"></i> {{ $service }}</li>
                                        @endforeach
                                 
                                    @endif
                                </ul>
                            </div>
                            
                            @if($tour->activities && count($tour->activities) > 0)
                            <div class="activities-card mb-4 tour-detail-section" style="background:#f8f9fa;border-radius:8px;border:1px solid #e9ecef;animation-delay: 0.9s;">
                                <div class="p-3 border-bottom" style="background:#fff;border-top-left-radius:8px;border-top-right-radius:8px;">
                                    <h5 class="mb-0"><i class="fi-rs-flag me-2 text-primary"></i>{{ __('Activities') }}</h5>
                                </div>
                                <div class="p-3">
                                    <ul class="list-unstyled mb-0">
                                        @foreach($tour->activities as $activity)
                                            <li class="mb-2 d-flex align-items-start">
                                                <i class="fi-rs-check me-2 text-success" style="margin-top:3px;"></i>
                                                <span>{{ $activity }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            @endif
                            
                            <div class="booking-form tour-detail-section" style="animation-delay: 1s;">
                                <form id="booking-form" 
                                      action="{{ route('public.tours.booking.store') }}" 
                                      method="POST"
                                      data-tour-slug="{{ $tour->slug }}"
                                >
                                    @csrf
                                    <input type="hidden" name="tour_id" value="{{ $tour->id }}">
                                    <input type="hidden" name="adult_price" value="{{ $tour->current_price }}">
                                    <input type="hidden" name="child_price" value="{{ $tour->current_children_price ?: $tour->current_price }}">
                                    <input type="hidden" name="infant_price" value="{{ $tour->current_infants_price }}">
                                    <input type="hidden" name="currency" value="{{ get_application_currency()->title ?? 'USD' }}">
                                    <input type="hidden" name="payment_status" value="pending">
                                    <input type="hidden" name="status" value="pending">
                                    <input type="hidden" name="total_amount" value="{{ $fixedPrice }}">
                                    <input type="hidden" name="subtotal" value="{{ $fixedPrice }}">
                                
                                    @include('plugins/tours::themes.tour-calendar')

                                    <!-- Quantity Selection -->
                                    <div class="quantity-section mt-4">
                                        <h5 class="mb-3">{{ __('plugins/tours::tours.Number of Participants') }}</h5>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('plugins/tours::tours.Adults') }} <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <button type="button" class="btn btn-outline-secondary" onclick="changeQuantity('adults', -1)">-</button>
                                                <input type="number" name="adults" id="adults-input" class="form-control text-center js-price-input" min="1" max="50" value="1" required>
                                                <button type="button" class="btn btn-outline-secondary" onclick="changeQuantity('adults', 1)">+</button>
                                            </div>
                                            <small class="text-muted">
                                                <span id="adult-price-per-person">{{ format_tour_price($tour->current_price) }}</span> {{ __('plugins/tours::tours.per person') }}
                                                <span id="adult-price-total" class="fw-bold text-primary"></span>
                                            </small>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('plugins/tours::tours.Children') }}</label>
                                            <div class="input-group">
                                                <button type="button" class="btn btn-outline-secondary" onclick="changeQuantity('children', -1)">-</button>
                                                <input type="number" name="children" id="children-input" class="form-control text-center js-price-input" min="0" max="50" value="0">
                                                <button type="button" class="btn btn-outline-secondary" onclick="changeQuantity('children', 1)">+</button>
                                            </div>
                                            <small class="text-muted">
                                                <span id="child-price-per-person">{{ format_tour_price($tour->current_children_price ?: $tour->current_price) }}</span> {{ __('plugins/tours::tours.per child') }}
                                                <span id="child-price-total" class="fw-bold text-primary"></span>
                                            </small>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('plugins/tours::tours.Infants') }}</label>
                                            <div class="input-group">
                                                <button type="button" class="btn btn-outline-secondary" onclick="changeQuantity('infants', -1)">-</button>
                                                <input type="number" name="infants" id="infants-input" class="form-control text-center js-price-input" min="0" max="50" value="0">
                                                <button type="button" class="btn btn-outline-secondary" onclick="changeQuantity('infants', 1)">+</button>
                                            </div>
                                            <small class="text-muted">
                                                <span id="infant-price-per-person">${{ number_format($tour->current_infants_price, 2) }}</span> {{ __('plugins/tours::tours.per infant') }}
                                                <span id="infant-price-total" class="fw-bold text-primary"></span>
                                            </small>
                                        </div>
                                    </div>

                                    <!-- Selected Slots Info -->
                                    <div id="selected-slots-info" class="selected-slots-info mt-3" style="display:none;">
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <span id="slots-count-text">{{ __('plugins/tours::tours.No time slots selected') }}</span>
                                        </div>
                                    </div>

                                    <!-- Total Price Display -->
                                    <div class="total-price-section mt-4 p-3" style="background:#f8f9fa;border-radius:8px;border:1px solid #e9ecef;">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0">{{ __('plugins/tours::tours.Total Price') }}:</h5>
                                            <h4 class="mb-0 text-primary" id="total-price">{{ format_tour_price($fixedPrice) }}</h4>
                                        </div>
                                        <small class="text-muted" id="price-breakdown">{{ __('plugins/tours::tours.Select time slots to calculate final price') }}</small>
                                    </div>

                                    <!-- Customer Information -->
                                    <div class="customer-info-section mt-4">
                                        <h5 class="mb-3">{{ __('plugins/tours::tours.Your Information') }}</h5>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('plugins/tours::tours.Full Name') }} <span class="text-danger">*</span></label>
                                            <input type="text" name="customer_name" class="form-control" required placeholder="{{ __('plugins/tours::tours.Enter your full name') }}">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('plugins/tours::tours.Email Address') }} <span class="text-danger">*</span></label>
                                            <input type="email" name="customer_email" class="form-control" required placeholder="{{ __('plugins/tours::tours.Enter your email') }}">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('plugins/tours::tours.Phone Number') }} <span class="text-danger">*</span></label>
                                            <input type="tel" name="customer_phone" class="form-control" required placeholder="{{ __('plugins/tours::tours.Enter your phone') }}">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('plugins/tours::tours.Spoken Language') }}</label>
                                            <input type="text" name="customer_nationality" class="form-control" placeholder="{{ __('plugins/tours::tours.Enter your spoken language') }}">
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('plugins/tours::tours.Address') }}</label>
                                            <textarea name="customer_address" class="form-control" rows="2" placeholder="{{ __('plugins/tours::tours.Enter your address') }}"></textarea>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('plugins/tours::tours.Special Requirements') }}</label>
                                            <textarea name="special_requirements" class="form-control" rows="3" placeholder="{{ __('plugins/tours::tours.Any special requirements or notes...') }}"></textarea>
                                        </div>
                                    </div>

                                    <!-- Book Now Button -->
                                    <div class="booking-submit mt-4">
                                        <button type="submit" class="btn btn-primary btn-lg w-100" disabled>
                                            <i class="fas fa-calendar-plus"></i> {{ __('plugins/tours::tours.Select Time Slot First') }}
                                        </button>
                                        <p class="text-center mt-2 mb-0">
                                            <small class="text-muted">{{ __('plugins/tours::tours.Secure booking - You will receive confirmation within 24 hours') }}</small>
                                        </p>
                                    </div>

                                </form>
                                

                            </div>
                            
                            <!-- Got a Question? Enquiry Card -->
                            <div class="enquiry-card mt-4 tour-detail-section" style="background:#f8f9fa;border-radius:8px;border:1px solid #e9ecef;animation-delay: 1.1s;">
                                <div class="p-3 border-bottom" style="background:#fff;border-top-left-radius:8px;border-top-right-radius:8px;">
                                    <h5 class="mb-1">{{ __('Got a Question?') }}</h5>
                                    <p class="text-muted mb-0" style="font-size: 14px;">{{ __('Do not hesitage to give us a call. We are an expert team and we are happy to talk to you.') }}</p>
                                </div>
                                <div class="p-3">
                                    <button type="button" class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#enquiryModal">
                                        {{ __('Get Enquiry') }}
                                    </button>
                                </div>
                            </div>
                            
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Reviews Section -->
    @if($tour->approvedReviews->count() > 0)
    <div class="row mt-5">
        <div class="col-12">
            <div class="reviews-section tour-detail-section" style="animation-delay: 1.2s;">
                <h4 class="mb-4">{{ __('Customer Reviews') }} ({{ $tour->reviews_count }})</h4>
                
                <div class="reviews-summary mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="overall-rating d-flex align-items-center">
                                <div class="rating-number me-3">
                                    <span class="display-4 fw-bold text-primary">{{ number_format($tour->average_rating, 1) }}</span>
                                </div>
                                <div>
                                    <div class="rating-stars mb-1">
                                        @php
                                            $averageRating = $tour->average_rating;
                                            $fullStars = floor($averageRating);
                                            $halfStar = ($averageRating - $fullStars) >= 0.5;
                                            $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                                        @endphp
                                        @for($i = 0; $i < $fullStars; $i++)
                                            <span style="color: #ffc107; font-size: 20px;">★</span>
                                        @endfor
                                        @if($halfStar)
                                            <span style="color: #ffc107; font-size: 20px;">☆</span>
                                        @endif
                                        @for($i = 0; $i < $emptyStars; $i++)
                                            <span style="color: #e0e0e0; font-size: 20px;">☆</span>
                                        @endfor
                                    </div>
                                    <p class="mb-0 text-muted">{{ __('Based on') }} {{ $tour->reviews_count }} {{ $tour->reviews_count == 1 ? __('review') : __('reviews') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="reviews-list">
                    @foreach($tour->approvedReviews->take(5) as $review)
                    <div class="review-item border-bottom pb-4 mb-4">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="mb-1">{{ $review->customer_name }}</h6>
                                <div class="review-rating">
                                    @php
                                        $rating = $review->rating;
                                        $fullStars = floor($rating);
                                        $halfStar = ($rating - $fullStars) >= 0.5;
                                        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                                    @endphp
                                    @for($i = 0; $i < $fullStars; $i++)
                                        <span style="color: #ffc107;">★</span>
                                    @endfor
                                    @if($halfStar)
                                        <span style="color: #ffc107;">☆</span>
                                    @endif
                                    @for($i = 0; $i < $emptyStars; $i++)
                                        <span style="color: #e0e0e0;">☆</span>
                                    @endfor
                                    <span class="ms-2 text-muted">({{ number_format($rating, 1) }})</span>
                                </div>
                            </div>
                            <small class="text-muted">{{ $review->created_at->format('M d, Y') }}</small>
                        </div>
                        @if($review->review)
                        <p class="review-text mb-0">{{ $review->review }}</p>
                        @endif
                    </div>
                    @endforeach
                    
                    @if($tour->approvedReviews->count() > 5)
                    <div class="text-center">
                        <button class="btn btn-outline-primary js-load-more-reviews">
                            {{ __('Show More Reviews') }} ({{ $tour->approvedReviews->count() - 5 }} {{ __('more') }})
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- You may like Tour Section -->
    @if($relatedTours && $relatedTours->count() > 0)
    <div class="tour-related-container mb-4">
        <div class="related-main-box" style="background: #fff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); overflow: hidden; border: 1px solid #e9ecef;">
            <div class="related-header" style="background: #3BB77E !important; color: white !important; padding: 15px 25px; text-align: left;">
                <h3 class="related-title" style="color: white !important; margin: 0; font-size: 20px; font-weight: 600;">{{ __('You may like Tour') }} :</h3>
            </div>
            <div class="related-content" style="padding: 20px;">
                <div class="row">
                    @foreach($relatedTours as $index => $relatedTour)
                    <div class="col-md-3 mb-3">
                        <div class="related-tour-item" style="text-align: center; position: relative;">
                            <div class="tour-image mb-3" style="position: relative;">
                                <img src="{{ RvMedia::getImageUrl($relatedTour->image, 'medium', false, RvMedia::getDefaultImage()) }}" 
                                     alt="{{ $relatedTour->name }}" 
                                     style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px;">
                                

                            </div>
                            <div class="tour-info">
                                @if(!empty($relatedTour->slug))
                                    <a href="{{ route('public.tours.detail', $relatedTour->slug) }}" class="tour-name" style="font-size: 16px; font-weight: 600; color: #333; text-decoration: none; display: block; margin-bottom: 8px;">{{ $relatedTour->name }}</a>
                                @else
                                    <span class="tour-name" style="font-size: 16px; font-weight: 600; color: #333; display: block; margin-bottom: 8px;">{{ $relatedTour->name }}</span>
                                @endif
                                <div class="tour-location" style="font-size: 14px; color: #666; margin-bottom: 8px;">
                                    <i class="fi-rs-marker" style="margin-right: 5px;"></i>{{ $relatedTour->location ?: __('Various Locations') }}
                                </div>
                                <div class="tour-price" style="font-size: 18px; font-weight: 700; color: #3BB77E; margin-bottom: 10px;">
                                    @if($relatedTour->has_discount)
                                        <span class="current-price">{{ format_tour_price($relatedTour->current_price) }}</span>
                                        <span class="old-price" style="font-size: 14px; color: #999; text-decoration: line-through; margin-left: 5px;">{{ format_tour_price($relatedTour->price) }}</span>
                                        <div class="discount-badge" style="background: #dc3545; color: white; font-size: 10px; padding: 2px 6px; border-radius: 10px; display: inline-block; margin-left: 5px;">{{ $relatedTour->sale_percentage }}% OFF</div>
                                    @else
                                        <span class="current-price">{{ format_tour_price($relatedTour->current_price) }}</span>
                                    @endif
                                </div>
                                @if(!empty($relatedTour->slug))
                                    <a href="{{ route('public.tours.detail', $relatedTour->slug) }}" class="explore-btn" style="background: #3BB77E; color: white; padding: 8px 20px; border-radius: 20px; text-decoration: none; font-size: 14px; font-weight: 600; display: inline-block; transition: all 0.3s ease;">
                                        {{ __('Explore') }}
                                    </a>
                                @else
                                    <span class="explore-btn" style="background: #ccc; color: white; padding: 8px 20px; border-radius: 20px; font-size: 14px; font-weight: 600; display: inline-block; cursor: not-allowed;">
                                        {{ __('Not Available') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

</div>



@push('footer')
<script src="{{ asset('vendor/core/plugins/tours/assets/js/tour.js') }}"></script>
@endpush

<!-- Review Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewModalLabel">
                    <i class="fi-rs-star me-2"></i>
                    {{ __('Write a Review') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="reviewForm" action="{{ route('public.tours.reviews.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tour_id" value="{{ $tour->id }}">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="customer_name" class="form-label">{{ __('Your Name') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="customer_email" class="form-label">{{ __('Your Email') }} <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="customer_email" name="customer_email" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="rating" class="form-label">{{ __('Rating') }} <span class="text-danger">*</span></label>
                        <div class="rating-input">
                            <div class="star-rating">
                                <input type="radio" id="star5" name="rating" value="5" />
                                <label for="star5" title="5 stars">★</label>
                                <input type="radio" id="star4" name="rating" value="4" />
                                <label for="star4" title="4 stars">★</label>
                                <input type="radio" id="star3" name="rating" value="3" />
                                <label for="star3" title="3 stars">★</label>
                                <input type="radio" id="star2" name="rating" value="2" />
                                <label for="star2" title="2 stars">★</label>
                                <input type="radio" id="star1" name="rating" value="1" />
                                <label for="star1" title="1 star">★</label>
                            </div>
                            <small class="text-muted">{{ __('Click on stars to rate') }}</small>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="review_text" class="form-label">{{ __('Your Review') }} <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="review_text" name="review_text" rows="4" placeholder="{{ __('Tell us about your experience with this tour...') }}" required maxlength="1000"></textarea>
                        <div class="text-muted small mt-1">
                            <span id="char-count">0</span>/1000 {{ __('characters') }}
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fi-rs-info me-2"></i>
                        {{ __('Your review will be published after admin approval.') }}
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="submit" form="reviewForm" class="btn btn-primary">
                    <i class="fi-rs-paper-plane me-2"></i>
                    {{ __('Submit Review') }}
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Enquiry Modal -->
<div class="modal fade" id="enquiryModal" tabindex="-1" aria-labelledby="enquiryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="enquiryModalLabel">{{ __('Got a Question?') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="enquiryForm" action="{{ route('public.tours.enquiry.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tour_id" value="{{ $tour->id }}">
                    <div class="mb-3">
                        <label class="form-label">{{ __('Customer Name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="customer_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Email') }} <span class="text-danger">*</span></label>
                        <input type="email" name="customer_email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Subject') }} <span class="text-danger">*</span></label>
                        <input type="text" name="subject" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('Message') }} <span class="text-danger">*</span></label>
                        <textarea name="message" class="form-control" rows="4" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="submit" form="enquiryForm" class="btn btn-primary">
                    <i class="fi-rs-paper-plane me-2"></i>{{ __('Get Enquiry') }}
                </button>
            </div>
        </div>
    </div>
</div>

 