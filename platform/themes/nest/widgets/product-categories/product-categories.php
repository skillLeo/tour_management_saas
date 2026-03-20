<?php

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Ecommerce\Facades\ProductCategoryHelper;
use Botble\Ecommerce\Models\ProductCategory;
use Botble\Support\Services\Cache\Cache;
use Botble\Widget\AbstractWidget;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProductCategoriesWidget extends AbstractWidget
{
    public function __construct()
    {
        parent::__construct([
            'name' => __('Product Categories'),
            'description' => __('Widget display product categories'),
            'number_display' => 10,
            'categories' => [],
        ]);
    }

    public function data(): array|Collection
    {
        if (! is_plugin_active('ecommerce')) {
            return [
                'categories' => collect(),
            ];
        }

        $categoryIds = $this->getConfig('categories');

        $categories = ProductCategory::query()
            ->where('is_featured', true)
            ->wherePublished()
            ->when($categoryIds, fn (Builder $query) => $query->whereIn('id', $categoryIds))
            ->orderBy('order')
            ->orderByDesc('created_at')
            ->with(['slugable', 'activeChildren'])
            ->limit((int) $this->getConfig('number_display', 10) ?: 10)
            ->get();

        $this->preloadProductCounts($categories);

        return compact('categories');
    }

    protected function preloadProductCounts(Collection $categories): void
    {
        if ($categories->isEmpty()) {
            return;
        }

        $categoryIdsMap = [];

        foreach ($categories as $category) {
            $childIds = ProductCategory::getChildrenIds($category->activeChildren);
            $childIds[] = $category->id;
            $categoryIdsMap[$category->id] = $childIds;
        }

        $allCategoryIds = collect($categoryIdsMap)->flatten()->unique()->values()->all();

        $productCounts = DB::table('ec_product_category_product')
            ->join('ec_products', 'ec_product_category_product.product_id', '=', 'ec_products.id')
            ->whereIn('category_id', $allCategoryIds)
            ->where('ec_products.status', BaseStatusEnum::PUBLISHED)
            ->where('ec_products.is_variation', 0)
            ->select('category_id', DB::raw('COUNT(DISTINCT product_id) as count'))
            ->groupBy('category_id')
            ->pluck('count', 'category_id');

        $cache = Cache::make(ProductCategory::class);

        foreach ($categories as $category) {
            $relevantIds = $categoryIdsMap[$category->id] ?? [];
            $count = 0;

            foreach ($relevantIds as $categoryId) {
                $count += (int) ($productCounts[$categoryId] ?? 0);
            }

            $cacheKey = 'count_all_products_' . $category->id . app()->getLocale();
            $cache->put($cacheKey, $count, Carbon::now()->addHours(2));
        }
    }

    public function adminConfig(): array
    {
        if (! is_plugin_active('ecommerce')) {
            return [
                'categories' => collect(),
            ];
        }

        return [
            'categories' => ProductCategoryHelper::getActiveTreeCategories(),
        ];
    }
}
