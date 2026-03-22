<?php

return [
    'name' => 'Link brevi',
    'short_link' => 'Link breve',
    'short_links' => 'Link brevi',
    'create' => 'Crea link breve',
    'edit' => 'Modifica link breve',
    'delete' => 'Elimina link breve',
    'short_link_details' => 'Dettagli link breve',
    'untitled' => 'Link senza titolo',

    // Form fields
    'affiliate' => 'Affiliato',
    'title' => 'Titolo',
    'title_placeholder' => 'Inserisci un titolo descrittivo per questo link breve',
    'short_code' => 'Codice breve',
    'short_code_placeholder' => 'Inserisci codice breve univoco (es. estate2024)',
    'short_code_help' => 'Questo sarà usato nell\'URL: tuosito.com/go/[codice-breve]',
    'destination_url' => 'URL destinazione',
    'destination_url_help' => 'L\'URL completo a cui gli utenti verranno reindirizzati',
    'product' => 'Prodotto',
    'product_help' => 'Opzionale: Collega questo link breve a un prodotto specifico per un migliore tracciamento',
    'all_products' => 'Tutti i prodotti',
    'short_url' => 'URL breve',
    'clicks' => 'Clic',
    'conversions' => 'Conversioni',
    'conversion_rate' => 'Tasso di conversione',
    'total_clicks' => 'Clic totali',
    'total_conversions' => 'Conversioni totali',
    'statistics' => 'Statistiche',
    'actions' => 'Azioni',
    'created_at' => 'Creato il',
    'updated_at' => 'Aggiornato il',

    // Validation messages
    'affiliate_required' => 'Seleziona un affiliato.',
    'affiliate_not_exists' => 'L\'affiliato selezionato non esiste.',
    'short_code_required' => 'Il codice breve è obbligatorio.',
    'short_code_unique' => 'Questo codice breve è già in uso.',
    'short_code_alpha_dash' => 'Il codice breve può contenere solo lettere, numeri, trattini e trattini bassi.',
    'destination_url_required' => 'L\'URL di destinazione è obbligatorio.',
    'destination_url_invalid' => 'Inserisci un URL valido.',
    'product_not_exists' => 'Il prodotto selezionato non esiste.',

    // Actions
    'copy_url' => 'Copia URL',
    'copy_short_url' => 'Copia URL breve',
    'test_link' => 'Testa link',
    'visit_destination' => 'Visita destinazione',
    'copied_to_clipboard' => 'URL copiato negli appunti!',
    'copy_failed' => 'Impossibile copiare l\'URL. Riprova.',

    // UI sections
    'url_information' => 'Informazioni URL',
    'affiliate_product_info' => 'Informazioni affiliato e prodotto',
    'quick_actions' => 'Azioni rapide',
    'performance' => 'Prestazioni',
    'product_link' => 'Link prodotto',
    'general_link' => 'Link generale',

    // Performance indicators
    'excellent' => 'Eccellente',
    'good' => 'Buono',
    'average' => 'Medio',
    'no_data' => 'Nessun dato',

    'back' => 'Indietro',

    // Status messages
    'affiliate_not_found' => 'Affiliato non trovato',
    'short_link_not_found' => 'Link breve non trovato',

    // Table headers
    'affiliate_column' => 'Affiliato',
    'title_column' => 'Titolo e codice',
    'destination_column' => 'Destinazione',
    'short_url_column' => 'URL breve',
    'product_column' => 'Prodotto',
    'stats_column' => 'Statistiche',
    'created_column' => 'Creato',

    // Bulk actions
    'bulk_delete_confirm' => 'Sei sicuro di voler eliminare questi link brevi?',
    'bulk_delete_success' => 'Link brevi selezionati eliminati con successo.',

    // Permissions
    'permissions' => [
        'index' => 'Visualizza link brevi',
        'create' => 'Crea link brevi',
        'edit' => 'Modifica link brevi',
        'destroy' => 'Elimina link brevi',
    ],
];
