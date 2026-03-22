@include(Theme::getThemeNamespace() . '::views.ecommerce.products', [
    'filterURL' => $tag->url,
    'pageName' => $tag->name,
    'pageDescription' => $tag->content ? \Botble\Shortcode\Facades\Shortcode::compile($tag->content, true)->toHtml() : $tag->description,
])
