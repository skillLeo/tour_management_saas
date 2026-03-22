<?php

return [
    'name' => 'शॉर्ट लिंक',
    'short_link' => 'शॉर्ट लिंक',
    'short_links' => 'शॉर्ट लिंक',
    'create' => 'शॉर्ट लिंक बनाएं',
    'edit' => 'शॉर्ट लिंक संपादित करें',
    'delete' => 'शॉर्ट लिंक हटाएं',
    'short_link_details' => 'शॉर्ट लिंक विवरण',
    'untitled' => 'शीर्षकहीन लिंक',

    // Form fields
    'affiliate' => 'सहयोगी',
    'title' => 'शीर्षक',
    'title_placeholder' => 'इस शॉर्ट लिंक के लिए एक विवरणात्मक शीर्षक दर्ज करें',
    'short_code' => 'शॉर्ट कोड',
    'short_code_placeholder' => 'विशिष्ट शॉर्ट कोड दर्ज करें (उदाहरण, summer2024)',
    'short_code_help' => 'यह URL में उपयोग किया जाएगा: yoursite.com/go/[short-code]',
    'destination_url' => 'गंतव्य URL',
    'destination_url_help' => 'पूर्ण URL जहां उपयोगकर्ताओं को पुनर्निर्देशित किया जाएगा',
    'product' => 'उत्पाद',
    'product_help' => 'वैकल्पिक: बेहतर ट्रैकिंग के लिए इस शॉर्ट लिंक को किसी विशिष्ट उत्पाद से लिंक करें',
    'all_products' => 'सभी उत्पाद',
    'short_url' => 'शॉर्ट URL',
    'clicks' => 'क्लिक',
    'conversions' => 'रूपांतरण',
    'conversion_rate' => 'रूपांतरण दर',
    'total_clicks' => 'कुल क्लिक',
    'total_conversions' => 'कुल रूपांतरण',
    'statistics' => 'आंकड़े',
    'actions' => 'कार्रवाई',
    'created_at' => 'बनाया गया',
    'updated_at' => 'अपडेट किया गया',

    // Validation messages
    'affiliate_required' => 'कृपया एक सहयोगी चुनें।',
    'affiliate_not_exists' => 'चयनित सहयोगी मौजूद नहीं है।',
    'short_code_required' => 'शॉर्ट कोड आवश्यक है।',
    'short_code_unique' => 'यह शॉर्ट कोड पहले से लिया गया है।',
    'short_code_alpha_dash' => 'शॉर्ट कोड में केवल अक्षर, संख्या, डैश और अंडरस्कोर हो सकते हैं।',
    'destination_url_required' => 'गंतव्य URL आवश्यक है।',
    'destination_url_invalid' => 'कृपया एक मान्य URL दर्ज करें।',
    'product_not_exists' => 'चयनित उत्पाद मौजूद नहीं है।',

    // Actions
    'copy_url' => 'URL कॉपी करें',
    'copy_short_url' => 'शॉर्ट URL कॉपी करें',
    'test_link' => 'लिंक परीक्षण करें',
    'visit_destination' => 'गंतव्य पर जाएं',
    'copied_to_clipboard' => 'URL क्लिपबोर्ड पर कॉपी किया गया!',
    'copy_failed' => 'URL कॉपी करने में विफल। कृपया पुनः प्रयास करें।',

    // UI sections
    'url_information' => 'URL जानकारी',
    'affiliate_product_info' => 'सहयोगी और उत्पाद जानकारी',
    'quick_actions' => 'त्वरित कार्रवाई',
    'performance' => 'प्रदर्शन',
    'product_link' => 'उत्पाद लिंक',
    'general_link' => 'सामान्य लिंक',

    // Performance indicators
    'excellent' => 'उत्कृष्ट',
    'good' => 'अच्छा',
    'average' => 'औसत',
    'no_data' => 'कोई डेटा नहीं',

    'back' => 'वापस',

    // Status messages
    'affiliate_not_found' => 'सहयोगी नहीं मिला',
    'short_link_not_found' => 'शॉर्ट लिंक नहीं मिला',

    // Table headers
    'affiliate_column' => 'सहयोगी',
    'title_column' => 'शीर्षक और कोड',
    'destination_column' => 'गंतव्य',
    'short_url_column' => 'शॉर्ट URL',
    'product_column' => 'उत्पाद',
    'stats_column' => 'आंकड़े',
    'created_column' => 'बनाया गया',

    // Bulk actions
    'bulk_delete_confirm' => 'क्या आप वाकई इन शॉर्ट लिंक को हटाना चाहते हैं?',
    'bulk_delete_success' => 'चयनित शॉर्ट लिंक सफलतापूर्वक हटाए गए।',

    // Permissions
    'permissions' => [
        'index' => 'शॉर्ट लिंक देखें',
        'create' => 'शॉर्ट लिंक बनाएं',
        'edit' => 'शॉर्ट लिंक संपादित करें',
        'destroy' => 'शॉर्ट लिंक हटाएं',
    ],
];
