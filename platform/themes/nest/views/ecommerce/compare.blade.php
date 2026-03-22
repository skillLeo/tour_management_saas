<div class="mb-80 mt-50">
    <div class="row">
        <div class="col-lg-12 m-auto">
            <h1 class="compare-page-title mb-10">{{ __('Products Compare') }}</h1>
            <div class="table__compare">
                @if ($products->isNotEmpty())
                    <p class="text-body mb-30 font-heading h6">{!! BaseHelper::clean(__('There are :total products to compare', ['total' => '<span class="text-brand">' . $products->count() . '</span>'])) !!}</p>
                    <div class="compare-grid" style="--compare-cols: {{ $products->count() }}">
                        <div class="compare-section compare-section--products">
                            <div class="compare-row">
                                @foreach($products as $product)
                                    <div class="compare-cell compare-product-card">
                                        <a href="#" class="compare-remove-btn js-remove-from-compare-button" data-url="{{ route('public.compare.remove', $product->id) }}" title="{{ __('Remove') }}">
                                            <i class="fi-rs-cross-small"></i>
                                        </a>
                                        <a href="{{ $product->original_product->url }}" class="compare-product-img">
                                            <img src="{{ RvMedia::getImageUrl($product->image, 'product-thumb', false, RvMedia::getDefaultImage()) }}" alt="{{ $product->name }}">
                                        </a>
                                        <h6 class="compare-product-name">
                                            <a href="{{ $product->original_product->url }}">{!! BaseHelper::clean($product->name) !!}</a>
                                        </h6>
                                        @if (is_plugin_active('marketplace') && $product->original_product->store->id)
                                            <p class="compare-sold-by">
                                                <span>{{ __('Sold by') }}: </span>
                                                <a href="{{ $product->original_product->store->url }}">{{ $product->original_product->store->name }}</a>
                                            </p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        @if (! EcommerceHelper::hideProductPrice() || EcommerceHelper::isCartEnabled())
                            <div class="compare-section">
                                <div class="compare-section-label">{{ __('Price') }}</div>
                                <div class="compare-row">
                                    @foreach($products as $product)
                                        <div class="compare-cell">
                                            <div class="compare-price">
                                                <span class="price text-brand">{{ format_price($product->front_sale_price_with_taxes) }}</span>
                                                @if ($product->front_sale_price !== $product->price)
                                                    <span class="compare-price-old">
                                                        <del>{{ format_price($product->price_with_taxes) }}</del>
                                                        <span class="compare-discount-badge">{{ get_sale_percentage($product->price, $product->front_sale_price) }}</span>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if (EcommerceHelper::isReviewEnabled())
                            <div class="compare-section">
                                <div class="compare-section-label">{{ __('Rating') }}</div>
                                <div class="compare-row">
                                    @foreach($products as $product)
                                        <div class="compare-cell">
                                            @if ($product->reviews_count)
                                                <div class="compare-rating">
                                                    <div class="product-rate d-inline-block">
                                                        <div class="product-rating" style="width: {{ $product->reviews_avg * 20 }}%"></div>
                                                    </div>
                                                    <span class="rating_num">({{ $product->reviews_count }})</span>
                                                </div>
                                            @else
                                                <span class="text-muted font-sm">&mdash;</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="compare-section">
                            <div class="compare-section-label">{{ __('Description') }}</div>
                            <div class="compare-row">
                                @foreach($products as $product)
                                    <div class="compare-cell">
                                        <div class="compare-description compare-description--collapsed">
                                            <div class="compare-description__content">{!! BaseHelper::clean($product->description) !!}</div>
                                        </div>
                                        <button type="button" class="compare-toggle-desc" onclick="this.previousElementSibling.classList.toggle('compare-description--collapsed'); this.textContent = this.previousElementSibling.classList.contains('compare-description--collapsed') ? '{{ __('Show more') }}' : '{{ __('Show less') }}'">{{ __('Show more') }}</button>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        @foreach($attributeSets as $attributeSet)
                            @if ($attributeSet->is_comparable)
                                <div class="compare-section">
                                    <div class="compare-section-label">{{ $attributeSet->title }}</div>
                                    <div class="compare-row">
                                        @foreach($products as $product)
                                            @php
                                                $attributes = app(\Botble\Ecommerce\Repositories\Interfaces\ProductInterface::class)
                                                        ->getRelatedProductAttributes($product)
                                                        ->where('attribute_set_id', $attributeSet->id)
                                                        ->sortBy('order');
                                            @endphp
                                            <div class="compare-cell">
                                                @if ($attributes->count())
                                                    @if ($attributeSet->display_layout == 'dropdown')
                                                        <span class="compare-attr-text">{{ $attributes->pluck('title')->implode(', ') }}</span>
                                                    @elseif ($attributeSet->display_layout == 'text')
                                                        <div class="compare-attr-swatches">
                                                            @foreach($attributes as $attribute)
                                                                <span class="compare-text-swatch">{{ $attribute->title }}</span>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <div class="compare-attr-swatches">
                                                            @foreach($attributes as $attribute)
                                                                <span class="compare-color-swatch" style="{{ $attribute->image ? 'background-image: url(' . RvMedia::getImageUrl($attribute->image) . ');' : 'background-color: ' . $attribute->color . ';' }}" title="{{ $attribute->title }}"></span>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                @else
                                                    <span class="text-muted">&mdash;</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach

                        @if (EcommerceHelper::isCartEnabled())
                            <div class="compare-section">
                                <div class="compare-row">
                                    @foreach($products as $product)
                                        <div class="compare-cell">
                                            <a href="#" class="btn btn-sm add-to-cart-button" data-id="{{ $product->id }}" data-url="{{ route('public.ajax.cart.store') }}">
                                                <i class="fi-rs-shopping-bag mr-5"></i>{{ __('Add To Cart') }}
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="empty-state">
                        <img src="{{ Theme::asset()->url('imgs/theme/icons/icon-compare.svg') }}" alt="{{ __('No products in compare list!') }}" class="empty-state__icon">
                        <h5 class="empty-state__title">{{ __('No products in compare list!') }}</h5>
                        <p class="empty-state__text">{{ __('Add products to compare by clicking the compare icon on any product.') }}</p>
                        <a href="{{ route('public.products') }}" class="btn btn-sm">{{ __('Browse Products') }}</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
