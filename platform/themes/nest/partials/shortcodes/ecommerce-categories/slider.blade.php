<section class="popular-categories section-padding" @if ($shortcode->background_color && $shortcode->background_color !== 'transparent') style="background-color: {{ $shortcode->background_color }};" @endif>
    <div class="container wow animate__animated animate__fadeIn">
        <div class="section-title">
            <div class="title">
                @if ($shortcode->title)
                    <h2>{{ BaseHelper::clean($shortcode->title) }}</h2>
                @endif
                @if ($shortcode->subtitle)
                    <p>{{ $shortcode->subtitle }}</p>
                @endif
            </div>
            <div class="slider-arrow slider-arrow-2 flex-right carousel-categories-arrow" id="carousel-categories-arrows"></div>
        </div>
        <div class="carousel-categories-cover position-relative">
            <div class="carousel-slider-wrapper carousel-categories" id="carousel-categories" title="{{ $shortcode->title }}"
                 data-slick="{{ json_encode([
                    'autoplay' => $shortcode->is_autoplay == 'yes',
                    'infinite' => true,
                    'autoplaySpeed' => (int)(in_array($shortcode->autoplay_speed, theme_get_autoplay_speed_options()) ? $shortcode->autoplay_speed : 3000),
                    'speed' => 800,
                ]) }}"
                 data-items-xxl="{{ $numberOfItems = ((int)$shortcode->items_per_view > 0 ? (int)$shortcode->items_per_view : 5) }}"
                 data-items-xl="{{ max($numberOfItems - 1, 4) }}"
                 data-items-lg="4"
                 data-items-md="3"
                 data-items-sm="2"
            >
                @foreach($categories as $category)
                    <div class="card-2 wow animate__animated animate__fadeInUp"
                         data-wow-delay="{{ ($loop->index + 1) / 10 }}s"
                         style="{{ $category->getMetaData('background_color', true) ? 'background-color:' . $category->getMetaData('background_color', true) : '' }}">
                        <figure class="img-hover-scale overflow-hidden">
                            <a href="{{ $category->url }}">
                                <img src="{{ RvMedia::getImageUrl($category->image, null, false, RvMedia::getDefaultImage()) }}" alt="{{ $category->name }}" />
                            </a>
                        </figure>
                        <h6>
                            <a href="{{ $category->url }}" title="{{ $category->name }}">{{ $category->name }}</a>
                        </h6>
                        @if ($shortcode->show_products_count)
                            <span>{{ __(':count items', ['count' => $category->count_all_products]) }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
