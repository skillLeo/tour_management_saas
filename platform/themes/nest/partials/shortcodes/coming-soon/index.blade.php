<section class="container box-coming-soon overflow-hidden">
    @if($shortcode->image)
        <div class="row align-items-center">
            <div class="col-lg-5 mb-30">
        @endif
                @if ($countdownTime)
                    <div class="deals-countdown" data-countdown="{{ $countdownTime }}"></div>
                @endif

                @if($shortcode->title)
                    <h2 class="coming-soon-title">
                        {!! BaseHelper::clean($shortcode->title) !!}
                    </h2>
                @endif

                @if($shortcode->subtitle ?? false)
                    <p class="coming-soon-subtitle">
                        {!! BaseHelper::clean($shortcode->subtitle) !!}
                    </p>
                @endif

                @if ($form)
                    <div class="newsletter coming-soon-newsletter">
                        {!! $form->renderForm() !!}
                    </div>
                @endif

                <div class="coming-soon-contact-info">
                    <ul class="list-wrap">
                        @if ($address = $shortcode->address)
                            <li>
                                <div class="coming-soon-contact-item">
                                    <span class="coming-soon-contact-icon"><x-core::icon name="ti ti-map-pin" /></span>
                                    <span>{!! BaseHelper::clean($address) !!}</span>
                                </div>
                            </li>
                        @endif

                        @if ($hotline = $shortcode->hotline)
                            <li>
                                <div class="coming-soon-contact-item">
                                    <span class="coming-soon-contact-icon"><x-core::icon name="ti ti-phone" /></span>
                                    <a href="tel:{{ $hotline }}" dir="ltr">{{ $hotline }}</a>
                                </div>
                            </li>
                        @endif

                        @if ($businessHours = $shortcode->business_hours)
                            <li>
                                <div class="coming-soon-contact-item">
                                    <span class="coming-soon-contact-icon"><x-core::icon name="ti ti-clock" /></span>
                                    <span>{!! BaseHelper::clean(nl2br($businessHours)) !!}</span>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>

                @if($shortcode->show_social_links ?? true)
                    @if ($socialLinks = theme_option('social_links'))
                        <div class="mobile-social-icon coming-soon-social">
                            @foreach(json_decode($socialLinks, true) as $socialLink)
                                @if (count($socialLink) == 3)
                                    <a href="{{ $socialLink[2]['value'] }}"
                                       title="{{ $socialLink[0]['value'] }}">
                                        <img src="{{ RvMedia::getImageUrl($socialLink[1]['value']) }}" alt="{{ $socialLink[0]['value'] }}" />
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    @endif
                @endif

                @if($shortcode->image)
            </div>
            <div class="col-lg-7 mb-30">
                {{ RvMedia::image($shortcode->image, $shortcode->title, attributes: ['class' => 'coming-soon-image']) }}
            </div>
            @endif
        </div>
</section>
