<?php

return [
    'name' => 'Korte Links',
    'short_link' => 'Kort Link',
    'short_links' => 'Korte Links',
    'create' => 'Opret Kort Link',
    'edit' => 'Rediger Kort Link',
    'delete' => 'Slet Kort Link',
    'short_link_details' => 'Kort Link-detaljer',
    'untitled' => 'Unavngivet Link',

    // Form fields
    'affiliate' => 'Partner',
    'title' => 'Titel',
    'title_placeholder' => 'Indtast en beskrivende titel for dette korte link',
    'short_code' => 'Kort Kode',
    'short_code_placeholder' => 'Indtast unik kort kode (f.eks. sommer2024)',
    'short_code_help' => 'Dette vil blive brugt i URL\'en: ditwebsted.dk/go/[kort-kode]',
    'destination_url' => 'Destinations-URL',
    'destination_url_help' => 'Den fulde URL, som brugere vil blive omdirigeret til',
    'product' => 'Produkt',
    'product_help' => 'Valgfrit: Link dette korte link til et specifikt produkt for bedre sporing',
    'all_products' => 'Alle Produkter',
    'short_url' => 'Kort URL',
    'clicks' => 'Klik',
    'conversions' => 'Konverteringer',
    'conversion_rate' => 'Konverteringsrate',
    'total_clicks' => 'Totale Klik',
    'total_conversions' => 'Totale Konverteringer',
    'statistics' => 'Statistik',
    'actions' => 'Handlinger',
    'created_at' => 'Oprettet Den',
    'updated_at' => 'Opdateret Den',

    // Validation messages
    'affiliate_required' => 'Vælg en affiliate.',
    'affiliate_not_exists' => 'Den valgte affiliate findes ikke.',
    'short_code_required' => 'Kort kode er påkrævet.',
    'short_code_unique' => 'Denne korte kode er allerede taget.',
    'short_code_alpha_dash' => 'Kort kode må kun indeholde bogstaver, tal, bindestreger og understregninger.',
    'destination_url_required' => 'Destinations-URL er påkrævet.',
    'destination_url_invalid' => 'Indtast en gyldig URL.',
    'product_not_exists' => 'Det valgte produkt findes ikke.',

    // Actions
    'copy_url' => 'Kopier URL',
    'copy_short_url' => 'Kopier Kort URL',
    'test_link' => 'Afprøv link',
    'visit_destination' => 'Besøg Destination',
    'copied_to_clipboard' => 'URL kopieret til udklipsholder!',
    'copy_failed' => 'Kunne ikke kopiere URL. Prøv igen.',

    // UI sections
    'url_information' => 'URL-information',
    'affiliate_product_info' => 'Affiliate & Produktinformation',
    'quick_actions' => 'Hurtige Handlinger',
    'performance' => 'Præstation',
    'product_link' => 'Produktlink',
    'general_link' => 'Generelt Link',

    // Performance indicators
    'excellent' => 'Fremragende',
    'good' => 'God',
    'average' => 'Gennemsnitlig',
    'no_data' => 'Ingen Data',

    'back' => 'Tilbage',

    // Status messages
    'affiliate_not_found' => 'Affiliate ikke fundet',
    'short_link_not_found' => 'Kort link ikke fundet',

    // Table headers
    'affiliate_column' => 'Partner',
    'title_column' => 'Titel & Kode',
    'destination_column' => 'Destination',
    'short_url_column' => 'Kort URL',
    'product_column' => 'Produkt',
    'stats_column' => 'Statistik',
    'created_column' => 'Oprettet',

    // Bulk actions
    'bulk_delete_confirm' => 'Er du sikker på, at du vil slette disse korte links?',
    'bulk_delete_success' => 'Valgte korte links er blevet slettet.',

    // Permissions
    'permissions' => [
        'index' => 'Vis korte links',
        'create' => 'Opret korte links',
        'edit' => 'Rediger korte links',
        'destroy' => 'Slet korte links',
    ],
];
