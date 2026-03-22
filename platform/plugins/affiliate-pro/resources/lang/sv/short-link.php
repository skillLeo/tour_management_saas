<?php

return [
    'name' => 'Kortlänkar',
    'short_link' => 'Kortlänk',
    'short_links' => 'Kortlänkar',
    'create' => 'Skapa Kortlänk',
    'edit' => 'Redigera Kortlänk',
    'delete' => 'Ta bort Kortlänk',
    'short_link_details' => 'Kortlänksinformation',
    'untitled' => 'Namnlös Länk',

    // Form fields
    'affiliate' => 'Partner',
    'title' => 'Titel',
    'title_placeholder' => 'Ange en beskrivande titel för denna kortlänk',
    'short_code' => 'Kortkod',
    'short_code_placeholder' => 'Ange unik kortkod (t.ex. sommar2024)',
    'short_code_help' => 'Detta kommer att användas i URL:en: dinwebbplats.se/go/[kortkod]',
    'destination_url' => 'Mål-URL',
    'destination_url_help' => 'Den fullständiga URL:en dit användare kommer att omdirigeras',
    'product' => 'Produkt',
    'product_help' => 'Valfritt: Länka denna kortlänk till en specifik produkt för bättre spårning',
    'all_products' => 'Alla Produkter',
    'short_url' => 'Kort URL',
    'clicks' => 'Klick',
    'conversions' => 'Konverteringar',
    'conversion_rate' => 'Konverteringsgrad',
    'total_clicks' => 'Totalt Klick',
    'total_conversions' => 'Totalt Konverteringar',
    'statistics' => 'Statistik',
    'actions' => 'Åtgärder',
    'created_at' => 'Skapad Den',
    'updated_at' => 'Uppdaterad Den',

    // Validation messages
    'affiliate_required' => 'Välj en affiliate.',
    'affiliate_not_exists' => 'Den valda affiliaten finns inte.',
    'short_code_required' => 'Kortkod krävs.',
    'short_code_unique' => 'Denna kortkod är redan upptagen.',
    'short_code_alpha_dash' => 'Kortkod får endast innehålla bokstäver, siffror, bindestreck och understreck.',
    'destination_url_required' => 'Mål-URL krävs.',
    'destination_url_invalid' => 'Ange en giltig URL.',
    'product_not_exists' => 'Den valda produkten finns inte.',

    // Actions
    'copy_url' => 'Kopiera URL',
    'copy_short_url' => 'Kopiera Kort URL',
    'test_link' => 'Testa Länk',
    'visit_destination' => 'Besök Destination',
    'copied_to_clipboard' => 'URL kopierad till urklipp!',
    'copy_failed' => 'Misslyckades kopiera URL. Försök igen.',

    // UI sections
    'url_information' => 'URL-information',
    'affiliate_product_info' => 'Affiliate & Produktinformation',
    'quick_actions' => 'Snabbåtgärder',
    'performance' => 'Prestanda',
    'product_link' => 'Produktlänk',
    'general_link' => 'Allmän Länk',

    // Performance indicators
    'excellent' => 'Utmärkt',
    'good' => 'Bra',
    'average' => 'Genomsnittlig',
    'no_data' => 'Ingen Data',

    'back' => 'Tillbaka',

    // Status messages
    'affiliate_not_found' => 'Affiliate hittades inte',
    'short_link_not_found' => 'Kortlänk hittades inte',

    // Table headers
    'affiliate_column' => 'Partner',
    'title_column' => 'Titel & Kod',
    'destination_column' => 'Destination',
    'short_url_column' => 'Kort URL',
    'product_column' => 'Produkt',
    'stats_column' => 'Statistik',
    'created_column' => 'Skapad',

    // Bulk actions
    'bulk_delete_confirm' => 'Är du säker på att du vill ta bort dessa kortlänkar?',
    'bulk_delete_success' => 'Valda kortlänkar har raderats.',

    // Permissions
    'permissions' => [
        'index' => 'Visa kortlänkar',
        'create' => 'Skapa kortlänkar',
        'edit' => 'Redigera kortlänkar',
        'destroy' => 'Ta bort kortlänkar',
    ],
];
