<?php

return [
    'name' => 'Kurzlinks',
    'short_link' => 'Kurzlink',
    'short_links' => 'Kurzlinks',
    'create' => 'Kurzlink erstellen',
    'edit' => 'Kurzlink bearbeiten',
    'delete' => 'Kurzlink löschen',
    'short_link_details' => 'Kurzlink-Details',
    'untitled' => 'Unbenannter Link',

    // Form fields
    'affiliate' => 'Partner',
    'title' => 'Titel',
    'title_placeholder' => 'Geben Sie einen beschreibenden Titel für diesen Kurzlink ein',
    'short_code' => 'Kurzcode',
    'short_code_placeholder' => 'Eindeutigen Kurzcode eingeben (z.B. sommer2024)',
    'short_code_help' => 'Dieser wird in der URL verwendet: ihreseite.com/go/[kurzcode]',
    'destination_url' => 'Ziel-URL',
    'destination_url_help' => 'Die vollständige URL, zu der Benutzer weitergeleitet werden',
    'product' => 'Produkt',
    'product_help' => 'Optional: Verknüpfen Sie diesen Kurzlink mit einem bestimmten Produkt für besseres Tracking',
    'all_products' => 'Alle Produkte',
    'short_url' => 'Kurz-URL',
    'clicks' => 'Klicks',
    'conversions' => 'Konversionen',
    'conversion_rate' => 'Konversionsrate',
    'total_clicks' => 'Gesamtklicks',
    'total_conversions' => 'Gesamtkonversionen',
    'statistics' => 'Statistiken',
    'actions' => 'Aktionen',
    'created_at' => 'Erstellt am',
    'updated_at' => 'Aktualisiert am',

    // Validation messages
    'affiliate_required' => 'Bitte wählen Sie einen Partner aus.',
    'affiliate_not_exists' => 'Der ausgewählte Partner existiert nicht.',
    'short_code_required' => 'Kurzcode ist erforderlich.',
    'short_code_unique' => 'Dieser Kurzcode ist bereits vergeben.',
    'short_code_alpha_dash' => 'Der Kurzcode darf nur Buchstaben, Zahlen, Bindestriche und Unterstriche enthalten.',
    'destination_url_required' => 'Ziel-URL ist erforderlich.',
    'destination_url_invalid' => 'Bitte geben Sie eine gültige URL ein.',
    'product_not_exists' => 'Das ausgewählte Produkt existiert nicht.',

    // Actions
    'copy_url' => 'URL kopieren',
    'copy_short_url' => 'Kurz-URL kopieren',
    'test_link' => 'Link testen',
    'visit_destination' => 'Ziel besuchen',
    'copied_to_clipboard' => 'URL in die Zwischenablage kopiert!',
    'copy_failed' => 'Fehler beim Kopieren der URL. Bitte versuchen Sie es erneut.',

    // UI sections
    'url_information' => 'URL-Informationen',
    'affiliate_product_info' => 'Partner- & Produktinformationen',
    'quick_actions' => 'Schnellaktionen',
    'performance' => 'Leistung',
    'product_link' => 'Produktlink',
    'general_link' => 'Allgemeiner Link',

    // Performance indicators
    'excellent' => 'Ausgezeichnet',
    'good' => 'Gut',
    'average' => 'Durchschnittlich',
    'no_data' => 'Keine Daten',

    'back' => 'Zurück',

    // Status messages
    'affiliate_not_found' => 'Partner nicht gefunden',
    'short_link_not_found' => 'Kurzlink nicht gefunden',

    // Table headers
    'affiliate_column' => 'Partner',
    'title_column' => 'Titel & Code',
    'destination_column' => 'Ziel',
    'short_url_column' => 'Kurz-URL',
    'product_column' => 'Produkt',
    'stats_column' => 'Statistiken',
    'created_column' => 'Erstellt',

    // Bulk actions
    'bulk_delete_confirm' => 'Sind Sie sicher, dass Sie diese Kurzlinks löschen möchten?',
    'bulk_delete_success' => 'Ausgewählte Kurzlinks wurden erfolgreich gelöscht.',

    // Permissions
    'permissions' => [
        'index' => 'Kurzlinks anzeigen',
        'create' => 'Kurzlinks erstellen',
        'edit' => 'Kurzlinks bearbeiten',
        'destroy' => 'Kurzlinks löschen',
    ],
];
