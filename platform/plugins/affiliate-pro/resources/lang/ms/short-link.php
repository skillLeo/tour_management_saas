<?php

return [
    'name' => 'Pautan Pendek',
    'short_link' => 'Pautan Pendek',
    'short_links' => 'Pautan Pendek',
    'create' => 'Cipta Pautan Pendek',
    'edit' => 'Edit Pautan Pendek',
    'delete' => 'Padam Pautan Pendek',
    'short_link_details' => 'Butiran Pautan Pendek',
    'untitled' => 'Pautan Tanpa Tajuk',

    // Form fields
    'affiliate' => 'Afiliasi',
    'title' => 'Tajuk',
    'title_placeholder' => 'Masukkan tajuk deskriptif untuk pautan pendek ini',
    'short_code' => 'Kod Pendek',
    'short_code_placeholder' => 'Masukkan kod pendek unik (cth., summer2024)',
    'short_code_help' => 'Ini akan digunakan dalam URL: yoursite.com/go/[short-code]',
    'destination_url' => 'URL Destinasi',
    'destination_url_help' => 'URL penuh di mana pengguna akan diubah hala',
    'product' => 'Produk',
    'product_help' => 'Pilihan: Pautkan pautan pendek ini ke produk tertentu untuk penjejakan yang lebih baik',
    'all_products' => 'Semua Produk',
    'short_url' => 'URL Pendek',
    'clicks' => 'Klik',
    'conversions' => 'Penukaran',
    'conversion_rate' => 'Kadar Penukaran',
    'total_clicks' => 'Jumlah Klik',
    'total_conversions' => 'Jumlah Penukaran',
    'statistics' => 'Statistik',
    'actions' => 'Tindakan',
    'created_at' => 'Dicipta Pada',
    'updated_at' => 'Dikemaskini Pada',

    // Validation messages
    'affiliate_required' => 'Sila pilih afiliasi.',
    'affiliate_not_exists' => 'Afiliasi yang dipilih tidak wujud.',
    'short_code_required' => 'Kod pendek diperlukan.',
    'short_code_unique' => 'Kod pendek ini telah digunakan.',
    'short_code_alpha_dash' => 'Kod pendek hanya boleh mengandungi huruf, nombor, sengkang dan garis bawah.',
    'destination_url_required' => 'URL destinasi diperlukan.',
    'destination_url_invalid' => 'Sila masukkan URL yang sah.',
    'product_not_exists' => 'Produk yang dipilih tidak wujud.',

    // Actions
    'copy_url' => 'Salin URL',
    'copy_short_url' => 'Salin URL Pendek',
    'test_link' => 'Uji Pautan',
    'visit_destination' => 'Lawati Destinasi',
    'copied_to_clipboard' => 'URL disalin ke papan keratan!',
    'copy_failed' => 'Gagal menyalin URL. Sila cuba lagi.',

    // UI sections
    'url_information' => 'Maklumat URL',
    'affiliate_product_info' => 'Maklumat Afiliasi & Produk',
    'quick_actions' => 'Tindakan Pantas',
    'performance' => 'Prestasi',
    'product_link' => 'Pautan Produk',
    'general_link' => 'Pautan Umum',

    // Performance indicators
    'excellent' => 'Cemerlang',
    'good' => 'Baik',
    'average' => 'Sederhana',
    'no_data' => 'Tiada Data',

    'back' => 'Kembali',

    // Status messages
    'affiliate_not_found' => 'Afiliasi tidak dijumpai',
    'short_link_not_found' => 'Pautan pendek tidak dijumpai',

    // Table headers
    'affiliate_column' => 'Afiliasi',
    'title_column' => 'Tajuk & Kod',
    'destination_column' => 'Destinasi',
    'short_url_column' => 'URL Pendek',
    'product_column' => 'Produk',
    'stats_column' => 'Statistik',
    'created_column' => 'Dicipta',

    // Bulk actions
    'bulk_delete_confirm' => 'Adakah anda pasti mahu memadam pautan pendek ini?',
    'bulk_delete_success' => 'Pautan pendek yang dipilih telah berjaya dipadamkan.',

    // Permissions
    'permissions' => [
        'index' => 'Lihat pautan pendek',
        'create' => 'Cipta pautan pendek',
        'edit' => 'Edit pautan pendek',
        'destroy' => 'Padam pautan pendek',
    ],
];
