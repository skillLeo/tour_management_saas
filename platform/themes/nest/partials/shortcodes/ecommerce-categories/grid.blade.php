<section class="popular-categories section-padding" @if ($shortcode->background_color && $shortcode->background_color !== 'transparent') style="background-color: {{ $shortcode->background_color }};" @endif>
    <div class="container wow animate__animated animate__fadeIn">
        @if ($shortcode->title || $shortcode->subtitle)
            <div class="section-title">
                <div class="title">
                    @if ($shortcode->title)
                        <h2>{{ BaseHelper::clean($shortcode->title) }}</h2>
                    @endif
                    @if ($shortcode->subtitle)
                        <p>{{ $shortcode->subtitle }}</p>
                    @endif
                </div>
            </div>
        @endif
        <div class="row">
            @foreach($categories as $category)
                <div class="col-xl-3 col-lg-4 col-md-6 mb-lg-0 mb-md-5 mb-sm-5">
                    <div class="card-2 bg-9 wow animate__animated animate__fadeInUp" data-wow-delay="{{ ($loop->index + 1) / 10 }}s" style="{{ $category->getMetaData('background_color', true) ? 'background-color:' . $category->getMetaData('background_color', true) : '' }}">
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
                </div>
            @endforeach
        </div>
    </div>
</section>
