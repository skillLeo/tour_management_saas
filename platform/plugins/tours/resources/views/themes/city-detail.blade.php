@php
    Theme::set('pageTitle', $city->name . ' - ' . __('Tours'));
    Theme::set('pageName', $city->name);
@endphp

@section('header')
    {!! Theme::partial('header') !!}
@endsection

<style>
/* City Detail Page Styles */
.city-hero-section {
    position: relative;
    height: 400px;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    margin-bottom: 50px;
}

.city-hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.3) 100%);
    display: flex;
    align-items: flex-end;
    padding: 50px 0;
}

.city-hero-content {
    color: white;
}

.city-hero-content h1 {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 15px;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.city-hero-content p {
    font-size: 1.2rem;
    opacity: 0.95;
    margin-bottom: 20px;
}

.city-stats {
    display: flex;
    gap: 30px;
    flex-wrap: wrap;
}

.city-stat-item {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 1.1rem;
}

.city-stat-item i {
    font-size: 1.5rem;
    color: #3BB77E;
}

.city-description {
    background: #f8f9fa;
    padding: 30px;
    border-radius: 12px;
    margin-bottom: 40px;
}

.city-description h3 {
    font-size: 1.5rem;
    margin-bottom: 15px;
    color: #2c3e50;
}

.city-description p {
    font-size: 1.05rem;
    line-height: 1.8;
    color: #6c757d;
    margin: 0;
}

.tours-section-header {
    margin-bottom: 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.tours-section-header h2 {
    font-size: 2rem;
    font-weight: 600;
    color: #2c3e50;
}

.tours-count {
    color: #6c757d;
    font-size: 1.1rem;
}

/* Tour Cards Grid */
.tours-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 30px;
    margin-bottom: 40px;
}

.tour-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    text-decoration: none;
    display: block;
}

.tour-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.12);
}

.tour-card-image {
    width: 100%;
    height: 220px;
    overflow: hidden;
    position: relative;
}

.tour-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.tour-card:hover .tour-card-image img {
    transform: scale(1.05);
}

.tour-card-body {
    padding: 20px;
}

.tour-card-title {
    font-size: 1.2rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 10px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.tour-card-price {
    font-size: 1.5rem;
    font-weight: 700;
    color: #3BB77E;
    margin-bottom: 10px;
}

.tour-card-meta {
    display: flex;
    gap: 15px;
    color: #6c757d;
    font-size: 0.9rem;
}

.tour-card-meta i {
    margin-right: 5px;
}

/* Empty State */
.no-tours-state {
    text-align: center;
    padding: 80px 20px;
    color: #6c757d;
}

.no-tours-state i {
    font-size: 4rem;
    margin-bottom: 20px;
    opacity: 0.2;
}

.no-tours-state h3 {
    font-size: 1.5rem;
    margin-bottom: 10px;
    color: #495057;
}

/* Responsive */
@media (max-width: 768px) {
    .city-hero-section {
        height: 300px;
    }
    
    .city-hero-content h1 {
        font-size: 2rem;
    }
    
    .city-hero-content p {
        font-size: 1rem;
    }
    
    .tours-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
    }
    
    .tours-section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
}
</style>

<!-- City Hero Section -->
<div class="city-hero-section" style="background-image: url('{{ RvMedia::getImageUrl($city->image, 'large', false, RvMedia::getDefaultImage()) }}');">
    <div class="city-hero-overlay">
        <div class="container">
            <div class="city-hero-content">
                <h1>{{ $city->name }}</h1>
                @if($city->description)
                    <p>{{ Str::limit($city->description, 150) }}</p>
                @endif
                <div class="city-stats">
                    <div class="city-stat-item">
                        <i class="fi-rs-layout"></i>
                        <span>{{ $tours->total() }} {{ __('Tours Available') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="container mb-80">
    @if($city->description)
        <div class="city-description">
            <h3>{{ __('About') }} {{ $city->name }}</h3>
            <p>{{ $city->description }}</p>
        </div>
    @endif
    
    <!-- Tours Section -->
    <div class="tours-section">
        <div class="tours-section-header">
            <h2>{{ __('Available Tours') }}</h2>
            <span class="tours-count">{{ $tours->total() }} {{ $tours->total() == 1 ? __('tour') : __('tours') }}</span>
        </div>
        
        @if($tours->isNotEmpty())
            <div class="tours-grid">
                @foreach($tours as $tour)
                    <a href="{{ route('public.tours.detail', $tour->slug) }}" class="tour-card">
                        <div class="tour-card-image">
                            <img src="{{ RvMedia::getImageUrl($tour->image, 'medium', false, RvMedia::getDefaultImage()) }}" alt="{{ $tour->name }}">
                        </div>
                        <div class="tour-card-body">
                            <h3 class="tour-card-title">{{ $tour->name }}</h3>
                            <div class="tour-card-price">
                                {{ format_price($tour->current_price) }}
                            </div>
                            <div class="tour-card-meta">
                                @if($tour->duration_days > 0)
                                    <span><i class="fi-rs-clock"></i>{{ $tour->duration_days }} {{ __('days') }}</span>
                                @elseif($tour->duration_hours > 0)
                                    <span><i class="fi-rs-clock"></i>{{ $tour->duration_hours }} {{ __('hours') }}</span>
                                @endif
                                @if($tour->reviews_count > 0)
                                    <span><i class="fi-rs-star"></i>{{ number_format($tour->reviews_avg_rating, 1) }} ({{ $tour->reviews_count }})</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            
            <!-- Pagination -->
            @if($tours->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {!! $tours->links() !!}
                </div>
            @endif
        @else
            <div class="no-tours-state">
                <i class="fi-rs-marker"></i>
                <h3>{{ __('No tours available yet') }}</h3>
                <p>{{ __('Check back later for new tours in') }} {{ $city->name }}!</p>
                <a href="{{ route('public.cities.index') }}" class="btn btn-primary mt-3">
                    {{ __('View All Cities') }}
                </a>
            </div>
        @endif
    </div>
</div>
