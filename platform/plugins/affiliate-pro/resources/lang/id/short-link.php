<?php

return [
    'name' => 'Tautan Pendek',
    'short_link' => 'Tautan Pendek',
    'short_links' => 'Tautan Pendek',
    'create' => 'Buat Tautan Pendek',
    'edit' => 'Edit Tautan Pendek',
    'delete' => 'Hapus Tautan Pendek',
    'short_link_details' => 'Detail Tautan Pendek',
    'untitled' => 'Tautan Tanpa Judul',

    // Form fields
    'affiliate' => 'Afiliasi',
    'title' => 'Judul',
    'title_placeholder' => 'Masukkan judul deskriptif untuk tautan pendek ini',
    'short_code' => 'Kode Pendek',
    'short_code_placeholder' => 'Masukkan kode pendek unik (contoh: summer2024)',
    'short_code_help' => 'Ini akan digunakan di URL: yoursite.com/go/[short-code]',
    'destination_url' => 'URL Tujuan',
    'destination_url_help' => 'URL lengkap tempat pengguna akan diarahkan',
    'product' => 'Produk',
    'product_help' => 'Opsional: Hubungkan tautan pendek ini ke produk tertentu untuk pelacakan yang lebih baik',
    'all_products' => 'Semua Produk',
    'short_url' => 'URL Pendek',
    'clicks' => 'Klik',
    'conversions' => 'Konversi',
    'conversion_rate' => 'Tingkat Konversi',
    'total_clicks' => 'Total Klik',
    'total_conversions' => 'Total Konversi',
    'statistics' => 'Statistik',
    'actions' => 'Tindakan',
    'created_at' => 'Dibuat Pada',
    'updated_at' => 'Diperbarui Pada',

    // Validation messages
    'affiliate_required' => 'Silakan pilih afiliasi.',
    'affiliate_not_exists' => 'Afiliasi yang dipilih tidak ada.',
    'short_code_required' => 'Kode pendek wajib diisi.',
    'short_code_unique' => 'Kode pendek ini sudah digunakan.',
    'short_code_alpha_dash' => 'Kode pendek hanya boleh berisi huruf, angka, tanda hubung dan garis bawah.',
    'destination_url_required' => 'URL tujuan wajib diisi.',
    'destination_url_invalid' => 'Silakan masukkan URL yang valid.',
    'product_not_exists' => 'Produk yang dipilih tidak ada.',

    // Actions
    'copy_url' => 'Salin URL',
    'copy_short_url' => 'Salin URL Pendek',
    'test_link' => 'Uji Tautan',
    'visit_destination' => 'Kunjungi Tujuan',
    'copied_to_clipboard' => 'URL disalin ke clipboard!',
    'copy_failed' => 'Gagal menyalin URL. Silakan coba lagi.',

    // UI sections
    'url_information' => 'Informasi URL',
    'affiliate_product_info' => 'Informasi Afiliasi & Produk',
    'quick_actions' => 'Tindakan Cepat',
    'performance' => 'Kinerja',
    'product_link' => 'Tautan Produk',
    'general_link' => 'Tautan Umum',

    // Performance indicators
    'excellent' => 'Sangat Baik',
    'good' => 'Baik',
    'average' => 'Rata-rata',
    'no_data' => 'Tidak Ada Data',

    'back' => 'Kembali',

    // Status messages
    'affiliate_not_found' => 'Afiliasi tidak ditemukan',
    'short_link_not_found' => 'Tautan pendek tidak ditemukan',

    // Table headers
    'affiliate_column' => 'Afiliasi',
    'title_column' => 'Judul & Kode',
    'destination_column' => 'Tujuan',
    'short_url_column' => 'URL Pendek',
    'product_column' => 'Produk',
    'stats_column' => 'Statistik',
    'created_column' => 'Dibuat',

    // Bulk actions
    'bulk_delete_confirm' => 'Apakah Anda yakin ingin menghapus tautan pendek ini?',
    'bulk_delete_success' => 'Tautan pendek yang dipilih telah berhasil dihapus.',

    // Permissions
    'permissions' => [
        'index' => 'Lihat tautan pendek',
        'create' => 'Buat tautan pendek',
        'edit' => 'Edit tautan pendek',
        'destroy' => 'Hapus tautan pendek',
    ],
];
