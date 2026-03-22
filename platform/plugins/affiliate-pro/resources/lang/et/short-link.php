<?php

return [
    'name' => 'Lühilingid',
    'short_link' => 'Lühilink',
    'short_links' => 'Lühilingid',
    'create' => 'Loo lühilink',
    'edit' => 'Muuda lühilinki',
    'delete' => 'Kustuta lühilink',
    'short_link_details' => 'Lühilingi üksikasjad',
    'untitled' => 'Nimetu link',

    // Form fields
    'affiliate' => 'Partner',
    'title' => 'Pealkiri',
    'title_placeholder' => 'Sisestage sellele lühilingile kirjeldav pealkiri',
    'short_code' => 'Lühikood',
    'short_code_placeholder' => 'Sisestage unikaalne lühikood (nt summer2024)',
    'short_code_help' => 'Seda kasutatakse URL-is: yoursite.com/go/[short-code]',
    'destination_url' => 'Siht-URL',
    'destination_url_help' => 'Täielik URL, kuhu kasutajad suunatakse',
    'product' => 'Toode',
    'product_help' => 'Valikuline: Seostage see lühilink konkreetse tootega parema jälgimise jaoks',
    'all_products' => 'Kõik tooted',
    'short_url' => 'Lühike URL',
    'clicks' => 'Klikid',
    'conversions' => 'Konversioonid',
    'conversion_rate' => 'Konversioonimäär',
    'total_clicks' => 'Kokku klikke',
    'total_conversions' => 'Kokku konversioone',
    'statistics' => 'Statistika',
    'actions' => 'Toimingud',
    'created_at' => 'Loodud',
    'updated_at' => 'Uuendatud',

    // Validation messages
    'affiliate_required' => 'Palun valige partner.',
    'affiliate_not_exists' => 'Valitud partnerit ei eksisteeri.',
    'short_code_required' => 'Lühikood on kohustuslik.',
    'short_code_unique' => 'See lühikood on juba võetud.',
    'short_code_alpha_dash' => 'Lühikood võib sisaldada ainult tähti, numbreid, kriipse ja alakriipse.',
    'destination_url_required' => 'Siht-URL on kohustuslik.',
    'destination_url_invalid' => 'Palun sisestage kehtiv URL.',
    'product_not_exists' => 'Valitud toodet ei eksisteeri.',

    // Actions
    'copy_url' => 'Kopeeri URL',
    'copy_short_url' => 'Kopeeri lühike URL',
    'test_link' => 'Testi linki',
    'visit_destination' => 'Külasta sihtkohta',
    'copied_to_clipboard' => 'URL kopeeritud lõikelauale!',
    'copy_failed' => 'URL-i kopeerimine ebaõnnestus. Palun proovige uuesti.',

    // UI sections
    'url_information' => 'URL-i teave',
    'affiliate_product_info' => 'Partneri ja toote teave',
    'quick_actions' => 'Kiirtoimingud',
    'performance' => 'Jõudlus',
    'product_link' => 'Toote link',
    'general_link' => 'Üldine link',

    // Performance indicators
    'excellent' => 'Suurepärane',
    'good' => 'Hea',
    'average' => 'Keskmine',
    'no_data' => 'Andmed puuduvad',

    'back' => 'Tagasi',

    // Status messages
    'affiliate_not_found' => 'Partnerit ei leitud',
    'short_link_not_found' => 'Lühilinki ei leitud',

    // Table headers
    'affiliate_column' => 'Partner',
    'title_column' => 'Pealkiri ja kood',
    'destination_column' => 'Sihtkoht',
    'short_url_column' => 'Lühike URL',
    'product_column' => 'Toode',
    'stats_column' => 'Statistika',
    'created_column' => 'Loodud',

    // Bulk actions
    'bulk_delete_confirm' => 'Kas olete kindel, et soovite neid lühilinke kustutada?',
    'bulk_delete_success' => 'Valitud lühilingid edukalt kustutatud.',

    // Permissions
    'permissions' => [
        'index' => 'Vaata lühilinke',
        'create' => 'Loo lühilinke',
        'edit' => 'Muuda lühilinke',
        'destroy' => 'Kustuta lühilinke',
    ],
];
