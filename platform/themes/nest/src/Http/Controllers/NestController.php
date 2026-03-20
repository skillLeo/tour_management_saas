<?php

namespace Theme\Nest\Http\Controllers;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Ecommerce\Facades\Cart;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Repositories\Interfaces\ProductCategoryInterface;
use Botble\Ecommerce\Repositories\Interfaces\ProductInterface;
use Botble\Ecommerce\Services\Products\GetProductService;
use Botble\Theme\Facades\Theme;
use Botble\Theme\Http\Controllers\PublicController;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class NestController extends PublicController
{
    public function ajaxCart(Request $request, BaseHttpResponse $response)
    {
        if (! $request->ajax()) {
            return $response->setNextUrl(BaseHelper::getHomepageUrl());
        }

        return $response->setData([
            'count' => Cart::instance('cart')->count(),
            'html' => Theme::partial('cart-panel'),
        ]);
    }

    public function getQuickView(int|string $id, BaseHttpResponse $response)
    {
        $product = get_products(
            [
                'condition' => [
                    'ec_products.id' => $id,
                    'ec_products.status' => BaseStatusEnum::PUBLISHED,
                ],
                'take' => 1,
                'with' => [
                    'slugable',
                    'tags',
                    'tags.slugable',
                    'options' => function ($query) {
                        return $query->with('values');
                    },
                ],
            ]
        );

        if (! $product) {
            return $response->setNextUrl(route('public.index'));
        }

        [$productImages, $productVariation, $selectedAttrs] = EcommerceHelper::getProductVariationInfo($product);

        return $response->setData(
            Theme::partial('quick-view', compact('product', 'selectedAttrs', 'productImages', 'productVariation'))
        );
    }

    public function ajaxSearchProducts(
        Request $request,
        GetProductService $productService,
        BaseHttpResponse $response
    ) {
        $request->merge(['num' => 12]);

        $products = $productService->getProduct($request);

        $queries = $request->input();
        foreach ($queries as $key => $query) {
            if (! $query || $key == 'num' || (is_array($query) && ! Arr::get($query, 0))) {
                unset($queries[$key]);
            }
        }

        $total = $products->count();
        $message = $total != 1 ? __(':total Products found', compact('total')) : __(
            ':total Product found',
            compact('total')
        );

        return $response
            ->setData(Theme::partial('ajax-search-results', compact('products', 'queries')))
            ->setMessage($message);
    }

    public function ajaxGetProductsByCollection(int|string $id, Request $request, BaseHttpResponse $response)
    {
        $products = get_products_by_collections([
            'collections' => [
                'by' => 'id',
                'value_in' => [$id],
            ],
            'take' => $request->integer('limit') ?: 8,
            'with' => EcommerceHelper::withProductEagerLoadingRelations(),
        ]);

        $data = view(
            Theme::getThemeNamespace() . '::views.ecommerce.includes.product-items-loop',
            compact('products')
        )->render();

        return $response->setData($data);
    }

    public function ajaxGetProductsByCategory(
        int|string $id,
        Request $request,
        BaseHttpResponse $response,
        ProductInterface $productRepository,
        ProductCategoryInterface $productCategoryRepository
    ) {
        $category = $productCategoryRepository->getFirstBy(
            [
                'status' => BaseStatusEnum::PUBLISHED,
                'id' => $id,
            ],
            ['*'],
            [
                'activeChildren' => function ($query) {
                    return $query->limit(3);
                },
            ]
        );

        if (! $category) {
            return $response->setData([]);
        }

        $products = $productRepository->getProductsByCategories([
            'categories' => [
                'by' => 'id',
                'value_in' => array_merge([$category->id], $category->activeChildren->pluck('id')->all()),
            ],
            'take' => $request->integer('limit') ?: 8,
        ]);

        $data = view(
            Theme::getThemeNamespace() . '::views.ecommerce.includes.product-items-loop',
            compact('products')
        )->render();

        return $response->setData($data);
    }

    public function ajaxGetRelatedProducts(int|string $id, Request $request, BaseHttpResponse $response)
    {
        $product = Product::find($id);

        if (! $product) {
            return $response->setError()->setMessage(__('Product not found'));
        }

        $relatedProducts = get_related_products($product, $request->integer('limit'));

        if ($relatedProducts->isEmpty()) {
            return $response->setData('');
        }

        $data = view(
            Theme::getThemeNamespace() . '::views.ecommerce.includes.product-items-loop',
            ['products' => $relatedProducts, 'perRow' => 4]
        )->render();

        return $response->setData($data);
    }

    public function ajaxGetCrossSaleProducts(int|string $id, Request $request, BaseHttpResponse $response)
    {
        $product = Product::find($id);

        if (! $product) {
            return $response->setError()->setMessage(__('Product not found'));
        }

        $crossSaleProducts = get_cross_sale_products($product, $request->integer('limit'));

        if ($crossSaleProducts->isEmpty()) {
            return $response->setData('');
        }

        $data = view(
            Theme::getThemeNamespace() . '::views.ecommerce.includes.product-items-loop',
            ['products' => $crossSaleProducts, 'perRow' => 4]
        )->render();

        return $response->setData($data);
    }
}
