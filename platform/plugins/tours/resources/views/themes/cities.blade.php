@php
    Theme::set('pageTitle', __('Explore All Cities'));
    Theme::set('pageName', __('All Cities'));
@endphp

@section('header')
    {!! Theme::partial('header') !!}
@endsection

<link rel="stylesheet" href="{{ asset('vendor/core/plugins/tours/css/cities-slider.css') }}">

<style>
/* Cities Page Custom Styles - Clean & Minimal */
.cities-page-hero {
    background: #f8f9fa;
    padding: 60px 0 40px;
    text-align: center;
    margin-bottom: 40px;
    border-bottom: 1px solid #e9ecef;
}

.cities-page-hero h1 {
    font-size: 2.5rem;
    font-weight: 600;
    margin-bottom: 10px;
    color: #2c3e50;
}

.cities-page-hero p {
    font-size: 1.1rem;
    color: #6c757d;
    margin: 0;
}

.cities-grid-section {
    padding: 40px 0 80px;
}

.cities-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 25px;
    margin-top: 30px;
}

/* City Card Styles - Clean & Minimal */
.city-card-grid {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    height: 280px;
    display: block;
    text-decoration: none;
    background: #fff;
}

.city-card-grid:hover {
    transform: translateY(-4px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.12);
}

.city-card-grid .city-card-image {
    width: 100%;
    height: 100%;
    position: relative;
    overflow: hidden;
}

.city-card-grid .city-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.city-card-grid:hover .city-card-image img {
    transform: scale(1.05);
}

.city-card-grid .city-card-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.3) 60%, transparent 100%);
    padding: 25px 20px 20px;
    color: white;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
}

.city-card-grid .city-card-name {
    font-size: 1.4rem;
    font-weight: 600;
    margin: 0 0 6px 0;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
}

.city-card-grid .city-card-tours {
    font-size: 0.9rem;
    opacity: 0.9;
    display: flex;
    align-items: center;
    gap: 5px;
}

.city-card-grid .city-card-tours i {
    font-size: 0.95rem;
}

/* Stats Section - Minimal */
.cities-stats {
    text-align: center;
    margin-bottom: 35px;
}

.cities-stats h3 {
    font-size: 1.1rem;
    color: #6c757d;
    margin-bottom: 8px;
    font-weight: 400;
}

.cities-stats .stats-number {
    font-size: 2.5rem;
    font-weight: 600;
    color: #3BB77E;
    margin: 10px 0;
}

/* Back Button */
.back-to-tours {
    text-align: center;
    margin-top: 50px;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .cities-grid {
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
    }
}

@media (max-width: 768px) {
    .cities-page-hero {
        padding: 40px 0 30px;
    }
    
    .cities-page-hero h1 {
        font-size: 2rem;
    }
    
    .cities-page-hero p {
        font-size: 1rem;
    }
    
    .cities-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
    }
    
    .city-card-grid {
        height: 260px;
    }
    
    .cities-stats .stats-number {
        font-size: 2rem;
    }
}

@media (max-width: 576px) {
    .cities-page-hero {
        padding: 30px 20px 20px;
    }
    
    .cities-page-hero h1 {
        font-size: 1.75rem;
    }
    
    .cities-grid {
        grid-template-columns: repeat(auto-fill, minmax(100%, 1fr));
        gap: 20px;
    }
    
    .city-card-grid {
        height: 240px;
    }
    
    .city-card-grid .city-card-name {
        font-size: 1.25rem;
    }
}

/* Empty State - Minimal */
.empty-cities-state {
    text-align: center;
    padding: 80px 20px;
    color: #6c757d;
}

.empty-cities-state i {
    font-size: 4rem;
    margin-bottom: 20px;
    opacity: 0.2;
    color: #dee2e6;
}

.empty-cities-state h3 {
    font-size: 1.4rem;
    margin-bottom: 10px;
    color: #495057;
    font-weight: 500;
}

.empty-cities-state p {
    color: #6c757d;
    font-size: 1rem;
}
</style>

<!-- Hero Section -->
<div class="cities-page-hero">
    <div class="container">
        <h1>{{ __('Explore All Cities') }}</h1>
        <p>{{ __('Discover amazing destinations and book your perfect tour') }}</p>
    </div>
</div>

<!-- Cities Grid Section -->
<div class="cities-grid-section">
    <div class="container">
        @if($cities->isNotEmpty())
            <!-- Stats -->
            <div class="cities-stats">
                <h3>{{ __('We have tours in') }}</h3>
                <div class="stats-number">{{ $cities->count() }}</div>
                <h3>{{ __('amazing destinations') }}</h3>
            </div>
            
            <!-- Cities Grid -->
            <div class="cities-grid">
                @foreach($cities as $city)
                    <a href="{{ route('public.tours.index', ['city' => $city->id]) }}" class="city-card-grid">
                        <div class="city-card-image">
                            <img src="{{ RvMedia::getImageUrl($city->image, 'medium', false, RvMedia::getDefaultImage()) }}" alt="{{ $city->name }}">
                            <div class="city-card-overlay">
                                <h3 class="city-card-name">{{ $city->name }}</h3>
                                <div class="city-card-tours">
                                    <i class="fi-rs-layout"></i>
                                    {{ $city->tours_count }} {{ $city->tours_count == 1 ? __('Tour') : __('Tours') }}
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            
            <!-- Back Button -->
            <div class="back-to-tours">
                <a href="{{ route('public.tours.index') }}" class="btn btn-outline-primary btn-lg">
                    <i class="fi-rs-arrow-left me-2"></i>
                    {{ __('Back to Tours') }}
                </a>
            </div>
        @else
            <!-- Empty State -->
            <div class="empty-cities-state">
                <i class="fi-rs-marker"></i>
                <h3>{{ __('No cities available') }}</h3>
                <p>{{ __('Check back later for new destinations!') }}</p>
                <a href="{{ route('public.tours.index') }}" class="btn btn-primary mt-3">
                    {{ __('Browse Tours') }}
                </a>
            </div>
        @endif
    </div>
</div>
