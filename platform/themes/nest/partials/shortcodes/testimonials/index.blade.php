@php
    $slick = [
        'rtl' => BaseHelper::siteLanguageDirection() == 'rtl',
        'arrows' => true,
        'dots' => false,
        'autoplay' => $shortcode->is_autoplay == 'yes',
        'infinite' => $shortcode->infinite == 'yes' || $shortcode->is_infinite == 'yes',
        'autoplaySpeed' => in_array($shortcode->autoplay_speed, theme_get_autoplay_speed_options()) ? $shortcode->autoplay_speed : 3000,
        'speed' => 800,
        'slidesToShow' => $shortcode->slides_to_show ?: 4,
        'slidesToScroll' => 1,
        'responsive' => [
            [
                'breakpoint' => 1199,
                'settings' => [
                    'slidesToShow' => 2,
                ],
            ],
            [
                'breakpoint' => 767,
                'settings' => [
                    'arrows' => false,
                    'dots' => false,
                    'slidesToShow' => 1,
                    'slidesToScroll' => 1,
                ],
            ],
        ],
    ];
@endphp

<section class="section-padding-60">
    <div class="container">
        @if($shortcode->title || $shortcode->subtitle)
            <div class="mb-50">
                @if($shortcode->title)
                    <h3 class="section-title style-1 mb-30 wow fadeIn animated">{!! BaseHelper::clean($shortcode->title) !!}</h3>
                @endif
                @if ($shortcode->subtitle)
                    <p class="text-muted wow fadeIn animated">{!! BaseHelper::clean($shortcode->subtitle) !!}</p>
                @endif
            </div>
        @endif

        <div class="carousel-6-columns-cover arrow-center position-relative wow fadeIn animated">
            <div class="slider-arrow slider-arrow-3 carousel-6-columns-arrow" id="testimonials-carousel-arrows"></div>
            <div
                class="carousel-slider-wrapper carousel-6-columns testimonials-slider"
                id="testimonials-carousel"
                data-slick="{{ json_encode($slick) }}"
            >
                @foreach($testimonials as $testimonial)
                    <div class="testimonial-item h-100">
                        <div class="testimonial-card border border-1 rounded-15 p-30 h-100 bg-white hover-up d-flex flex-column">
                            <div class="testimonial-quote mb-20">
                                <img src="{{ Theme::asset()->url('imgs/testimonial-quote.png') }}" alt="quote" width="40" />
                            </div>
                            <div class="testimonial-rating mb-20">
                                @php
                                    $stars = $testimonial->shortcode_stars ?? 5;
                                @endphp
                                @for ($i = 1; $i <= 5; $i++)
                                    <span class="font-xs {{ $i <= $stars ? 'text-warning' : 'text-muted' }}">
                                        <x-core::icon name="{{ $i <= $stars ? 'ti ti-star-filled' : 'ti ti-star' }}" />
                                    </span>
                                @endfor
                            </div>
                            <div class="testimonial-content mb-30 flex-grow-1">
                                <p class="font-md text-muted">
                                    {!! BaseHelper::clean($testimonial->content) !!}
                                </p>
                            </div>
                            <div class="testimonial-user d-flex align-items-center mt-auto">
                                <div class="testimonial-avatar me-3">
                                    <div class="rounded-circle overflow-hidden" style="width: 50px; height: 50px;">
                                        {{ RvMedia::image($testimonial->image, $testimonial->name, 'thumb') }}
                                    </div>
                                </div>
                                <div class="testimonial-user-info">
                                    <h6 class="mb-0 font-md">{{ $testimonial->name }}</h6>
                                    <span class="font-xs text-muted">{{ $testimonial->company }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
