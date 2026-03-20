@php
    Theme::set('pageTitle', $category->name);
    Theme::set('pageName', $category->name);
@endphp


<div class="container mb-30">
    <div class="archive-header-2 text-center pt-80 pb-50">
        <h1 class="display-2 mb-50">{{ $category->name }}</h1>
        @if($category->description)
            <div class="row">
                <div class="col-lg-5 mx-auto">
                    <p class="text-muted">{{ $category->description }}</p>
                </div>
            </div>
        @endif
    </div>
    <div class="row flex-row-reverse">
        <div class="col-lg-4-5">
            <div class="shop-product-fillter mb-30">
                <div class="totall-product">
                    <p class="mb-0">{{ __('We found :count tours in :category', ['count' => $tours->total(), 'category' => $category->name]) }}</p>
                </div>
            </div>
            <div class="row product-grid">
                @forelse($tours as $tour)
                    <div class="col-lg-4 col-md-6 col-12 col-sm-6">
                        <div class="product-cart-wrap mb-30 wow animate__animated animate__fadeIn" data-wow-delay=".1s">
                            <div class="product-img-action-wrap">
                                <div class="product-img product-img-zoom">
                                    <a href="{{ route('public.tours.detail', $tour->slug) }}">
                                        <img class="default-img" src="{{ RvMedia::getImageUrl($tour->image, 'medium', false, RvMedia::getDefaultImage()) }}" alt="{{ $tour->name }}" />
                                    </a>
                                </div>
                                <div class="product-action-1">
                                    <a aria-label="{{ __('Quick view') }}" class="action-btn" href="{{ route('public.tours.detail', $tour->slug) }}"><i class="fi-rs-eye"></i></a>
                                </div>
                                @if($tour->is_featured)
                                    <div class="product-badges product-badges-position product-badges-mrg">
                                        <span class="hot">{{ __('Featured') }}</span>
                                    </div>
                                @endif

                            </div>
                            <div class="product-content-wrap">
                                <div class="product-category">
                                    <a href="{{ route('public.tours.category', $tour->category->slug ?? '') }}">{{ $tour->category->name ?? __('Uncategorized') }}</a>
                                </div>
                                <h2><a href="{{ route('public.tours.detail', $tour->slug) }}">{{ $tour->name }}</a></h2>
                                <div class="product-rate-cover">
                                    <div class="product-rate d-inline-block">
                                        @if($tour->reviews_count > 0)
                                            @php
                                                $averageRating = $tour->average_rating;
                                                $ratingPercentage = ($averageRating / 5) * 100;
                                            @endphp
                                            <div class="product-rating" style="width: {{ $ratingPercentage }}%"></div>
                                        @else
                                            <div class="product-rating" style="width: 0%"></div>
                                        @endif
                                    </div>
                                    <span class="font-small ml-5 text-muted">
                                        @if($tour->reviews_count > 0)
                                            ({{ number_format($tour->average_rating, 1) }} - {{ $tour->reviews_count }} {{ $tour->reviews_count == 1 ? __('review') : __('reviews') }})
                                        @else
                                            ({{ __('No reviews yet') }})
                                        @endif
                                    </span>
                                </div>
                                @if($tour->location)
                                    <div>
                                        <span class="font-small text-muted"><i class="fi-rs-marker"></i> {{ $tour->location }}</span>
                                    </div>
                                @endif
                                <div>
                                    <span class="font-small text-muted"><i class="fi-rs-clock"></i>
                                        @if(!empty($tour->duration_hours) && $tour->duration_hours > 0)
                                            {{ $tour->duration_hours }} {{ __('plugins/tours::tours.hours') }}
                                        @else
                                            {{ $tour->duration_days }} {{ __('days') }}@if($tour->duration_nights > 0), {{ $tour->duration_nights }} {{ __('nights') }}@endif
                                        @endif
                                    </span>
                                </div>
                                <div class="product-card-bottom">
                                    <div class="product-price">
                                        <span>{{ format_tour_price($tour->price) }}</span>
                                    </div>
                                    <div class="add-cart">
                                        <a class="add" href="{{ route('public.tours.detail', $tour->slug) }}"><i class="fi-rs-shopping-cart mr-5"></i>{{ __('Book Now') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center">
                            <img src="{{ Theme::asset()->url('imgs/theme/no-product.png') }}" alt="{{ __('No tours found') }}" class="mb-3" style="max-width: 200px;">
                            <h4>{{ __('No tours found in this category') }}</h4>
                            <p class="text-muted">{{ __('Sorry, we could not find any tours in this category.') }}</p>
                            <a href="{{ route('public.tours.index') }}" class="btn btn-primary">{{ __('Browse All Tours') }}</a>
                        </div>
                    </div>
                @endforelse
            </div>
            <!--product grid-->
            <div class="pagination-area mt-20 mb-20">
                <nav aria-label="Page navigation example">
                    {{ $tours->withQueryString()->links() }}
                </nav>
            </div>
        </div>
        <div class="col-lg-1-5 primary-sidebar sticky-sidebar">
            <div class="sidebar-widget widget-category-2 mb-30">
                <h5 class="section-title style-1 mb-30">{{ __('All Categories') }}</h5>
                <ul>
                    @foreach($categories as $cat)
                        <li>
                            <a href="{{ route('public.tours.category', $cat->slug) }}" class="{{ $cat->id == $category->id ? 'active' : '' }}">
                                @if($cat->icon)
                                    <img src="{{ RvMedia::getImageUrl($cat->icon, 'thumb') }}" alt="{{ $cat->name }}" />
                                @endif
                                {{ $cat->name }}
                            </a>
                            <span class="count">{{ $cat->tours_count }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="sidebar-widget widget-category-2 mb-30">
                <h5 class="section-title style-1 mb-30">{{ __('Browse All Tours') }}</h5>
                <a href="{{ route('public.tours.index') }}" class="btn btn-primary btn-sm">{{ __('View All Tours') }}</a>
            </div>
        </div>
    </div>
</div>