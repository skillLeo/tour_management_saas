<?php

return [
    'name' => 'Σύντομοι Σύνδεσμοι',
    'short_link' => 'Σύντομος Σύνδεσμος',
    'short_links' => 'Σύντομοι Σύνδεσμοι',
    'create' => 'Δημιουργία Σύντομου Συνδέσμου',
    'edit' => 'Επεξεργασία Σύντομου Συνδέσμου',
    'delete' => 'Διαγραφή Σύντομου Συνδέσμου',
    'short_link_details' => 'Λεπτομέρειες Σύντομου Συνδέσμου',
    'untitled' => 'Ανώνυμος Σύνδεσμος',

    // Form fields
    'affiliate' => 'Συνεργάτης',
    'title' => 'Τίτλος',
    'title_placeholder' => 'Εισαγάγετε έναν περιγραφικό τίτλο για αυτόν τον σύντομο σύνδεσμο',
    'short_code' => 'Σύντομος Κωδικός',
    'short_code_placeholder' => 'Εισαγάγετε μοναδικό σύντομο κωδικό (π.χ., summer2024)',
    'short_code_help' => 'Αυτό θα χρησιμοποιηθεί στο URL: yoursite.com/go/[short-code]',
    'destination_url' => 'URL Προορισμού',
    'destination_url_help' => 'Το πλήρες URL όπου θα ανακατευθυνθούν οι χρήστες',
    'product' => 'Προϊόν',
    'product_help' => 'Προαιρετικό: Συνδέστε αυτόν τον σύντομο σύνδεσμο με ένα συγκεκριμένο προϊόν για καλύτερη παρακολούθηση',
    'all_products' => 'Όλα τα Προϊόντα',
    'short_url' => 'Σύντομο URL',
    'clicks' => 'Κλικ',
    'conversions' => 'Μετατροπές',
    'conversion_rate' => 'Ποσοστό Μετατροπής',
    'total_clicks' => 'Συνολικά Κλικ',
    'total_conversions' => 'Συνολικές Μετατροπές',
    'statistics' => 'Στατιστικά',
    'actions' => 'Ενέργειες',
    'created_at' => 'Δημιουργήθηκε Στις',
    'updated_at' => 'Ενημερώθηκε Στις',

    // Validation messages
    'affiliate_required' => 'Παρακαλώ επιλέξτε έναν συνεργάτη.',
    'affiliate_not_exists' => 'Ο επιλεγμένος συνεργάτης δεν υπάρχει.',
    'short_code_required' => 'Ο σύντομος κωδικός είναι υποχρεωτικός.',
    'short_code_unique' => 'Αυτός ο σύντομος κωδικός χρησιμοποιείται ήδη.',
    'short_code_alpha_dash' => 'Ο σύντομος κωδικός μπορεί να περιέχει μόνο γράμματα, αριθμούς, παύλες και κάτω παύλες.',
    'destination_url_required' => 'Το URL προορισμού είναι υποχρεωτικό.',
    'destination_url_invalid' => 'Παρακαλώ εισαγάγετε έγκυρο URL.',
    'product_not_exists' => 'Το επιλεγμένο προϊόν δεν υπάρχει.',

    // Actions
    'copy_url' => 'Αντιγραφή URL',
    'copy_short_url' => 'Αντιγραφή Σύντομου URL',
    'test_link' => 'Δοκιμή Συνδέσμου',
    'visit_destination' => 'Επίσκεψη Προορισμού',
    'copied_to_clipboard' => 'Το URL αντιγράφηκε στο πρόχειρο!',
    'copy_failed' => 'Αποτυχία αντιγραφής URL. Παρακαλώ δοκιμάστε ξανά.',

    // UI sections
    'url_information' => 'Πληροφορίες URL',
    'affiliate_product_info' => 'Πληροφορίες Συνεργάτη & Προϊόντος',
    'quick_actions' => 'Γρήγορες Ενέργειες',
    'performance' => 'Απόδοση',
    'product_link' => 'Σύνδεσμος Προϊόντος',
    'general_link' => 'Γενικός Σύνδεσμος',

    // Performance indicators
    'excellent' => 'Εξαιρετικό',
    'good' => 'Καλό',
    'average' => 'Μέτριο',
    'no_data' => 'Χωρίς Δεδομένα',

    'back' => 'Πίσω',

    // Status messages
    'affiliate_not_found' => 'Ο συνεργάτης δεν βρέθηκε',
    'short_link_not_found' => 'Ο σύντομος σύνδεσμος δεν βρέθηκε',

    // Table headers
    'affiliate_column' => 'Συνεργάτης',
    'title_column' => 'Τίτλος & Κωδικός',
    'destination_column' => 'Προορισμός',
    'short_url_column' => 'Σύντομο URL',
    'product_column' => 'Προϊόν',
    'stats_column' => 'Στατιστικά',
    'created_column' => 'Δημιουργήθηκε',

    // Bulk actions
    'bulk_delete_confirm' => 'Είστε βέβαιοι ότι θέλετε να διαγράψετε αυτούς τους σύντομους συνδέσμους;',
    'bulk_delete_success' => 'Οι επιλεγμένοι σύντομοι σύνδεσμοι διαγράφηκαν επιτυχώς.',

    // Permissions
    'permissions' => [
        'index' => 'Προβολή σύντομων συνδέσμων',
        'create' => 'Δημιουργία σύντομων συνδέσμων',
        'edit' => 'Επεξεργασία σύντομων συνδέσμων',
        'destroy' => 'Διαγραφή σύντομων συνδέσμων',
    ],
];
