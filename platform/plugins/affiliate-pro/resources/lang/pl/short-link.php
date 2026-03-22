<?php

return [
    'name' => 'Krótkie Linki',
    'short_link' => 'Krótki Link',
    'short_links' => 'Krótkie Linki',
    'create' => 'Utwórz Krótki Link',
    'edit' => 'Edytuj Krótki Link',
    'delete' => 'Usuń Krótki Link',
    'short_link_details' => 'Szczegóły Krótkiego Linku',
    'untitled' => 'Link bez Tytułu',

    // Form fields
    'affiliate' => 'Partner',
    'title' => 'Tytuł',
    'title_placeholder' => 'Wprowadź opisowy tytuł dla tego krótkiego linku',
    'short_code' => 'Krótki Kod',
    'short_code_placeholder' => 'Wprowadź unikalny krótki kod (np. lato2024)',
    'short_code_help' => 'Będzie używany w adresie URL: twojastrona.pl/go/[krótki-kod]',
    'destination_url' => 'Docelowy URL',
    'destination_url_help' => 'Pełny adres URL, do którego użytkownicy zostaną przekierowani',
    'product' => 'Produkt',
    'product_help' => 'Opcjonalnie: Połącz ten krótki link z konkretnym produktem w celu lepszego śledzenia',
    'all_products' => 'Wszystkie Produkty',
    'short_url' => 'Krótki URL',
    'clicks' => 'Kliknięcia',
    'conversions' => 'Konwersje',
    'conversion_rate' => 'Współczynnik Konwersji',
    'total_clicks' => 'Łączna Liczba Kliknięć',
    'total_conversions' => 'Łączna Liczba Konwersji',
    'statistics' => 'Statystyki',
    'actions' => 'Akcje',
    'created_at' => 'Utworzono',
    'updated_at' => 'Zaktualizowano',

    // Validation messages
    'affiliate_required' => 'Wybierz partnera.',
    'affiliate_not_exists' => 'Wybrany partner nie istnieje.',
    'short_code_required' => 'Krótki kod jest wymagany.',
    'short_code_unique' => 'Ten krótki kod jest już zajęty.',
    'short_code_alpha_dash' => 'Krótki kod może zawierać tylko litery, cyfry, myślniki i podkreślenia.',
    'destination_url_required' => 'Docelowy URL jest wymagany.',
    'destination_url_invalid' => 'Wprowadź prawidłowy URL.',
    'product_not_exists' => 'Wybrany produkt nie istnieje.',

    // Actions
    'copy_url' => 'Kopiuj URL',
    'copy_short_url' => 'Kopiuj Krótki URL',
    'test_link' => 'Testuj Link',
    'visit_destination' => 'Odwiedź Miejsce Docelowe',
    'copied_to_clipboard' => 'URL skopiowany do schowka!',
    'copy_failed' => 'Nie udało się skopiować URL. Spróbuj ponownie.',

    // UI sections
    'url_information' => 'Informacje o URL',
    'affiliate_product_info' => 'Informacje o Partnerze i Produkcie',
    'quick_actions' => 'Szybkie Akcje',
    'performance' => 'Wydajność',
    'product_link' => 'Link do Produktu',
    'general_link' => 'Link Ogólny',

    // Performance indicators
    'excellent' => 'Doskonały',
    'good' => 'Dobry',
    'average' => 'Średni',
    'no_data' => 'Brak Danych',

    'back' => 'Wstecz',

    // Status messages
    'affiliate_not_found' => 'Nie znaleziono partnera',
    'short_link_not_found' => 'Nie znaleziono krótkiego linku',

    // Table headers
    'affiliate_column' => 'Partner',
    'title_column' => 'Tytuł i Kod',
    'destination_column' => 'Miejsce Docelowe',
    'short_url_column' => 'Krótki URL',
    'product_column' => 'Produkt',
    'stats_column' => 'Statystyki',
    'created_column' => 'Utworzono',

    // Bulk actions
    'bulk_delete_confirm' => 'Czy na pewno chcesz usunąć te krótkie linki?',
    'bulk_delete_success' => 'Wybrane krótkie linki zostały pomyślnie usunięte.',

    // Permissions
    'permissions' => [
        'index' => 'Zobacz krótkie linki',
        'create' => 'Utwórz krótkie linki',
        'edit' => 'Edytuj krótkie linki',
        'destroy' => 'Usuń krótkie linki',
    ],
];
