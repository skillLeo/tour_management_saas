@include('plugins/ecommerce::themes.includes.product-price', [
    'product' => $product ?? null,
    'priceWrapperClassName' => 'product-price',
    'priceClassName' => '',
    'priceOriginalWrapperClassName' => '',
    'priceOriginalClassName' => 'old-price',
    'priceFormatted' => $priceFormatted ?? null,
])
