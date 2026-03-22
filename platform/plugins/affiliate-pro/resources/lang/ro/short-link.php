<?php

return [
    'name' => 'Link-uri scurte',
    'short_link' => 'Link scurt',
    'short_links' => 'Link-uri scurte',
    'create' => 'Creează link scurt',
    'edit' => 'Editează link scurt',
    'delete' => 'Șterge link scurt',
    'short_link_details' => 'Detalii link scurt',
    'untitled' => 'Link fără titlu',

    // Form fields
    'affiliate' => 'Afiliat',
    'title' => 'Titlu',
    'title_placeholder' => 'Introduceți un titlu descriptiv pentru acest link scurt',
    'short_code' => 'Cod scurt',
    'short_code_placeholder' => 'Introduceți cod scurt unic (de ex., vara2024)',
    'short_code_help' => 'Acesta va fi folosit în URL: situldvs.ro/go/[cod-scurt]',
    'destination_url' => 'URL destinație',
    'destination_url_help' => 'URL-ul complet unde vor fi redirecționați utilizatorii',
    'product' => 'Produs',
    'product_help' => 'Opțional: asociați acest link scurt cu un anumit produs pentru urmărire mai bună',
    'all_products' => 'Toate produsele',
    'short_url' => 'URL scurt',
    'clicks' => 'Clicuri',
    'conversions' => 'Conversii',
    'conversion_rate' => 'Rată conversie',
    'total_clicks' => 'Total clicuri',
    'total_conversions' => 'Total conversii',
    'statistics' => 'Statistici',
    'actions' => 'Acțiuni',
    'created_at' => 'Creat la',
    'updated_at' => 'Actualizat la',

    // Validation messages
    'affiliate_required' => 'Vă rugăm să selectați un afiliat.',
    'affiliate_not_exists' => 'Afiliatul selectat nu există.',
    'short_code_required' => 'Codul scurt este obligatoriu.',
    'short_code_unique' => 'Acest cod scurt este deja folosit.',
    'short_code_alpha_dash' => 'Codul scurt poate conține doar litere, cifre, cratime și underscore.',
    'destination_url_required' => 'URL-ul destinație este obligatoriu.',
    'destination_url_invalid' => 'Vă rugăm să introduceți un URL valid.',
    'product_not_exists' => 'Produsul selectat nu există.',

    // Actions
    'copy_url' => 'Copiază URL',
    'copy_short_url' => 'Copiază URL scurt',
    'test_link' => 'Testează link',
    'visit_destination' => 'Vizitează destinația',
    'copied_to_clipboard' => 'URL copiat în clipboard!',
    'copy_failed' => 'Copierea a eșuat. Încercați din nou.',

    // UI sections
    'url_information' => 'Informații URL',
    'affiliate_product_info' => 'Informații afiliat și produs',
    'quick_actions' => 'Acțiuni rapide',
    'performance' => 'Performanță',
    'product_link' => 'Link produs',
    'general_link' => 'Link general',

    // Performance indicators
    'excellent' => 'Excelent',
    'good' => 'Bun',
    'average' => 'Mediu',
    'no_data' => 'Fără date',

    'back' => 'Înapoi',

    // Status messages
    'affiliate_not_found' => 'Afiliat negăsit',
    'short_link_not_found' => 'Link scurt negăsit',

    // Table headers
    'affiliate_column' => 'Afiliat',
    'title_column' => 'Titlu și cod',
    'destination_column' => 'Destinație',
    'short_url_column' => 'URL scurt',
    'product_column' => 'Produs',
    'stats_column' => 'Statistici',
    'created_column' => 'Creat',

    // Bulk actions
    'bulk_delete_confirm' => 'Sunteți sigur că doriți să ștergeți aceste link-uri scurte?',
    'bulk_delete_success' => 'Link-urile scurte selectate au fost șterse cu succes.',

    // Permissions
    'permissions' => [
        'index' => 'Vizualizare link-uri scurte',
        'create' => 'Creare link-uri scurte',
        'edit' => 'Editare link-uri scurte',
        'destroy' => 'Ștergere link-uri scurte',
    ],
];
