<?php

return [
    'name' => 'Īsās saites',
    'short_link' => 'Īsā saite',
    'short_links' => 'Īsās saites',
    'create' => 'Izveidot īsu saiti',
    'edit' => 'Rediģēt īsu saiti',
    'delete' => 'Dzēst īsu saiti',
    'short_link_details' => 'Īsās saites informācija',
    'untitled' => 'Bez nosaukuma saite',

    // Form fields
    'affiliate' => 'Partneris',
    'title' => 'Nosaukums',
    'title_placeholder' => 'Ievadiet aprakstošu nosaukumu šai īsajai saitei',
    'short_code' => 'Īsais kods',
    'short_code_placeholder' => 'Ievadiet unikālu īso kodu (piemēram, vasara2024)',
    'short_code_help' => 'Tas tiks izmantots URL: jūsuvietne.lv/go/[īsais-kods]',
    'destination_url' => 'Galamērķa URL',
    'destination_url_help' => 'Pilnais URL, uz kuru lietotāji tiks novirzīti',
    'product' => 'Produkts',
    'product_help' => 'Neobligāti: saistiet šo īso saiti ar konkrētu produktu labākai izsekošanai',
    'all_products' => 'Visi produkti',
    'short_url' => 'Īss URL',
    'clicks' => 'Klikšķi',
    'conversions' => 'Konversijas',
    'conversion_rate' => 'Konversijas līmenis',
    'total_clicks' => 'Kopējie klikšķi',
    'total_conversions' => 'Kopējās konversijas',
    'statistics' => 'Statistika',
    'actions' => 'Darbības',
    'created_at' => 'Izveidots',
    'updated_at' => 'Atjaunināts',

    // Validation messages
    'affiliate_required' => 'Lūdzu, izvēlieties partneri.',
    'affiliate_not_exists' => 'Izvēlētais partneris neeksistē.',
    'short_code_required' => 'Īsais kods ir obligāts.',
    'short_code_unique' => 'Šis īsais kods jau ir aizņemts.',
    'short_code_alpha_dash' => 'Īsais kods var saturēt tikai burtus, ciparus, domuzīmes un pasvītrojumus.',
    'destination_url_required' => 'Galamērķa URL ir obligāts.',
    'destination_url_invalid' => 'Lūdzu, ievadiet derīgu URL.',
    'product_not_exists' => 'Izvēlētais produkts neeksistē.',

    // Actions
    'copy_url' => 'Kopēt URL',
    'copy_short_url' => 'Kopēt īso URL',
    'test_link' => 'Testēt saiti',
    'visit_destination' => 'Apmeklēt galamērķi',
    'copied_to_clipboard' => 'URL nokopēts starpliktuvē!',
    'copy_failed' => 'Neizdevās nokopēt URL. Lūdzu, mēģiniet vēlreiz.',

    // UI sections
    'url_information' => 'URL informācija',
    'affiliate_product_info' => 'Partnera un produkta informācija',
    'quick_actions' => 'Ātrās darbības',
    'performance' => 'Veiktspēja',
    'product_link' => 'Produkta saite',
    'general_link' => 'Vispārēja saite',

    // Performance indicators
    'excellent' => 'Izcili',
    'good' => 'Labi',
    'average' => 'Vidēji',
    'no_data' => 'Nav datu',

    'back' => 'Atpakaļ',

    // Status messages
    'affiliate_not_found' => 'Partneris nav atrasts',
    'short_link_not_found' => 'Īsā saite nav atrasta',

    // Table headers
    'affiliate_column' => 'Partneris',
    'title_column' => 'Nosaukums un kods',
    'destination_column' => 'Galamērķis',
    'short_url_column' => 'Īss URL',
    'product_column' => 'Produkts',
    'stats_column' => 'Statistika',
    'created_column' => 'Izveidots',

    // Bulk actions
    'bulk_delete_confirm' => 'Vai tiešām vēlaties dzēst šīs īsās saites?',
    'bulk_delete_success' => 'Izvēlētās īsās saites ir veiksmīgi dzēstas.',

    // Permissions
    'permissions' => [
        'index' => 'Skatīt īsās saites',
        'create' => 'Izveidot īsās saites',
        'edit' => 'Rediģēt īsās saites',
        'destroy' => 'Dzēst īsās saites',
    ],
];
