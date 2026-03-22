<?php

return [
    'name' => 'Mga Maikling Link',
    'short_link' => 'Maiikling Link',
    'short_links' => 'Mga Maikling Link',
    'create' => 'Gumawa ng Maikling Link',
    'edit' => 'I-edit ang Maikling Link',
    'delete' => 'Tanggalin ang Maikling Link',
    'short_link_details' => 'Mga Detalye ng Maikling Link',
    'untitled' => 'Walang Pamagat na Link',

    // Form fields
    'affiliate' => 'Kaakibat',
    'title' => 'Pamagat',
    'title_placeholder' => 'Maglagay ng naglalarawang pamagat para sa maikling link na ito',
    'short_code' => 'Maikling Code',
    'short_code_placeholder' => 'Maglagay ng natatanging maikling code (e.g., summer2024)',
    'short_code_help' => 'Ito ay gagamitin sa URL: yoursite.com/go/[short-code]',
    'destination_url' => 'URL ng destinasyon',
    'destination_url_help' => 'Ang buong URL kung saan ire-redirect ang mga users',
    'product' => 'Produkto',
    'product_help' => 'Opsyonal: I-link ang maikling link na ito sa partikular na produkto para sa mas magandang pagsubaybay',
    'all_products' => 'Lahat ng Produkto',
    'short_url' => 'Maikling URL',
    'clicks' => 'Mga Pag-click',
    'conversions' => 'Mga Conversion',
    'conversion_rate' => 'Antas ng Conversion',
    'total_clicks' => 'Kabuuang Mga Pag-click',
    'total_conversions' => 'Kabuuang Conversion',
    'statistics' => 'Mga Estadistika',
    'actions' => 'Mga Aksyon',
    'created_at' => 'Ginawa Noong',
    'updated_at' => 'Na-update Noong',

    // Validation messages
    'affiliate_required' => 'Pakipili ang kaakibat.',
    'affiliate_not_exists' => 'Ang napiling kaakibat ay hindi umiiral.',
    'short_code_required' => 'Kailangan ang maikling code.',
    'short_code_unique' => 'Ang maikling code na ito ay nagamit na.',
    'short_code_alpha_dash' => 'Ang maikling code ay maaari lamang maglaman ng mga titik, numero, gitling, at underscore.',
    'destination_url_required' => 'Kailangan ang destination URL.',
    'destination_url_invalid' => 'Pakiusap na maglagay ng wastong URL.',
    'product_not_exists' => 'Ang napiling produkto ay hindi umiiral.',

    // Actions
    'copy_url' => 'Kopyahin ang URL',
    'copy_short_url' => 'Kopyahin ang Maikling URL',
    'test_link' => 'Subukan ang Link',
    'visit_destination' => 'Bisitahin ang Destinasyon',
    'copied_to_clipboard' => 'URL ay nakopya na sa clipboard!',
    'copy_failed' => 'Hindi nakopya ang URL. Pakisubukan muli.',

    // UI sections
    'url_information' => 'Impormasyon ng URL',
    'affiliate_product_info' => 'Impormasyon ng Kaakibat at Produkto',
    'quick_actions' => 'Mabibilis na Aksyon',
    'performance' => 'Pagganap',
    'product_link' => 'Link ng produkto',
    'general_link' => 'Pangkalahatang Link',

    // Performance indicators
    'excellent' => 'Napakahusay',
    'good' => 'Maganda',
    'average' => 'Karaniwan',
    'no_data' => 'Walang Data',

    'back' => 'Bumalik',

    // Status messages
    'affiliate_not_found' => 'Hindi nahanap ang kaakibat',
    'short_link_not_found' => 'Hindi nahanap ang maikling link',

    // Table headers
    'affiliate_column' => 'Kaakibat',
    'title_column' => 'Pamagat at Code',
    'destination_column' => 'Destinasyon',
    'short_url_column' => 'Maikling URL',
    'product_column' => 'Produkto',
    'stats_column' => 'Mga Estadistika',
    'created_column' => 'Ginawa',

    // Bulk actions
    'bulk_delete_confirm' => 'Sigurado ka bang nais mong tanggalin ang mga maikling link na ito?',
    'bulk_delete_success' => 'Matagumpay na natanggal ang mga napiling maikling link.',

    // Permissions
    'permissions' => [
        'index' => 'Tingnan ang mga maikling link',
        'create' => 'Gumawa ng mga maikling link',
        'edit' => 'I-edit ang mga maikling link',
        'destroy' => 'Tanggalin ang mga maikling link',
    ],
];
