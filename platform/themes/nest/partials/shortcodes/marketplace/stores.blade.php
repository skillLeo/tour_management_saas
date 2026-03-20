<section class="section-padding position-relative">
    <div class="container">
        <div class="section-title style-2 wow animate__animated animate__fadeIn">
            <div class="title">
                <h2>{{ $shortcode->title }}</h2>
            </div>
        </div>

        <div class="row vendor-grid">
            @foreach($stores as $store)
                @include(Theme::getThemeNamespace('views.marketplace.stores.item.' . $layout))
            @endforeach
        </div>
    </div>
</section>
