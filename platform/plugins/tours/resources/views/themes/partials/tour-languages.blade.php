@if($tour->languages->count() > 0)
    <div class="tour-languages mb-4">
        <h5 class="mb-3">{{ __('Available Languages') }}</h5>
        <div class="d-flex flex-wrap gap-2">
            @foreach($tour->languages as $language)
                <div class="tour-language-item d-flex align-items-center">
                    @if($language->flag)
                        <img src="{{ RvMedia::getImageUrl($language->flag, 'thumb') }}" 
                             alt="{{ $language->name }}" 
                             class="language-flag me-2" 
                             style="width: 24px; height: auto;">
                    @endif
                    <span class="language-name">{{ $language->name }}</span>
                </div>
            @endforeach
        </div>
    </div>
@endif
