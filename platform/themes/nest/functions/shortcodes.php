<?php

use Botble\Ads\Events\AdsLoading;
use Botble\Ads\Facades\AdsManager;
use Botble\Ads\Models\Ads;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FieldOptions\ColorFieldOption;
use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\NumberFieldOption;
use Botble\Base\Forms\FieldOptions\OnOffFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\ColorField;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\MultiCheckListField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Models\BaseModel;
use Botble\Blog\Repositories\Interfaces\PostInterface;
use Botble\Ecommerce\Enums\StockStatusEnum;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Models\FlashSale;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\ProductCategory;
use Botble\Ecommerce\Repositories\Interfaces\ProductCategoryInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Botble\Faq\Models\FaqCategory;
use Botble\Faq\Repositories\Interfaces\FaqCategoryInterface;
use Botble\Newsletter\Forms\Fronts\NewsletterForm;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Shortcode\Compilers\Shortcode;
use Botble\Shortcode\Facades\Shortcode as ShortcodeFacade;
use Botble\Shortcode\Forms\ShortcodeForm;
use Botble\Shortcode\ShortcodeField;
use Botble\Theme\Facades\Theme;
use Botble\Theme\Supports\ThemeSupport;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

app()->booted(function (): void {
    ThemeSupport::registerGoogleMapsShortcode();
    ThemeSupport::registerYoutubeShortcode();

    if (is_plugin_active('simple-slider')) {
        add_filter(SIMPLE_SLIDER_VIEW_TEMPLATE, function () {
            return Theme::getThemeNamespace() . '::partials.shortcodes.sliders.main';
        }, 120);
    }

    if (is_plugin_active('ecommerce')) {
        add_shortcode(
            'featured-product-categories',
            __('Featured Product Categories'),
            __('Featured Product Categories'),
            function (Shortcode $shortcode) {
                $categories = get_featured_product_categories();

                $categories->loadMissing('metadata');

                return Theme::partial(
                    'shortcodes.ecommerce.featured-product-categories',
                    compact('shortcode', 'categories')
                );
            }
        );

        shortcode()->setAdminConfig('featured-product-categories', function (array $attributes) {
            return ShortcodeForm::createFromArray($attributes)
                ->add(
                    'title',
                    TextField::class,
                    TextFieldOption::make()
                        ->label(__('Title'))
                )
                ->add(
                    'scroll_items',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Scroll display items'))
                        ->choices([
                            '6' => '6',
                            '7' => '7',
                            '8' => '8',
                            '9' => '9',
                            '10' => '10',
                        ])
                        ->defaultValue('10')
                )
                ->add(
                    'scroll_items_on_mobile',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Scroll display items on mobile'))
                        ->choices([
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                        ])
                        ->defaultValue('2')
                )
                ->add(
                    'show_products_count',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Show products count'))
                        ->choices([
                            'yes' => __('Yes'),
                            'no' => __('No'),
                        ])
                        ->defaultValue('yes')
                )
                ->add(
                    'is_autoplay',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Is autoplay'))
                        ->choices([
                            'yes' => __('Yes'),
                            'no' => __('No'),
                        ])
                        ->defaultValue('no')
                )
                ->add(
                    'is_infinite',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Infinite'))
                        ->choices([
                            'yes' => __('Yes'),
                            'no' => __('No'),
                        ])
                        ->defaultValue('yes')
                )
                ->add(
                    'autoplay_speed',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Autoplay speed (if autoplay enabled)'))
                        ->choices([
                            '1000' => '1s',
                            '2000' => '2s',
                            '3000' => '3s',
                            '4000' => '4s',
                            '5000' => '5s',
                        ])
                        ->defaultValue('3000')
                );
        });

        add_shortcode('flash-sale', __('Flash sale'), __('Flash sale'), function (Shortcode $shortcode) {
            $with = [
                'products' => function (BelongsToMany $query) {
                    return $query
                        ->wherePublished()
                        ->with(EcommerceHelper::withProductEagerLoadingRelations());
                },
                'metadata',
            ];

            $flashSalePopup = null;
            if ($flashSalePopupId = $shortcode->flash_sale_popup_id) {
                $flashSalePopup = FlashSale::query()
                    ->where('id', $flashSalePopupId)
                    ->notExpired()
                    ->with($with)
                    ->first();
            }

            $flashSaleIds = [];
            for ($i = 1; $i <= 4; $i++) {
                if ($shortcode->{'flash_sale_' . $i}) {
                    $flashSaleIds[] = $shortcode->{'flash_sale_' . $i};
                }
            }

            $flashSales = FlashSale::query()
                ->notExpired()
                ->wherePublished()
                ->with($with)
                ->whereIn('id', $flashSaleIds)
                ->get();

            if (! $flashSales->count()) {
                return null;
            }

            return Theme::partial(
                'shortcodes.flash-sale.default',
                compact('shortcode', 'flashSales', 'flashSalePopup')
            );
        });

        shortcode()->setAdminConfig('flash-sale', function (array $attributes) {
            $flashSales = FlashSale::query()
                ->wherePublished()
                ->notExpired()
                ->pluck('name', 'id')
                ->toArray();

            $flashSales = ['' => __('None')] + $flashSales;

            return ShortcodeForm::createFromArray($attributes)
                ->add(
                    'title',
                    TextField::class,
                    TextFieldOption::make()
                        ->label(__('Title'))
                )
                ->add(
                    'flash_sale_1',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Flash sale 1'))
                        ->choices($flashSales)
                )
                ->add(
                    'flash_sale_2',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Flash sale 2'))
                        ->choices($flashSales)
                )
                ->add(
                    'flash_sale_3',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Flash sale 3'))
                        ->choices($flashSales)
                )
                ->add(
                    'flash_sale_4',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Flash sale 4'))
                        ->choices($flashSales)
                )
                ->add(
                    'flash_sale_popup_id',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Show flash sale popup?'))
                        ->choices($flashSales)
                )
                ->withLazyLoading();
        });

        add_shortcode('best-flash-sale', __('Best flash sale'), __('Best flash sale'), function (Shortcode $shortcode) {
            $flashSalePopup = null;
            if ($flashSaleId = $shortcode->flash_sale_id) {
                $flashSalePopup = FlashSale::query()
                    ->where(['id' => $flashSaleId])
                    ->notExpired()
                    ->wherePublished()
                    ->with([
                        'products' => function ($query) {
                            return $query
                                ->wherePublished()
                                ->with(EcommerceHelper::withProductEagerLoadingRelations());
                        },
                        'metadata',
                    ])
                    ->first();
            }

            if (! $flashSalePopup || ! $flashSalePopup->products->count()) {
                return null;
            }

            return Theme::partial('shortcodes.flash-sale.best', compact('shortcode', 'flashSalePopup'));
        });

        shortcode()->setAdminConfig('best-flash-sale', function (array $attributes) {
            $flashSales = FlashSale::query()
                ->wherePublished()
                ->notExpired()
                ->pluck('name', 'id')
                ->toArray();

            $flashSales = ['' => __('None')] + $flashSales;

            $form = ShortcodeForm::createFromArray($attributes)
                ->add(
                    'title',
                    TextField::class,
                    TextFieldOption::make()
                        ->label(__('Title'))
                )
                ->add(
                    'flash_sale_id',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Flash sale'))
                        ->choices($flashSales)
                );

            if (is_plugin_active('ads')) {
                $form->add(
                    'ads',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Ads'))
                        ->choices(AdsManager::getData()->pluck('name', 'key')->toArray())
                );
            }

            return $form;
        });

        add_shortcode(
            'product-collections',
            __('Product Collections'),
            __('Product Collections'),
            function (Shortcode $shortcode) {
                $productCollections = get_product_collections(
                    ['status' => BaseStatusEnum::PUBLISHED],
                    [],
                    ['id', 'name', 'slug']
                );

                if ($productCollections->isEmpty()) {
                    return null;
                }

                $limit = (int) $shortcode->limit ?: 8;

                $products = get_products_by_collections([
                    'collections' => [
                        'by' => 'id',
                        'value_in' => [$productCollections->first()->id],
                    ],
                    'take' => $limit,
                    'with' => EcommerceHelper::withProductEagerLoadingRelations(),
                ]);

                $perRow = (int) $shortcode->per_row > 0 ? (int) $shortcode->per_row : 4;

                return Theme::partial('shortcodes.ecommerce.product-collections', [
                    'title' => $shortcode->title,
                    'productCollections' => $productCollections,
                    'limit' => $limit,
                    'products' => $products,
                    'perRow' => $perRow,
                ]);
            }
        );

        shortcode()->setAdminConfig('product-collections', function (array $attributes) {
            return ShortcodeForm::createFromArray($attributes)
                ->add(
                    'title',
                    TextField::class,
                    TextFieldOption::make()
                        ->label(__('Title'))
                )
                ->add(
                    'per_row',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Number of products per row'))
                        ->choices([
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                            '6' => '6',
                        ])
                        ->defaultValue('4')
                )
                ->add(
                    'limit',
                    NumberField::class,
                    NumberFieldOption::make()
                        ->label(__('Total display products'))
                        ->defaultValue(8)
                );
        });

        add_shortcode(
            'product-category-products',
            __('Product category products'),
            __('Product category products'),
            function (Shortcode $shortcode) {
                $category = ProductCategory::query()
                    ->where([
                        'status' => BaseStatusEnum::PUBLISHED,
                        'id' => $shortcode->category_id,
                    ])
                    ->first();

                if (! $category) {
                    return null;
                }

                $category->load([
                    'activeChildren' => function ($query): void {
                        $query->limit(3);
                    },
                ]);

                $limit = (int) $shortcode->limit ?: 8;

                $products = app(ProductInterface::class)->getProductsByCategories([
                    'categories' => [
                        'by' => 'id',
                        'value_in' => array_merge([$category->id], $category->activeChildren->pluck('id')->all()),
                    ],
                    'take' => $limit,
                ]);

                $perRow = (int) $shortcode->per_row > 0 ? (int) $shortcode->per_row : 4;

                return Theme::partial(
                    'shortcodes.ecommerce.product-category-products',
                    compact('category', 'products', 'limit', 'perRow')
                );
            }
        );

        shortcode()->setAdminConfig('product-category-products', function (array $attributes) {
            $categories = ProductCategory::query()
                ->wherePublished()
                ->orderBy('order')
                ->orderBy('created_at', 'desc')
                ->get()
                ->pluck('name', 'id')
                ->toArray();

            return ShortcodeForm::createFromArray($attributes)
                ->add(
                    'category_id',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Product category'))
                        ->choices($categories)
                        ->searchable()
                )
                ->add(
                    'per_row',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Number of products per row'))
                        ->choices([
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                            '6' => '6',
                        ])
                        ->defaultValue('4')
                )
                ->add(
                    'limit',
                    NumberField::class,
                    NumberFieldOption::make()
                        ->label(__('Total display products'))
                        ->defaultValue(8)
                );
        });

        add_shortcode('featured-brands', __('Featured Brands'), __('Featured Brands'), function (Shortcode $shortcode) {
            $brands = get_featured_brands();

            return Theme::partial('shortcodes.ecommerce.featured-brands', compact('shortcode', 'brands'));
        });

        shortcode()->setAdminConfig('featured-brands', function (array $attributes) {
            return ShortcodeForm::createFromArray($attributes)
                ->add(
                    'title',
                    TextField::class,
                    TextFieldOption::make()
                        ->label(__('Title'))
                )
                ->add(
                    'scroll_items',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Scroll display items'))
                        ->choices([
                            '6' => '6',
                            '7' => '7',
                            '8' => '8',
                            '9' => '9',
                            '10' => '10',
                        ])
                        ->defaultValue('8')
                )
                ->add(
                    'is_autoplay',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Is autoplay'))
                        ->choices([
                            'yes' => __('Yes'),
                            'no' => __('No'),
                        ])
                        ->defaultValue('no')
                )
                ->add(
                    'is_infinite',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Infinite'))
                        ->choices([
                            'yes' => __('Yes'),
                            'no' => __('No'),
                        ])
                        ->defaultValue('yes')
                )
                ->add(
                    'autoplay_speed',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Autoplay speed (if autoplay enabled)'))
                        ->choices([
                            '1000' => '1s',
                            '2000' => '2s',
                            '3000' => '3s',
                            '4000' => '4s',
                            '5000' => '5s',
                        ])
                        ->defaultValue('3000')
                );
        });

        add_shortcode(
            'product-categories',
            __('Product Categories'),
            __('Product Categories'),
            function (Shortcode $shortcode) {
                $params = array_merge([
                    'condition' => [
                        'ec_product_categories.status' => BaseStatusEnum::PUBLISHED,
                    ],
                    'take' => null,
                    'order_by' => [
                        'ec_product_categories.order' => 'DESC',
                    ],
                    'select' => ['*'],
                    'with' => ['slugable', 'metadata'],
                ]);

                $categoryIds = array_filter(explode(',', $shortcode->categories));

                if (! empty($categoryIds)) {
                    $params['condition'][] = ['ec_product_categories.id', 'IN', $categoryIds];
                }

                $categories = app(ProductCategoryInterface::class)->advancedGet($params);

                return Theme::partial('shortcodes.ecommerce.product-categories', compact('shortcode', 'categories'));
            }
        );

        shortcode()->setAdminConfig('product-categories', function (array $attributes) {
            // Transform comma-separated string to array for the form
            if (isset($attributes['categories']) && is_string($attributes['categories'])) {
                $attributes['categories'] = array_filter(explode(',', $attributes['categories']));
            }

            return ShortcodeForm::createFromArray($attributes)
                ->add(
                    'title',
                    TextField::class,
                    TextFieldOption::make()
                        ->label(__('Title'))
                )
                ->add(
                    'scroll_items',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Scroll display items'))
                        ->choices([
                            '6' => '6',
                            '7' => '7',
                            '8' => '8',
                            '9' => '9',
                            '10' => '10',
                        ])
                        ->defaultValue('8')
                )
                ->add(
                    'scroll_items_on_mobile',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Scroll display items on mobile'))
                        ->choices([
                            '1' => '1',
                            '2' => '2',
                            '3' => '3',
                        ])
                        ->defaultValue('2')
                )
                ->add(
                    'is_autoplay',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Is autoplay'))
                        ->choices([
                            'yes' => __('Yes'),
                            'no' => __('No'),
                        ])
                        ->defaultValue('no')
                )
                ->add(
                    'is_infinite',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Infinite'))
                        ->choices([
                            'yes' => __('Yes'),
                            'no' => __('No'),
                        ])
                        ->defaultValue('yes')
                )
                ->add(
                    'autoplay_speed',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Autoplay speed (if autoplay enabled)'))
                        ->choices([
                            '1000' => '1s',
                            '2000' => '2s',
                            '3000' => '3s',
                            '4000' => '4s',
                            '5000' => '5s',
                        ])
                        ->defaultValue('3000')
                )
                ->add(
                    'categories',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->choices(
                            ProductCategory::query()
                                ->wherePublished()
                                ->pluck('name', 'id')
                                ->all()
                        )
                        ->label(__('Choose categories'))
                        ->selected(ShortcodeField::parseIds(Arr::get($attributes, 'category_ids')))
                        ->searchable()
                        ->multiple()
                );
        });

        add_shortcode(
            'top-products-group',
            __('Top Products Group'),
            __('Top Products Group'),
            function (Shortcode $shortcode) {
                $tabs = array_filter(explode(',', $shortcode->tabs));

                if (empty($tabs)) {
                    $tabs = ['top-selling', 'trending-products', 'recent-added', 'top-rated'];
                }

                $limit = 4;

                $with = ['slugable', 'variationInfo', 'productCollections'];

                $data = [];

                if (in_array('top-selling', $tabs)) {
                    $endDate = Carbon::now();
                    $startDate = Carbon::now()->subDays($shortcode->top_selling_in_days ?: 30);

                    $topSellingQuery = Product::query()
                        ->join('ec_order_product', 'ec_products.id', '=', 'ec_order_product.product_id')
                        ->join('ec_orders', 'ec_orders.id', '=', 'ec_order_product.order_id');

                    if (is_plugin_active('payment')) {
                        $topSellingQuery = $topSellingQuery
                            ->join('payments', 'payments.order_id', '=', 'ec_orders.id')
                            ->where('payments.status', PaymentStatusEnum::COMPLETED);
                    }

                    $topSelling = $topSellingQuery
                        ->whereDate('ec_orders.created_at', '>=', $startDate)
                        ->whereDate('ec_orders.created_at', '<=', $endDate)
                        ->select([
                            'ec_products.*',
                            'ec_order_product.qty as qty',
                        ])
                        ->with($with)
                        ->orderByDesc('ec_order_product.qty')
                        ->distinct()
                        ->limit($limit)
                        ->get();

                    if ($topSelling->isNotEmpty()) {
                        $data[] = [
                            'title' => __('Top Selling'),
                            'products' => $topSelling,
                        ];
                    }
                }

                if (in_array('trending-products', $tabs)) {
                    $trendingProducts = get_trending_products(
                        [
                            'take' => $limit,
                            'with' => $with,
                        ]
                    );

                    if ($trendingProducts->isNotEmpty()) {
                        $data[] = [
                            'title' => __('Trending Products'),
                            'products' => $trendingProducts,
                        ];
                    }
                }

                if (in_array('recent-added', $tabs)) {
                    $recentlyAdded = app(ProductInterface::class)
                        ->advancedGet(
                            [
                                'condition' => [
                                    'ec_products.status' => BaseStatusEnum::PUBLISHED,
                                    'ec_products.is_variation' => 0,
                                ],
                                'order_by' => [
                                    'ec_products.order' => 'ASC',
                                    'ec_products.created_at' => 'DESC',
                                ],
                                'take' => $limit,
                                'with' => $with,
                            ]
                        );

                    if ($recentlyAdded->isNotEmpty()) {
                        $data[] = [
                            'title' => __('Recently Added'),
                            'products' => $recentlyAdded,
                        ];
                    }
                }

                if (EcommerceHelper::isReviewEnabled() && in_array('top-rated', $tabs)) {
                    $topRated = get_top_rated_products($limit, $with);

                    if ($topRated->isNotEmpty()) {
                        $data[] = [
                            'title' => __('Top Rated'),
                            'products' => $topRated,
                        ];
                    }
                }

                return Theme::partial('shortcodes.ecommerce.top-products-group', compact('shortcode', 'data'));
            }
        );

        shortcode()->setAdminConfig('top-products-group', function (array $attributes) {
            // Transform comma-separated string to array for the form
            if (isset($attributes['tabs']) && is_string($attributes['tabs'])) {
                $attributes['tabs'] = array_filter(explode(',', $attributes['tabs']));
            }

            if (empty($attributes['tabs'])) {
                $attributes['tabs'] = ['top-selling', 'trending-products', 'recent-added', 'top-rated'];
            }

            $form = ShortcodeForm::createFromArray($attributes)
                ->add(
                    'tabs',
                    MultiCheckListField::class,
                    [
                        'label' => __('Tabs'),
                        'choices' => [
                            'top-selling' => __('Top selling'),
                            'trending-products' => __('Trending products'),
                            'recent-added' => __('Recent added'),
                            'top-rated' => __('Top rated'),
                        ],
                    ]
                )
                ->add(
                    'top_selling_in_days',
                    NumberField::class,
                    NumberFieldOption::make()
                        ->label(__('Top selling products within x days'))
                        ->defaultValue(30)
                )
                ->withLazyLoading();

            return $form;
        });

        add_shortcode(
            'popular-products',
            __('Popular Products'),
            __('Popular Products'),
            function (Shortcode $shortcode) {
                $products = Product::query()
                    ->join('meta_boxes', function ($join): void {
                        $join
                            ->on('ec_products.id', '=', 'meta_boxes.reference_id')
                            ->where('meta_boxes.reference_type', '=', Product::class);
                    })
                    ->where([
                        'meta_boxes.meta_key' => 'is_popular',
                        'meta_boxes.meta_value' => '["1"]',
                        'ec_products.status' => BaseStatusEnum::PUBLISHED,
                        'ec_products.is_variation' => 0,
                    ])
                    ->with(array_merge(['metadata'], EcommerceHelper::withProductEagerLoadingRelations()))
                    ->orderByRaw('
                        CASE
                            WHEN ec_products.with_storehouse_management = 0 THEN
                                CASE WHEN ec_products.stock_status = ? THEN 1 ELSE 0 END
                            ELSE
                                CASE WHEN ec_products.quantity <= 0 AND ec_products.allow_checkout_when_out_of_stock = 0 THEN 1 ELSE 0 END
                        END ASC
                    ', [StockStatusEnum::OUT_OF_STOCK])
                    ->orderBy('ec_products.order', 'ASC')
                    ->orderBy('ec_products.created_at', 'DESC')
                    ->limit((int) $shortcode->limit ?: 8)
                    ->select('ec_products.*')
                    ->distinct()
                    ->get();

                return Theme::partial('shortcodes.ecommerce.popular-products', compact('shortcode', 'products'));
            }
        );

        shortcode()->setAdminConfig('popular-products', function (array $attributes) {
            return ShortcodeForm::createFromArray($attributes)
                ->add(
                    'title',
                    TextField::class,
                    TextFieldOption::make()
                        ->label(__('Title'))
                )
                ->add(
                    'per_row',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Number of products per row'))
                        ->choices([
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                            '6' => '6',
                        ])
                        ->defaultValue('4')
                )
                ->add(
                    'limit',
                    NumberField::class,
                    NumberFieldOption::make()
                        ->label(__('Total display products'))
                        ->defaultValue(8)
                )
                ->withLazyLoading();
        });

        add_shortcode(
            'trending-products',
            __('Trending products'),
            __('Trending products'),
            function (Shortcode $shortcode) {
                $products = get_trending_products([
                        'take' => (int) $shortcode->limit ?: 10,
                        'with' => ['slugable'],
                    ]);

                if ($products->isEmpty()) {
                    return null;
                }

                return Theme::partial('shortcodes.ecommerce.trending-products', compact('shortcode', 'products'));
            }
        );

        shortcode()->setAdminConfig('trending-products', function (array $attributes) {
            return ShortcodeForm::createFromArray($attributes)
                ->add(
                    'title',
                    TextField::class,
                    TextFieldOption::make()
                        ->label(__('Title'))
                )
                ->add(
                    'limit',
                    NumberField::class,
                    NumberFieldOption::make()
                        ->label(__('Limit'))
                        ->defaultValue(10)
                )
                ->add(
                    'per_row',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Number of products per row'))
                        ->choices([
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                            '6' => '6',
                        ])
                        ->defaultValue('5')
                );
        });

        add_shortcode(
            'featured-products',
            __('Featured products'),
            __('Featured products'),
            function (Shortcode $shortcode) {
                $products = get_featured_products([
                        'take' => (int) $shortcode->limit ?: 10,
                        'with' => ['slugable'],
                    ]);

                if ($products->isEmpty()) {
                    return null;
                }

                return Theme::partial('shortcodes.ecommerce.featured-products', compact('shortcode', 'products'));
            }
        );

        shortcode()->setAdminConfig('featured-products', function (array $attributes) {
            return ShortcodeForm::createFromArray($attributes)
                ->add(
                    'title',
                    TextField::class,
                    TextFieldOption::make()
                        ->label(__('Title'))
                )
                ->add(
                    'limit',
                    NumberField::class,
                    NumberFieldOption::make()
                        ->label(__('Limit'))
                        ->defaultValue(10)
                )
                ->add(
                    'per_row',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Number of products per row'))
                        ->choices([
                            '2' => '2',
                            '3' => '3',
                            '4' => '4',
                            '5' => '5',
                            '6' => '6',
                        ])
                        ->defaultValue('5')
                );
        });
    }

    if (is_plugin_active('ads')) {
        add_shortcode('theme-ads', __('Theme ads'), __('Theme ads'), function (Shortcode $shortcode) {
            $keys = get_ads_keys_from_shortcode($shortcode);

            return display_ads($keys, $shortcode->style, $shortcode);
        });

        shortcode()->setAdminConfig('theme-ads', function (array $attributes) {
            $ads = Ads::query()
                ->wherePublished()
                ->notExpired()
                ->pluck('name', 'key')
                ->toArray();

            $form = ShortcodeForm::createFromArray($attributes);

            // Add 5 ad selection fields
            for ($i = 1; $i <= 5; $i++) {
                $form->add(
                    'ads_' . $i,
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Ad #:number', ['number' => $i]))
                        ->choices(['' => __('-- Select --')] + $ads)
                        ->allowClear()
                );
            }

            $form->add(
                'style',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(__('Style'))
                    ->choices([
                        '' => __('Default'),
                        'style-5' => __('Style 5'),
                    ])
            )
            ->add(
                'text_color',
                ColorField::class,
                ColorFieldOption::make()
                    ->label(__('Text color'))
                    ->defaultValue('#253D4E')
            );

            return $form;
        });

        app('events')->listen(AdsLoading::class, function (AdsLoading $event) {
            $event->ads->loadMissing('metadata');
        });

        function display_ad(
            BaseModel|string $ads,
            string $class = '',
            $loop = null,
            ?Shortcode $shortcode = null
        ): ?string {
            if (! ($ads instanceof BaseModel)) {
                $ads = AdsManager::getData()
                    ->where('key', $ads)
                    ->first();
            }

            if (! $ads || ! $ads->image) {
                return null;
            }

            if ($ads->location &&
                $ads->location != 'not_set' &&
                view()->exists(Theme::getThemeNamespace() . '::partials.shortcodes.ads.' . $ads->location)) {
                return Theme::partial('shortcodes.ads.' . $ads->location, compact('ads', 'class', 'loop', 'shortcode'));
            }

            return Theme::partial('shortcodes.ads.item', compact('ads', 'class', 'loop', 'shortcode'));
        }

        function get_ads_keys_from_shortcode($shortcode): array
        {
            $keys = collect($shortcode->toArray())
                ->sortKeys()
                ->filter(function ($value, $key) use ($shortcode) {
                    return Str::startsWith($key, 'ads_') ||
                        ($shortcode->name == 'theme-ads' && Str::startsWith($key, 'key_'));
                });

            return array_filter($keys->toArray() + [$shortcode->ads]);
        }

        function display_ads(array $keys, ?string $style = '', ?Shortcode $shortcode = null): string
        {
            $keys = collect($keys);

            return Theme::partial('shortcodes.ads.items', compact('keys', 'style', 'shortcode'));
        }

        if (is_plugin_active('simple-slider')) {
            shortcode()->modifyAdminConfig('simple-slider', function ($form, $attributes) {
                if (! ($form instanceof ShortcodeForm)) {
                    return $form;
                }

                // Add cover image field
                $form->add(
                    'cover_image',
                    MediaImageField::class,
                    MediaImageFieldOption::make()
                        ->label(__('Cover image'))
                );

                // Add ads fields if plugin is active
                if (is_plugin_active('ads')) {
                    $ads = Ads::query()
                        ->wherePublished()
                        ->notExpired()
                        ->pluck('name', 'key')
                        ->toArray();

                    for ($i = 1; $i <= 2; $i++) {
                        $form->add(
                            'ads_' . $i,
                            SelectField::class,
                            SelectFieldOption::make()
                                ->label(__('Ad #:number', ['number' => $i]))
                                ->choices(['' => __('-- Select --')] + $ads)
                                ->allowClear()
                        );
                    }
                }

                // Add newsletter option if plugin is active
                if (is_plugin_active('newsletter')) {
                    $form->add(
                        'show_newsletter_form',
                        SelectField::class,
                        SelectFieldOption::make()
                            ->label(__('Show newsletter form?'))
                            ->choices([
                                'yes' => __('Yes'),
                                'no' => __('No'),
                            ])
                            ->defaultValue('no')
                    );
                }

                return $form;
            });
        }
    }

    if (is_plugin_active('blog')) {
        add_shortcode('featured-news', __('Featured News'), __('Featured News'), function (Shortcode $shortcode) {
            $limit = (int) $shortcode->limit ?: 4;
            $posts = app(PostInterface::class)->getFeatured($limit, ['slugable']);

            $style = $shortcode->style ?: 'list';

            return Theme::partial('shortcodes.featured-news', compact('shortcode', 'posts', 'style'));
        });

        shortcode()->setAdminConfig('featured-news', function (array $attributes) {
            return ShortcodeForm::createFromArray($attributes)
                ->add(
                    'title',
                    TextField::class,
                    TextFieldOption::make()
                        ->label(__('Title'))
                )
                ->add(
                    'limit',
                    NumberField::class,
                    NumberFieldOption::make()
                        ->label(__('Limit'))
                        ->defaultValue(4)
                )
                ->add(
                    'style',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Style'))
                        ->choices([
                            'list' => __('List'),
                            'grid' => __('Grid'),
                        ])
                        ->defaultValue('list')
                );
        });
    }

    if (is_plugin_active('contact')) {
        add_filter(CONTACT_FORM_TEMPLATE_VIEW, function () {
            return Theme::getThemeNamespace() . '::partials.shortcodes.contact-form';
        }, 120);
    }

    add_shortcode('our-offices', __('Our offices'), __('Our offices'), function () {
        return Theme::partial('shortcodes.our-offices');
    });

    shortcode()->setAdminConfig('our-offices', function (array $attributes) {
        return ShortcodeForm::createFromArray($attributes)
            ->add(
                'info',
                HtmlField::class,
                [
                    'html' => __('Configure our office locations and information in Admin panel → Appearance → Theme options → General → Contact info.'),
                ]
            );
    });

    add_shortcode('big-banner', __('Big banner'), __('Big banner'), function (Shortcode $shortcode) {
        return Theme::partial('shortcodes.big-banner', compact('shortcode'));
    });

    shortcode()->setAdminConfig('big-banner', function (array $attributes) {
        $form = ShortcodeForm::createFromArray($attributes)
            ->add(
                'title',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Title'))
            )
            ->add(
                'cover_image',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('Cover image'))
            );

        if (is_plugin_active('newsletter')) {
            $form->add(
                'show_newsletter_form',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(__('Show newsletter form?'))
                    ->choices([
                        'yes' => __('Yes'),
                        'no' => __('No'),
                    ])
                    ->defaultValue('yes')
            );
        }

        if (is_plugin_active('ecommerce')) {
            $form->add(
                'number_display_featured_categories',
                NumberField::class,
                NumberFieldOption::make()
                    ->label(__('Number of featured categories'))
                    ->defaultValue(3)
            );
        }

        return $form;
    });

    if (is_plugin_active('faq')) {
        add_shortcode('faqs', __('FAQs'), __('List of FAQs'), function (Shortcode $shortcode) {
            $params = [
                'condition' => [
                    'status' => BaseStatusEnum::PUBLISHED,
                ],
                'with' => [
                    'faqs' => function ($query): void {
                        $query->wherePublished();
                    },
                ],
                'order_by' => [
                    'faq_categories.order' => 'ASC',
                    'faq_categories.created_at' => 'DESC',
                ],
            ];

            if ($shortcode->category_id) {
                $params['condition']['id'] = $shortcode->category_id;
            }

            $categories = app(FaqCategoryInterface::class)->advancedGet($params);

            return Theme::partial('shortcodes.faqs', compact('categories'));
        });

        shortcode()->setAdminConfig('faqs', function (array $attributes) {
            $categories = FaqCategory::query()
                ->wherePublished()
                ->pluck('name', 'id')
                ->toArray();

            return ShortcodeForm::createFromArray($attributes)
                ->add(
                    'category_id',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(__('Category'))
                        ->choices(['' => __('All')] + $categories)
                );
        });
    }

    ShortcodeFacade::register('coming-soon', __('Coming Soon'), __('Coming Soon'), function (Shortcode $shortcode): string {
        try {
            $countdownTime = Carbon::parse($shortcode->countdown_time);
        } catch (Exception) {
            $countdownTime = null;
        }

        $form = null;

        if (is_plugin_active('newsletter')) {
            $form = NewsletterForm::create();
        }

        return Theme::partial('shortcodes.coming-soon.index', compact('shortcode', 'countdownTime', 'form'));
    });

    ShortcodeFacade::setAdminConfig('coming-soon', function (array $attributes): ShortcodeForm {
        return ShortcodeForm::createFromArray($attributes)
            ->add(
                'title',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Title'))
            )
            ->add(
                'countdown_time',
                'datetime',
                [
                    'label' => __('Countdown time'),
                    'default_value' => Carbon::now()->addDays(7)->format('Y-m-d H:i'),
                ]
            )
            ->add(
                'address',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Address'))
            )
            ->add(
                'hotline',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Hotline'))
            )
            ->add(
                'business_hours',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Business hours'))
            )
            ->add(
                'show_social_links',
                OnOffField::class,
                OnOffFieldOption::make()
                    ->label(__('Show social links'))
                    ->defaultValue(true)
            )
            ->add(
                'image',
                MediaImageField::class,
                MediaImageFieldOption::make()
                    ->label(__('Image'))
            );
    });
});
