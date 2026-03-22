<?php

return [
    'name' => 'Lyhyet Linkit',
    'short_link' => 'Lyhyt Linkki',
    'short_links' => 'Lyhyet Linkit',
    'create' => 'Luo Lyhyt Linkki',
    'edit' => 'Muokkaa Lyhyttä Linkkiä',
    'delete' => 'Poista Lyhyt Linkki',
    'short_link_details' => 'Lyhyen Linkin Tiedot',
    'untitled' => 'Nimetön Linkki',

    // Form fields
    'affiliate' => 'Kumppani',
    'title' => 'Otsikko',
    'title_placeholder' => 'Anna kuvaava otsikko tälle lyhyelle linkille',
    'short_code' => 'Lyhyt Koodi',
    'short_code_placeholder' => 'Anna yksilöllinen lyhyt koodi (esim. kesa2024)',
    'short_code_help' => 'Tätä käytetään URL-osoitteessa: sivustosi.fi/go/[lyhyt-koodi]',
    'destination_url' => 'Kohde-URL',
    'destination_url_help' => 'Täydellinen URL-osoite, johon käyttäjät ohjataan',
    'product' => 'Tuote',
    'product_help' => 'Valinnainen: Linkitä tämä lyhyt linkki tiettyyn tuotteeseen parempaa seurantaa varten',
    'all_products' => 'Kaikki Tuotteet',
    'short_url' => 'Lyhyt URL',
    'clicks' => 'Klikkaukset',
    'conversions' => 'Konversiot',
    'conversion_rate' => 'Konversioprosentti',
    'total_clicks' => 'Klikkauksia Yhteensä',
    'total_conversions' => 'Konversioita Yhteensä',
    'statistics' => 'Tilastot',
    'actions' => 'Toiminnot',
    'created_at' => 'Luotu',
    'updated_at' => 'Päivitetty',

    // Validation messages
    'affiliate_required' => 'Valitse affiliate.',
    'affiliate_not_exists' => 'Valittua affiliatea ei ole olemassa.',
    'short_code_required' => 'Lyhyt koodi vaaditaan.',
    'short_code_unique' => 'Tämä lyhyt koodi on jo käytössä.',
    'short_code_alpha_dash' => 'Lyhyt koodi voi sisältää vain kirjaimia, numeroita, viivoja ja alaviivoja.',
    'destination_url_required' => 'Kohde-URL vaaditaan.',
    'destination_url_invalid' => 'Anna kelvollinen URL.',
    'product_not_exists' => 'Valittua tuotetta ei ole olemassa.',

    // Actions
    'copy_url' => 'Kopioi URL',
    'copy_short_url' => 'Kopioi Lyhyt URL',
    'test_link' => 'Testaa Linkki',
    'visit_destination' => 'Käy Kohteessa',
    'copied_to_clipboard' => 'URL kopioitu leikepöydälle!',
    'copy_failed' => 'URL:n kopiointi epäonnistui. Yritä uudelleen.',

    // UI sections
    'url_information' => 'URL-tiedot',
    'affiliate_product_info' => 'Affiliate & Tuotetiedot',
    'quick_actions' => 'Pikatoiminnot',
    'performance' => 'Suorituskyky',
    'product_link' => 'Tuotelinkki',
    'general_link' => 'Yleinen Linkki',

    // Performance indicators
    'excellent' => 'Erinomainen',
    'good' => 'Hyvä',
    'average' => 'Keskinkertainen',
    'no_data' => 'Ei Tietoja',

    'back' => 'Takaisin',

    // Status messages
    'affiliate_not_found' => 'Affiliatea ei löytynyt',
    'short_link_not_found' => 'Lyhyttä linkkiä ei löytynyt',

    // Table headers
    'affiliate_column' => 'Kumppani',
    'title_column' => 'Otsikko & Koodi',
    'destination_column' => 'Kohde',
    'short_url_column' => 'Lyhyt URL',
    'product_column' => 'Tuote',
    'stats_column' => 'Tilastot',
    'created_column' => 'Luotu',

    // Bulk actions
    'bulk_delete_confirm' => 'Oletko varma, että haluat poistaa nämä lyhyet linkit?',
    'bulk_delete_success' => 'Valitut lyhyet linkit on poistettu.',

    // Permissions
    'permissions' => [
        'index' => 'Näytä lyhyet linkit',
        'create' => 'Luo lyhyitä linkkejä',
        'edit' => 'Muokkaa lyhyitä linkkejä',
        'destroy' => 'Poista lyhyitä linkkejä',
    ],
];
