<?php

namespace Database\Seeders;

use Botble\Base\Facades\Html;
use Botble\Base\Facades\MetaBox;
use Botble\Base\Supports\BaseSeeder;
use Botble\Page\Models\Page;
use Botble\Slug\Facades\SlugHelper;
use Illuminate\Support\Arr;

class PageSeeder extends BaseSeeder
{
    public function run(): void
    {

        $themeAds = Html::tag(
            'div',
            '[theme-ads ads_1="IZ6WU8KUALYD" ads_2="ILSFJVYFGCPZ" ads_3="ILSDKVYFGXPH"][/theme-ads]'
        );
        $popularProducts = Html::tag(
            'div',
            '[popular-products title="Popular Products" per_row="5" limit="10" enable_lazy_loading="yes"][/popular-products]'
        );
        $bestFlashSale = Html::tag(
            'div',
            '[best-flash-sale title="Daily Best Sells" flash_sale_id="5" ads="IZ6WU8KUALYG"][/best-flash-sale]'
        );
        $topProductsGroup = Html::tag(
            'div',
            '[top-products-group tabs="top-selling,trending-products,recent-added,top-rated" top_selling_in_days="365" enable_lazy_loading="yes"][/top-products-group]'
        );
        $flashSale = Html::tag(
            'div',
            '[flash-sale flash_sale_1="1" flash_sale_2="2" flash_sale_3="3" flash_sale_4="4" title="Deals Of The Day" flash_sale_popup_id="1"][/flash-sale]'
        );
        $simpleSlider1 = Html::tag(
            'div',
            '[simple-slider key="home-slider-1" show_newsletter_form="yes"][/simple-slider]'
        );
        $productCategories = Html::tag('div', '[product-categories title="Shop by Categories"][/product-categories]');
        $simpleSlider2 = Html::tag(
            'div',
            '[simple-slider key="home-slider-2" ads_1="IZ6WU8KUALYH" show_newsletter_form="yes" cover_image="sliders/banner-1.png"][/simple-slider]'
        );
        $featuredProductCategories = Html::tag(
            'div',
            '[featured-product-categories title="Top Categories"][/featured-product-categories]'
        );
        $simpleSlider5 = Html::tag(
            'div',
            '[simple-slider key="home-slider-5" ads_1="IZ6WU8KUALYJ" ads_2="IZ6WU8KUALYK" show_newsletter_form="yes"][/simple-slider]'
        );
        $themeAds2 = Html::tag(
            'div',
            '[theme-ads ads_1="IZ6WU8KUALYL" ads_2="IZ6WU8KUALYM" ads_3="IZ6WU8KUALYN" ads_4="IZ6WU8KUALYO" style="style-5"][/theme-ads]'
        );
        $bigBanner = Html::tag(
            'div',
            '[big-banner cover_image="general/home-6.jpeg" show_newsletter_form="yes" number_display_featured_categories="4" title="What are you looking for?"][/big-banner]'
        );
        $trendingProducts = Html::tag(
            'div',
            '[trending-products title="Trending items" per_row="5" limit="20"][/trending-products]'
        );
        $testimonials = Html::tag(
            'div',
            '[testimonials title="What our Clients say" subtitle="Customers Review" testimonial_ids="1,2,3,4"][/testimonials]'
        );
        $recentlyViewedProducts = Html::tag(
            'div',
            '[recently-viewed-products title="Your Recently Viewed"][/recently-viewed-products]'
        );

        $pages = [
            [
                'name' => 'Homepage',
                'content' =>
                    $simpleSlider1 .
                    $featuredProductCategories .
                    $themeAds .
                    $popularProducts .
                    $bestFlashSale .
                    $flashSale .
                    $topProductsGroup .
                    $testimonials .
                    $recentlyViewedProducts,
                'template' => 'homepage',
            ],
            [
                'name' => 'Homepage 2',
                'content' =>
                    $simpleSlider2 .
                    $themeAds .
                    $popularProducts .
                    $bestFlashSale .
                    $flashSale .
                    $topProductsGroup .
                    $productCategories .
                    $testimonials .
                    $recentlyViewedProducts,
                'template' => 'homepage',
            ],
            [
                'name' => 'Homepage 3',
                'content' =>
                    $simpleSlider1 .
                    $popularProducts .
                    $flashSale .
                    $themeAds .
                    $productCategories .
                    $topProductsGroup .
                    $recentlyViewedProducts,
                'template' => 'homepage',
            ],
            [
                'name' => 'Homepage 4',
                'content' =>
                    Html::tag(
                        'div',
                        '[simple-slider key="home-slider-4" show_newsletter_form="yes"][/simple-slider]'
                    ) .
                    $popularProducts .
                    $flashSale .
                    $themeAds .
                    $productCategories .
                    $topProductsGroup .
                    $recentlyViewedProducts,
                'template' => 'homepage',
            ],
            [
                'name' => 'Blog',
                'content' => Html::tag('p', '---'),
                'template' => 'blog-grid',
            ],
            [
                'name' => 'Contact',
                'content' => Html::tag('p', '[google-map]502 New Street, Brighton VIC, Australia[/google-map]') .
                    Html::tag('p', '[our-offices][/our-offices]') .
                    Html::tag('p', '[contact-form][/contact-form]'),
            ],
            [
                'name' => 'About us',
                'content' =>
                    Html::tag('p', 'Welcome to our store! We are a leading e-commerce platform dedicated to providing high-quality products at competitive prices. Our mission is to make shopping easy, convenient, and enjoyable for everyone. With a wide selection of products across various categories, we strive to meet all your needs under one roof.') .
                    Html::tag('p', 'Founded with a passion for excellence, we have grown from a small startup to a trusted online marketplace serving customers worldwide. Our team works tirelessly to source the best products, ensure quality standards, and deliver exceptional customer service that keeps our customers coming back.') .
                    Html::tag('p', 'We believe in building lasting relationships with our customers through transparency, integrity, and commitment to satisfaction. Every product in our catalog is carefully selected to meet our high standards. We partner with reliable suppliers and manufacturers to bring you authentic, quality items at the best prices.') .
                    Html::tag('p', 'Thank you for choosing us as your shopping destination. We are committed to making your experience seamless and rewarding. If you have any questions or feedback, our dedicated support team is always here to help. Happy shopping!'),
                'template' => 'right-sidebar',
            ],
            [
                'name' => 'Cookie Policy',
                'content' => Html::tag('h3', 'EU Cookie Consent') .
                    Html::tag(
                        'p',
                        'To use this website we are using Cookies and collecting some data. To be compliant with the EU GDPR we give you to choose if you allow us to use certain Cookies and to collect some Data.'
                    ) .
                    Html::tag('h4', 'Essential Data') .
                    Html::tag(
                        'p',
                        'The Essential Data is needed to run the Site you are visiting technically. You can not deactivate them.'
                    ) .
                    Html::tag(
                        'p',
                        '- Session Cookie: PHP uses a Cookie to identify user sessions. Without this Cookie the Website is not working.'
                    ) .
                    Html::tag(
                        'p',
                        '- XSRF-Token Cookie: Laravel automatically generates a CSRF "token" for each active user session managed by the application. This token is used to verify that the authenticated user is the one actually making the requests to the application.'
                    ),
            ],
            [
                'name' => 'Terms & Conditions',
                'content' =>
                    Html::tag('p', 'Welcome to our website. By accessing and using this website, you accept and agree to be bound by these Terms and Conditions. Please read them carefully before using our services. If you do not agree to these terms, please do not use our website or services.') .
                    Html::tag('p', 'All content on this website, including text, graphics, logos, images, and software, is the property of our company and is protected by copyright laws. You may not reproduce, distribute, or create derivative works without our express written permission. Any unauthorized use may violate copyright, trademark, and other laws.') .
                    Html::tag('p', 'We reserve the right to modify these terms at any time without prior notice. Your continued use of the website following any changes constitutes acceptance of the new terms. It is your responsibility to review these terms periodically to stay informed of any updates.') .
                    Html::tag('p', 'We are not liable for any damages arising from your use of this website or any linked sites. All products and services are provided "as is" without warranties of any kind. By using our services, you agree to indemnify and hold us harmless from any claims or damages.'),
            ],
            [
                'name' => 'Returns & Exchanges',
                'content' =>
                    Html::tag('p', 'We want you to be completely satisfied with your purchase. If for any reason you are not happy with your order, we offer a hassle-free return and exchange policy within 30 days of delivery. Items must be unused, in their original packaging, and in the same condition as received.') .
                    Html::tag('p', 'To initiate a return or exchange, please contact our customer service team with your order number and reason for return. We will provide you with a return authorization and instructions for shipping the item back to us. Please note that shipping costs for returns are the responsibility of the customer unless the item is defective or incorrect.') .
                    Html::tag('p', 'Once we receive your returned item and verify its condition, we will process your refund or exchange within 5-7 business days. Refunds will be credited to your original payment method. For exchanges, we will ship the replacement item as soon as the return is processed.') .
                    Html::tag('p', 'Some items are not eligible for returns, including perishable goods, personalized items, and clearance products. Please review the product description for any specific return restrictions before making your purchase. Contact our support team if you have any questions about our return policy.'),
            ],
            [
                'name' => 'Shipping & Delivery',
                'content' =>
                    Html::tag('p', 'We offer fast and reliable shipping to customers worldwide. Standard shipping typically takes 5-7 business days for domestic orders and 10-14 business days for international orders. Express shipping options are available at checkout for faster delivery.') .
                    Html::tag('p', 'All orders are processed within 1-2 business days. You will receive a confirmation email with tracking information once your order has shipped. Please ensure your shipping address is correct at checkout, as we cannot modify addresses after an order has been placed.') .
                    Html::tag('p', 'Shipping costs are calculated based on order weight, dimensions, and destination. Free shipping is available on orders over a certain amount - check our current promotions for details. Please note that import duties and taxes for international orders are the responsibility of the customer.') .
                    Html::tag('p', 'We carefully package all items to ensure they arrive in perfect condition. If your package arrives damaged, please contact us immediately with photos of the damage. We will work with you to resolve the issue promptly and ensure your satisfaction.'),
            ],
            [
                'name' => 'Privacy Policy',
                'content' =>
                    Html::tag('p', 'Your privacy is important to us. This Privacy Policy explains how we collect, use, and protect your personal information when you visit our website or make a purchase. We are committed to safeguarding your data and ensuring transparency in our practices.') .
                    Html::tag('p', 'We collect information you provide directly, such as your name, email address, shipping address, and payment details when you create an account or place an order. We also automatically collect certain information about your device and browsing activity to improve our services and personalize your experience.') .
                    Html::tag('p', 'Your information is used to process orders, communicate with you, and improve our website and services. We do not sell or rent your personal information to third parties. We may share your information with service providers who assist us in operating our business, but only to the extent necessary to perform their functions.') .
                    Html::tag('p', 'We implement industry-standard security measures to protect your information from unauthorized access, alteration, or disclosure. However, no method of transmission over the internet is completely secure. By using our website, you acknowledge and accept these risks. Contact us if you have any questions about our privacy practices.'),
            ],
            [
                'name' => 'Blog List',
                'content' => Html::tag('p', '[blog-posts paginate="12"][/blog-posts]'),
                'template' => 'blog-list',
            ],
            [
                'name' => 'Blog Big',
                'content' => Html::tag('p', '[blog-posts paginate="12"][/blog-posts]'),
                'template' => 'blog-big',
            ],
            [
                'name' => 'Blog Wide',
                'content' => Html::tag('p', '[blog-posts paginate="12"][/blog-posts]'),
                'template' => 'blog-wide',
            ],
            [
                'name' => 'Homepage 5',
                'content' =>
                    $simpleSlider5 .
                    $featuredProductCategories .
                    $themeAds .
                    $popularProducts .
                    $themeAds2 .
                    $bestFlashSale .
                    $flashSale .
                    $topProductsGroup .
                    $recentlyViewedProducts,
                'template' => 'homepage',
                'header_style' => 'header-style-5',
            ],
            [
                'name' => 'Homepage 6',
                'content' =>
                    $bigBanner .
                    $trendingProducts .
                    $flashSale .
                    $topProductsGroup .
                    $recentlyViewedProducts,
                'template' => 'homepage',
                'header_style' => 'header-style-5',
            ],
            [
                'name' => 'Faq',
                'content' => Html::tag('div', '[faqs][/faqs]'),
            ],
            [
                'name' => 'Product Categories',
                'content' =>
                    Html::tag('div', '[ecommerce-categories category_ids="1,2,3,4,5,6,7,8" style="grid" title="Browse Categories" show_products_count="yes"][/ecommerce-categories]'),
                'template' => 'full-width',
            ],
            [
                'name' => 'Coming Soon',
                'content' => '[coming-soon title="Get Notified When We Launch" countdown_time="' . $this->now()->addDays(200)->toDateString() . '" address=" 58 Street Commercial Road Fratton, Australia" hotline="+123456789" business_hours="Mon – Sat: 8 am – 5 pm, Sunday: CLOSED" show_social_links="1" image="general/contact-img.jpg"][/coming-soon]',
                'template' => 'without-layout',
            ],
        ];

        Page::query()->truncate();

        foreach ($pages as $item) {
            $item['user_id'] = 1;

            if (! isset($item['template'])) {
                $item['template'] = 'default';
            }

            $page = Page::query()->create(
                Arr::except(
                    $item,
                    ['header_style', 'expanding_product_categories_on_the_homepage']
                )
            );

            $headerStyle = $item['header_style'] ?? null;
            if ($headerStyle) {
                MetaBox::saveMetaBoxData($page, 'header_style', $headerStyle);
            }

            if (isset($item['expanding_product_categories_on_the_homepage'])) {
                MetaBox::saveMetaBoxData(
                    $page,
                    'expanding_product_categories_on_the_homepage',
                    $item['expanding_product_categories_on_the_homepage']
                );
            }

            SlugHelper::createSlug($page);
        }
    }
}
