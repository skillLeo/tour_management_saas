<?php

return [
    'name' => 'მოკლე ბმულები',
    'short_link' => 'მოკლე ბმული',
    'short_links' => 'მოკლე ბმულები',
    'create' => 'მოკლე ბმულის შექმნა',
    'edit' => 'მოკლე ბმულის რედაქტირება',
    'delete' => 'მოკლე ბმულის წაშლა',
    'short_link_details' => 'მოკლე ბმულის დეტალები',
    'untitled' => 'უსათაურო ბმული',

    // Form fields
    'affiliate' => 'პარტნიორი',
    'title' => 'სათაური',
    'title_placeholder' => 'შეიყვანეთ აღწერითი სათაური ამ მოკლე ბმულისთვის',
    'short_code' => 'მოკლე კოდი',
    'short_code_placeholder' => 'შეიყვანეთ უნიკალური მოკლე კოდი (მაგ., summer2024)',
    'short_code_help' => 'ეს გამოყენებული იქნება URL-ში: yoursite.com/go/[short-code]',
    'destination_url' => 'დანიშნულების URL',
    'destination_url_help' => 'სრული URL, სადაც მომხმარებლები გადამისამართდებიან',
    'product' => 'პროდუქტი',
    'product_help' => 'არასავალდებულო: დაუკავშირეთ ეს მოკლე ბმული კონკრეტულ პროდუქტს უკეთესი თვალყურის დევნებისთვის',
    'all_products' => 'ყველა პროდუქტი',
    'short_url' => 'მოკლე URL',
    'clicks' => 'კლიკები',
    'conversions' => 'კონვერსიები',
    'conversion_rate' => 'კონვერსიის მაჩვენებელი',
    'total_clicks' => 'სულ კლიკები',
    'total_conversions' => 'სულ კონვერსიები',
    'statistics' => 'სტატისტიკა',
    'actions' => 'მოქმედებები',
    'created_at' => 'შექმნის თარიღი',
    'updated_at' => 'განახლების თარიღი',

    // Validation messages
    'affiliate_required' => 'გთხოვთ, აირჩიოთ პარტნიორი.',
    'affiliate_not_exists' => 'არჩეული პარტნიორი არ არსებობს.',
    'short_code_required' => 'მოკლე კოდი სავალდებულოა.',
    'short_code_unique' => 'ეს მოკლე კოდი უკვე გამოყენებულია.',
    'short_code_alpha_dash' => 'მოკლე კოდი შეიძლება შეიცავდეს მხოლოდ ასოებს, ციფრებს, ტირეებს და ქვედა ხაზებს.',
    'destination_url_required' => 'დანიშნულების URL სავალდებულოა.',
    'destination_url_invalid' => 'გთხოვთ, შეიყვანოთ სწორი URL.',
    'product_not_exists' => 'არჩეული პროდუქტი არ არსებობს.',

    // Actions
    'copy_url' => 'URL-ის კოპირება',
    'copy_short_url' => 'მოკლე URL-ის კოპირება',
    'test_link' => 'ბმულის ტესტირება',
    'visit_destination' => 'დანიშნულების ნახვა',
    'copied_to_clipboard' => 'URL დაკოპირდა!',
    'copy_failed' => 'URL-ის კოპირება ვერ მოხერხდა. გთხოვთ, სცადოთ ახლიდან.',

    // UI sections
    'url_information' => 'URL-ის ინფორმაცია',
    'affiliate_product_info' => 'პარტნიორისა და პროდუქტის ინფორმაცია',
    'quick_actions' => 'სწრაფი მოქმედებები',
    'performance' => 'მუშაობა',
    'product_link' => 'პროდუქტის ბმული',
    'general_link' => 'ზოგადი ბმული',

    // Performance indicators
    'excellent' => 'შესანიშნავი',
    'good' => 'კარგი',
    'average' => 'საშუალო',
    'no_data' => 'მონაცემები არ არის',

    'back' => 'უკან',

    // Status messages
    'affiliate_not_found' => 'პარტნიორი ვერ მოიძებნა',
    'short_link_not_found' => 'მოკლე ბმული ვერ მოიძებნა',

    // Table headers
    'affiliate_column' => 'პარტნიორი',
    'title_column' => 'სათაური და კოდი',
    'destination_column' => 'დანიშნულება',
    'short_url_column' => 'მოკლე URL',
    'product_column' => 'პროდუქტი',
    'stats_column' => 'სტატისტიკა',
    'created_column' => 'შექმნილია',

    // Bulk actions
    'bulk_delete_confirm' => 'დარწმუნებული ხართ, რომ გსურთ ამ მოკლე ბმულების წაშლა?',
    'bulk_delete_success' => 'არჩეული მოკლე ბმულები წარმატებით წაიშალა.',

    // Permissions
    'permissions' => [
        'index' => 'მოკლე ბმულების ნახვა',
        'create' => 'მოკლე ბმულების შექმნა',
        'edit' => 'მოკლე ბმულების რედაქტირება',
        'destroy' => 'მოკლე ბმულების წაშლა',
    ],
];
