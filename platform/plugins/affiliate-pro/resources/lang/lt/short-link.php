<?php

return [
    'name' => 'Trumpos nuorodos',
    'short_link' => 'Trumpa nuoroda',
    'short_links' => 'Trumpos nuorodos',
    'create' => 'Sukurti trumpą nuorodą',
    'edit' => 'Redaguoti trumpą nuorodą',
    'delete' => 'Ištrinti trumpą nuorodą',
    'short_link_details' => 'Trumpos nuorodos duomenys',
    'untitled' => 'Be pavadinimo',

    // Form fields
    'affiliate' => 'Partneris',
    'title' => 'Pavadinimas',
    'title_placeholder' => 'Įveskite aprašomąjį pavadinimą šiai trumpai nuorodai',
    'short_code' => 'Trumpas kodas',
    'short_code_placeholder' => 'Įveskite unikalų trumpą kodą (pvz., vasara2024)',
    'short_code_help' => 'Tai bus naudojama URL: jususvietove.lt/go/[trumpas-kodas]',
    'destination_url' => 'Paskirties URL',
    'destination_url_help' => 'Pilnas URL, kuriuo vartotojai bus nukreipti',
    'product' => 'Produktas',
    'product_help' => 'Pasirinktinai: susieti šią trumpą nuorodą su konkrečiu produktu geresniam stebėjimui',
    'all_products' => 'Visi produktai',
    'short_url' => 'Trumpas URL',
    'clicks' => 'Paspaudimai',
    'conversions' => 'Konversijos',
    'conversion_rate' => 'Konversijos lygis',
    'total_clicks' => 'Viso paspaudimų',
    'total_conversions' => 'Viso konversijų',
    'statistics' => 'Statistika',
    'actions' => 'Veiksmai',
    'created_at' => 'Sukurta',
    'updated_at' => 'Atnaujinta',

    // Validation messages
    'affiliate_required' => 'Pasirinkite partnerį.',
    'affiliate_not_exists' => 'Pasirinktas partneris neegzistuoja.',
    'short_code_required' => 'Trumpas kodas būtinas.',
    'short_code_unique' => 'Šis trumpas kodas jau užimtas.',
    'short_code_alpha_dash' => 'Trumpas kodas gali turėti tik raides, skaičius, brūkšnius ir pabraukimus.',
    'destination_url_required' => 'Paskirties URL būtinas.',
    'destination_url_invalid' => 'Įveskite tinkamą URL.',
    'product_not_exists' => 'Pasirinktas produktas neegzistuoja.',

    // Actions
    'copy_url' => 'Kopijuoti URL',
    'copy_short_url' => 'Kopijuoti trumpą URL',
    'test_link' => 'Išbandyti nuorodą',
    'visit_destination' => 'Aplankyti paskirtį',
    'copied_to_clipboard' => 'URL nukopijuotas į iškarpinę!',
    'copy_failed' => 'Nepavyko nukopijuoti URL. Bandykite dar kartą.',

    // UI sections
    'url_information' => 'URL informacija',
    'affiliate_product_info' => 'Partnerio ir produkto informacija',
    'quick_actions' => 'Greiti veiksmai',
    'performance' => 'Efektyvumas',
    'product_link' => 'Produkto nuoroda',
    'general_link' => 'Bendra nuoroda',

    // Performance indicators
    'excellent' => 'Puikiai',
    'good' => 'Gerai',
    'average' => 'Vidutiniškai',
    'no_data' => 'Nėra duomenų',

    'back' => 'Atgal',

    // Status messages
    'affiliate_not_found' => 'Partneris nerastas',
    'short_link_not_found' => 'Trumpa nuoroda nerasta',

    // Table headers
    'affiliate_column' => 'Partneris',
    'title_column' => 'Pavadinimas ir kodas',
    'destination_column' => 'Paskirtis',
    'short_url_column' => 'Trumpas URL',
    'product_column' => 'Produktas',
    'stats_column' => 'Statistika',
    'created_column' => 'Sukurta',

    // Bulk actions
    'bulk_delete_confirm' => 'Ar tikrai norite ištrinti šias trumpas nuorodas?',
    'bulk_delete_success' => 'Pasirinktos trumpos nuorodos sėkmingai ištrintos.',

    // Permissions
    'permissions' => [
        'index' => 'Peržiūrėti trumpas nuorodas',
        'create' => 'Sukurti trumpas nuorodas',
        'edit' => 'Redaguoti trumpas nuorodas',
        'destroy' => 'Ištrinti trumpas nuorodas',
    ],
];
