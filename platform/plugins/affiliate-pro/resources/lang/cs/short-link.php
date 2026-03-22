<?php

return [
    'name' => 'Krátké Odkazy',
    'short_link' => 'Krátký Odkaz',
    'short_links' => 'Krátké Odkazy',
    'create' => 'Vytvořit Krátký Odkaz',
    'edit' => 'Upravit Krátký Odkaz',
    'delete' => 'Smazat Krátký Odkaz',
    'short_link_details' => 'Detaily Krátkého Odkazu',
    'untitled' => 'Odkaz Bez Názvu',

    // Form fields
    'affiliate' => 'Partner',
    'title' => 'Název',
    'title_placeholder' => 'Zadejte popisný název pro tento krátký odkaz',
    'short_code' => 'Krátký Kód',
    'short_code_placeholder' => 'Zadejte jedinečný krátký kód (např. leto2024)',
    'short_code_help' => 'Bude použito v URL: vasestranka.cz/go/[kratky-kod]',
    'destination_url' => 'Cílová URL',
    'destination_url_help' => 'Úplná URL, na kterou budou uživatelé přesměrováni',
    'product' => 'Produkt',
    'product_help' => 'Volitelné: Propojte tento krátký odkaz s konkrétním produktem pro lepší sledování',
    'all_products' => 'Všechny Produkty',
    'short_url' => 'Krátká URL',
    'clicks' => 'Kliknutí',
    'conversions' => 'Konverze',
    'conversion_rate' => 'Konverzní Poměr',
    'total_clicks' => 'Celkový Počet Kliknutí',
    'total_conversions' => 'Celkový Počet Konverzí',
    'statistics' => 'Statistiky',
    'actions' => 'Akce',
    'created_at' => 'Vytvořeno',
    'updated_at' => 'Aktualizováno',

    // Validation messages
    'affiliate_required' => 'Vyberte partnera.',
    'affiliate_not_exists' => 'Vybraný partner neexistuje.',
    'short_code_required' => 'Krátký kód je povinný.',
    'short_code_unique' => 'Tento krátký kód je již použit.',
    'short_code_alpha_dash' => 'Krátký kód může obsahovat pouze písmena, číslice, pomlčky a podtržítka.',
    'destination_url_required' => 'Cílová URL je povinná.',
    'destination_url_invalid' => 'Zadejte prosím platnou URL.',
    'product_not_exists' => 'Vybraný produkt neexistuje.',

    // Actions
    'copy_url' => 'Kopírovat URL',
    'copy_short_url' => 'Kopírovat Krátkou URL',
    'test_link' => 'Otestovat Odkaz',
    'visit_destination' => 'Navštívit Cíl',
    'copied_to_clipboard' => 'URL zkopírována do schránky!',
    'copy_failed' => 'Kopírování selhalo. Zkuste to prosím znovu.',

    // UI sections
    'url_information' => 'Informace o URL',
    'affiliate_product_info' => 'Informace o Partnerovi a Produktu',
    'quick_actions' => 'Rychlé Akce',
    'performance' => 'Výkon',
    'product_link' => 'Odkaz na Produkt',
    'general_link' => 'Obecný Odkaz',

    // Performance indicators
    'excellent' => 'Vynikající',
    'good' => 'Dobrý',
    'average' => 'Průměrný',
    'no_data' => 'Žádná Data',

    'back' => 'Zpět',

    // Status messages
    'affiliate_not_found' => 'Partner nenalezen',
    'short_link_not_found' => 'Krátký odkaz nenalezen',

    // Table headers
    'affiliate_column' => 'Partner',
    'title_column' => 'Název a Kód',
    'destination_column' => 'Cíl',
    'short_url_column' => 'Krátká URL',
    'product_column' => 'Produkt',
    'stats_column' => 'Statistiky',
    'created_column' => 'Vytvořeno',

    // Bulk actions
    'bulk_delete_confirm' => 'Opravdu chcete smazat tyto krátké odkazy?',
    'bulk_delete_success' => 'Vybrané krátké odkazy byly úspěšně smazány.',

    // Permissions
    'permissions' => [
        'index' => 'Zobrazit krátké odkazy',
        'create' => 'Vytvořit krátké odkazy',
        'edit' => 'Upravit krátké odkazy',
        'destroy' => 'Smazat krátké odkazy',
    ],
];
