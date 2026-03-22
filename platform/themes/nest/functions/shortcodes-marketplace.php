<?php

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Marketplace\Models\Store;
use Botble\Shortcode\Compilers\Shortcode as ShortcodeCompiler;
use Botble\Shortcode\Facades\Shortcode;
use Botble\Shortcode\Forms\ShortcodeForm;
use Botble\Shortcode\ShortcodeField;
use Botble\Theme\Facades\Theme;
use Illuminate\Support\Arr;

if (is_plugin_active('marketplace')) {
    add_shortcode('marketplace-stores', __('Marketplace Stores'), __('Marketplace Stores'), function (ShortcodeCompiler $shortcode) {
        $storeIds = Shortcode::fields()->getIds('stores', $shortcode);

        if (empty($storeIds)) {
            return null;
        }

        $layout = $shortcode->layout ?: theme_option('store_list_layout');

        $layout = $layout && in_array($layout, array_keys(get_store_list_layouts())) ? $layout : 'grid';

        $with = ['slugable'];
        if (EcommerceHelper::isReviewEnabled()) {
            $with['reviews'] = function ($query): void {
                $query->where([
                    'ec_products.status' => BaseStatusEnum::PUBLISHED,
                    'ec_reviews.status' => BaseStatusEnum::PUBLISHED,
                ]);
            };
        }

        $stores = Store::query()
            ->wherePublished()
            ->whereIn('id', $storeIds)
            ->with($with)
            ->withCount([
                'products' => function ($query): void {
                    $query->wherePublished();
                },
            ])
            ->orderByDesc('created_at')
            ->get();

        return Theme::partial('shortcodes.marketplace.stores', compact('shortcode', 'layout', 'stores'));
    });

    shortcode()->setAdminConfig('marketplace-stores', function (array $attributes) {
        $stores = Store::query()
            ->wherePublished()
            ->orderBy('name')
            ->pluck('name', 'id')
            ->all();

        return ShortcodeForm::createFromArray($attributes)
            ->add(
                'title',
                TextField::class,
                TextFieldOption::make()
                    ->label(__('Title'))
            )
            ->add(
                'stores',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(__('Stores'))
                    ->choices($stores)
                    ->multiple()
                    ->searchable()
                    ->selected(ShortcodeField::parseIds(Arr::get($attributes, 'stores')))
            )
            ->add(
                'layout',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(__('Layout'))
                    ->choices(get_store_list_layouts())
                    ->defaultValue(theme_option('store_list_layout', 'grid'))
            );
    });
}
