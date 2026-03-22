<?php

return [
    'name' => 'Kortlenker',
    'short_link' => 'Kortlenke',
    'short_links' => 'Kortlenker',
    'create' => 'Opprett Kortlenke',
    'edit' => 'Rediger Kortlenke',
    'delete' => 'Slett Kortlenke',
    'short_link_details' => 'Kortlenke-detaljer',
    'untitled' => 'Uten Navn Lenke',

    // Form fields
    'affiliate' => 'Partner',
    'title' => 'Tittel',
    'title_placeholder' => 'Oppgi en beskrivende tittel for denne kortlenken',
    'short_code' => 'Kort Kode',
    'short_code_placeholder' => 'Oppgi unik kort kode (f.eks. sommer2024)',
    'short_code_help' => 'Dette vil bli brukt i URL-en: dittnetsted.no/go/[kort-kode]',
    'destination_url' => 'Destinasjons-URL',
    'destination_url_help' => 'Den fulle URL-en som brukere vil bli omdirigert til',
    'product' => 'Produkt',
    'product_help' => 'Valgfritt: Koble denne kortlenken til et spesifikt produkt for bedre sporing',
    'all_products' => 'Alle Produkter',
    'short_url' => 'Kort URL',
    'clicks' => 'Klikk',
    'conversions' => 'Konverteringer',
    'conversion_rate' => 'Konverteringsrate',
    'total_clicks' => 'Totale Klikk',
    'total_conversions' => 'Totale Konverteringer',
    'statistics' => 'Statistikk',
    'actions' => 'Handlinger',
    'created_at' => 'Opprettet Den',
    'updated_at' => 'Oppdatert Den',

    // Validation messages
    'affiliate_required' => 'Vennligst velg en affiliate.',
    'affiliate_not_exists' => 'Den valgte affiliaten finnes ikke.',
    'short_code_required' => 'Kort kode er påkrevd.',
    'short_code_unique' => 'Denne korte koden er allerede tatt.',
    'short_code_alpha_dash' => 'Kort kode kan bare inneholde bokstaver, tall, bindestreker og understreker.',
    'destination_url_required' => 'Destinasjons-URL er påkrevd.',
    'destination_url_invalid' => 'Vennligst oppgi en gyldig URL.',
    'product_not_exists' => 'Det valgte produktet finnes ikke.',

    // Actions
    'copy_url' => 'Kopier URL',
    'copy_short_url' => 'Kopier Kort URL',
    'test_link' => 'Test Lenke',
    'visit_destination' => 'Besøk Destinasjon',
    'copied_to_clipboard' => 'URL kopiert til utklippstavle!',
    'copy_failed' => 'Kunne ikke kopiere URL. Prøv igjen.',

    // UI sections
    'url_information' => 'URL-informasjon',
    'affiliate_product_info' => 'Affiliate & Produktinformasjon',
    'quick_actions' => 'Hurtighandlinger',
    'performance' => 'Ytelse',
    'product_link' => 'Produktlenke',
    'general_link' => 'Generell Lenke',

    // Performance indicators
    'excellent' => 'Utmerket',
    'good' => 'God',
    'average' => 'Gjennomsnittlig',
    'no_data' => 'Ingen Data',

    'back' => 'Tilbake',

    // Status messages
    'affiliate_not_found' => 'Affiliate ikke funnet',
    'short_link_not_found' => 'Kortlenke ikke funnet',

    // Table headers
    'affiliate_column' => 'Partner',
    'title_column' => 'Tittel & Kode',
    'destination_column' => 'Destinasjon',
    'short_url_column' => 'Kort URL',
    'product_column' => 'Produkt',
    'stats_column' => 'Statistikk',
    'created_column' => 'Opprettet',

    // Bulk actions
    'bulk_delete_confirm' => 'Er du sikker på at du vil slette disse kortlenkene?',
    'bulk_delete_success' => 'Valgte kortlenker er blitt slettet.',

    // Permissions
    'permissions' => [
        'index' => 'Vis kortlenker',
        'create' => 'Opprett kortlenker',
        'edit' => 'Rediger kortlenker',
        'destroy' => 'Slett kortlenker',
    ],
];
