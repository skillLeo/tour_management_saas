<?php

return [
    'name' => 'Liens Courts',
    'short_link' => 'Lien Court',
    'short_links' => 'Liens Courts',
    'create' => 'Créer un Lien Court',
    'edit' => 'Modifier le Lien Court',
    'delete' => 'Supprimer le Lien Court',
    'short_link_details' => 'Détails du Lien Court',
    'untitled' => 'Lien Sans Titre',

    // Form fields
    'affiliate' => 'Affilié',
    'title' => 'Titre',
    'title_placeholder' => 'Entrez un titre descriptif pour ce lien court',
    'short_code' => 'Code Court',
    'short_code_placeholder' => 'Entrez un code court unique (ex: summer2024)',
    'short_code_help' => 'Ceci sera utilisé dans l\'URL: yoursite.com/go/[short-code]',
    'destination_url' => 'URL de Destination',
    'destination_url_help' => 'L\'URL complète où les utilisateurs seront redirigés',
    'product' => 'Produit',
    'product_help' => 'Optionnel: Associez ce lien court à un produit spécifique pour un meilleur suivi',
    'all_products' => 'Tous les Produits',
    'short_url' => 'URL Courte',
    'clicks' => 'Clics',
    'conversions' => 'Conversions',
    'conversion_rate' => 'Taux de Conversion',
    'total_clicks' => 'Total de Clics',
    'total_conversions' => 'Total de Conversions',
    'statistics' => 'Statistiques',
    'actions' => 'Actions',
    'created_at' => 'Créé le',
    'updated_at' => 'Mis à jour le',

    // Validation messages
    'affiliate_required' => 'Veuillez sélectionner un affilié.',
    'affiliate_not_exists' => 'L\'affilié sélectionné n\'existe pas.',
    'short_code_required' => 'Le code court est obligatoire.',
    'short_code_unique' => 'Ce code court est déjà utilisé.',
    'short_code_alpha_dash' => 'Le code court ne peut contenir que des lettres, des chiffres, des tirets et des underscores.',
    'destination_url_required' => 'L\'URL de destination est obligatoire.',
    'destination_url_invalid' => 'Veuillez entrer une URL valide.',
    'product_not_exists' => 'Le produit sélectionné n\'existe pas.',

    // Actions
    'copy_url' => 'Copier l\'URL',
    'copy_short_url' => 'Copier l\'URL Courte',
    'test_link' => 'Tester le Lien',
    'visit_destination' => 'Visiter la Destination',
    'copied_to_clipboard' => 'URL copiée dans le presse-papiers!',
    'copy_failed' => 'Échec de la copie de l\'URL. Veuillez réessayer.',

    // UI sections
    'url_information' => 'Informations de l\'URL',
    'affiliate_product_info' => 'Informations Affilié et Produit',
    'quick_actions' => 'Actions Rapides',
    'performance' => 'Performances',
    'product_link' => 'Lien de Produit',
    'general_link' => 'Lien Général',

    // Performance indicators
    'excellent' => 'Excellent',
    'good' => 'Bon',
    'average' => 'Moyen',
    'no_data' => 'Aucune Donnée',

    'back' => 'Retour',

    // Status messages
    'affiliate_not_found' => 'Affilié non trouvé',
    'short_link_not_found' => 'Lien court non trouvé',

    // Table headers
    'affiliate_column' => 'Affilié',
    'title_column' => 'Titre et Code',
    'destination_column' => 'Destination',
    'short_url_column' => 'URL Courte',
    'product_column' => 'Produit',
    'stats_column' => 'Statistiques',
    'created_column' => 'Créé',

    // Bulk actions
    'bulk_delete_confirm' => 'Êtes-vous sûr de vouloir supprimer ces liens courts?',
    'bulk_delete_success' => 'Les liens courts sélectionnés ont été supprimés avec succès.',

    // Permissions
    'permissions' => [
        'index' => 'Voir les liens courts',
        'create' => 'Créer des liens courts',
        'edit' => 'Modifier les liens courts',
        'destroy' => 'Supprimer les liens courts',
    ],
];
