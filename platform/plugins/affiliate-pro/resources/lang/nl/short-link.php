<?php

return [
    'name' => 'Korte Links',
    'short_link' => 'Korte Link',
    'short_links' => 'Korte Links',
    'create' => 'Korte Link Aanmaken',
    'edit' => 'Korte Link Bewerken',
    'delete' => 'Korte Link Verwijderen',
    'short_link_details' => 'Korte Link Details',
    'untitled' => 'Naamloze Link',

    // Form fields
    'affiliate' => 'Partner',
    'title' => 'Titel',
    'title_placeholder' => 'Voer een beschrijvende titel in voor deze korte link',
    'short_code' => 'Korte Code',
    'short_code_placeholder' => 'Voer unieke korte code in (bijv. zomer2024)',
    'short_code_help' => 'Deze wordt gebruikt in de URL: uwsite.nl/go/[korte-code]',
    'destination_url' => 'Doel-URL',
    'destination_url_help' => 'De volledige URL waarnaar gebruikers worden doorgestuurd',
    'product' => 'Product',
    'product_help' => 'Optioneel: Koppel deze korte link aan een specifiek product voor betere tracking',
    'all_products' => 'Alle Producten',
    'short_url' => 'Korte URL',
    'clicks' => 'Klikken',
    'conversions' => 'Conversies',
    'conversion_rate' => 'Conversiepercentage',
    'total_clicks' => 'Totaal Aantal Clicks',
    'total_conversions' => 'Totaal Aantal Conversies',
    'statistics' => 'Statistieken',
    'actions' => 'Acties',
    'created_at' => 'Aangemaakt Op',
    'updated_at' => 'Bijgewerkt Op',

    // Validation messages
    'affiliate_required' => 'Selecteer een partner.',
    'affiliate_not_exists' => 'De geselecteerde partner bestaat niet.',
    'short_code_required' => 'Korte code is verplicht.',
    'short_code_unique' => 'Deze korte code is al in gebruik.',
    'short_code_alpha_dash' => 'Korte code mag alleen letters, cijfers, streepjes en underscores bevatten.',
    'destination_url_required' => 'Doel-URL is verplicht.',
    'destination_url_invalid' => 'Voer een geldige URL in.',
    'product_not_exists' => 'Het geselecteerde product bestaat niet.',

    // Actions
    'copy_url' => 'URL Kopiëren',
    'copy_short_url' => 'Korte URL Kopiëren',
    'test_link' => 'Link Testen',
    'visit_destination' => 'Bestemming Bezoeken',
    'copied_to_clipboard' => 'URL gekopieerd naar klembord!',
    'copy_failed' => 'Kopiëren mislukt. Probeer het opnieuw.',

    // UI sections
    'url_information' => 'URL-informatie',
    'affiliate_product_info' => 'Partner & Productinformatie',
    'quick_actions' => 'Snelle Acties',
    'performance' => 'Prestaties',
    'product_link' => 'Productlink',
    'general_link' => 'Algemene Link',

    // Performance indicators
    'excellent' => 'Uitstekend',
    'good' => 'Goed',
    'average' => 'Gemiddeld',
    'no_data' => 'Geen Gegevens',

    'back' => 'Terug',

    // Status messages
    'affiliate_not_found' => 'Partner niet gevonden',
    'short_link_not_found' => 'Korte link niet gevonden',

    // Table headers
    'affiliate_column' => 'Partner',
    'title_column' => 'Titel & Code',
    'destination_column' => 'Bestemming',
    'short_url_column' => 'Korte URL',
    'product_column' => 'Product',
    'stats_column' => 'Statistieken',
    'created_column' => 'Aangemaakt',

    // Bulk actions
    'bulk_delete_confirm' => 'Weet u zeker dat u deze korte links wilt verwijderen?',
    'bulk_delete_success' => 'Geselecteerde korte links zijn succesvol verwijderd.',

    // Permissions
    'permissions' => [
        'index' => 'Korte links bekijken',
        'create' => 'Korte links aanmaken',
        'edit' => 'Korte links bewerken',
        'destroy' => 'Korte links verwijderen',
    ],
];
