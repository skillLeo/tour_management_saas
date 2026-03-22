@if ($keys->isNotEmpty())
    @php
        $shortcode = $shortcode ?? null;
        $enableCarousel = $shortcode && $shortcode->carousel === 'yes';
        $itemsDesktop = (int) ($shortcode->items_desktop ?? 3);
        $itemsTablet = (int) ($shortcode->items_tablet ?? 2);
        $itemsMobile = (int) ($shortcode->items_mobile ?? 1);

        $slickConfig = $enableCarousel ? [
            'rtl' => BaseHelper::siteLanguageDirection() === 'rtl',
            'arrows' => true,
            'dots' => true,
            'infinite' => true,
            'autoplay' => ($shortcode->autoplay ?? 'yes') === 'yes',
            'autoplaySpeed' => in_array($shortcode->autoplay_speed, theme_get_autoplay_speed_options())
                ? (int) $shortcode->autoplay_speed
                : 5000,
            'speed' => 800,
            'slidesToShow' => $itemsDesktop,
            'slidesToScroll' => 1,
            'responsive' => [
                [
                    'breakpoint' => 1200,
                    'settings' => [
                        'slidesToShow' => $itemsDesktop,
                    ],
                ],
                [
                    'breakpoint' => 992,
                    'settings' => [
                        'slidesToShow' => $itemsTablet,
                    ],
                ],
                [
                    'breakpoint' => 768,
                    'settings' => [
                        'arrows' => false,
                        'slidesToShow' => $itemsMobile,
                    ],
                ],
            ],
        ] : null;

        $carouselId = $enableCarousel ? 'ads-carousel-' . uniqid() : null;
    @endphp

    @if ($enableCarousel)
        <section class="section-padding ads-carousel-section">
            <div class="container">
                <div class="carousel-ads-cover arrow-center position-relative wow fadeIn animated">
                    <div class="slider-arrow slider-arrow-3 carousel-ads-arrow" id="{{ $carouselId }}-arrows"></div>
                    <div
                        class="carousel-slider-wrapper carousel-ads"
                        id="{{ $carouselId }}"
                        data-slick="{{ json_encode($slickConfig) }}"
                    >
                        @foreach ($keys as $key)
                            <div class="carousel-ad-item">
                                {!! display_ad($key, '', $loop, $shortcode) !!}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @elseif ($style == 'style-5')
        <section class="section-padding">
            <div class="container">
                <div class="row">
                    @foreach ($keys as $key)
                        <div class="col-lg-3 col-md-6">
                            {!! display_ad($key, '', $loop, $shortcode) !!}
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @else
        <section class="banners pt-60">
            <div class="container">
                <div class="row justify-content-center">
                    @foreach ($keys as $key)
                        <div class="col-lg-4 col-md-6">
                            {!! display_ad($key, '', $loop, $shortcode) !!}
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endif
