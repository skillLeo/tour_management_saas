<?php

return [
    'name' => 'Kısa Bağlantılar',
    'short_link' => 'Kısa Bağlantı',
    'short_links' => 'Kısa Bağlantılar',
    'create' => 'Kısa Bağlantı Oluştur',
    'edit' => 'Kısa Bağlantıyı Düzenle',
    'delete' => 'Kısa Bağlantıyı Sil',
    'short_link_details' => 'Kısa Bağlantı Detayları',
    'untitled' => 'Başlıksız Bağlantı',

    // Form fields
    'affiliate' => 'Bayi',
    'title' => 'Başlık',
    'title_placeholder' => 'Bu kısa bağlantı için açıklayıcı bir başlık girin',
    'short_code' => 'Kısa Kod',
    'short_code_placeholder' => 'Benzersiz kısa kod girin (örn: summer2024)',
    'short_code_help' => 'Bu URL\'de kullanılacak: yoursite.com/go/[short-code]',
    'destination_url' => 'Hedef URL',
    'destination_url_help' => 'Kullanıcıların yönlendirileceği tam URL',
    'product' => 'Ürün',
    'product_help' => 'İsteğe bağlı: Daha iyi takip için bu kısa bağlantıyı belirli bir ürüne bağlayın',
    'all_products' => 'Tüm Ürünler',
    'short_url' => 'Kısa URL',
    'clicks' => 'Tıklamalar',
    'conversions' => 'Dönüşümler',
    'conversion_rate' => 'Dönüşüm Oranı',
    'total_clicks' => 'Toplam Tıklama',
    'total_conversions' => 'Toplam Dönüşüm',
    'statistics' => 'İstatistikler',
    'actions' => 'İşlemler',
    'created_at' => 'Oluşturulma Tarihi',
    'updated_at' => 'Güncellenme Tarihi',

    // Validation messages
    'affiliate_required' => 'Lütfen bir bayi seçin.',
    'affiliate_not_exists' => 'Seçilen bayi mevcut değil.',
    'short_code_required' => 'Kısa kod gereklidir.',
    'short_code_unique' => 'Bu kısa kod zaten kullanılıyor.',
    'short_code_alpha_dash' => 'Kısa kod yalnızca harf, rakam, tire ve alt çizgi içerebilir.',
    'destination_url_required' => 'Hedef URL gereklidir.',
    'destination_url_invalid' => 'Lütfen geçerli bir URL girin.',
    'product_not_exists' => 'Seçilen ürün mevcut değil.',

    // Actions
    'copy_url' => 'URL\'yi Kopyala',
    'copy_short_url' => 'Kısa URL\'yi Kopyala',
    'test_link' => 'Bağlantıyı Test Et',
    'visit_destination' => 'Hedefe Git',
    'copied_to_clipboard' => 'URL panoya kopyalandı!',
    'copy_failed' => 'URL kopyalanamadı. Lütfen tekrar deneyin.',

    // UI sections
    'url_information' => 'URL Bilgisi',
    'affiliate_product_info' => 'Bayi ve Ürün Bilgisi',
    'quick_actions' => 'Hızlı İşlemler',
    'performance' => 'Performans',
    'product_link' => 'Ürün Bağlantısı',
    'general_link' => 'Genel Bağlantı',

    // Performance indicators
    'excellent' => 'Mükemmel',
    'good' => 'İyi',
    'average' => 'Ortalama',
    'no_data' => 'Veri Yok',

    'back' => 'Geri',

    // Status messages
    'affiliate_not_found' => 'Bayi bulunamadı',
    'short_link_not_found' => 'Kısa bağlantı bulunamadı',

    // Table headers
    'affiliate_column' => 'Bayi',
    'title_column' => 'Başlık ve Kod',
    'destination_column' => 'Hedef',
    'short_url_column' => 'Kısa URL',
    'product_column' => 'Ürün',
    'stats_column' => 'İstatistikler',
    'created_column' => 'Oluşturuldu',

    // Bulk actions
    'bulk_delete_confirm' => 'Bu kısa bağlantıları silmek istediğinizden emin misiniz?',
    'bulk_delete_success' => 'Seçilen kısa bağlantılar başarıyla silindi.',

    // Permissions
    'permissions' => [
        'index' => 'Kısa bağlantıları görüntüle',
        'create' => 'Kısa bağlantı oluştur',
        'edit' => 'Kısa bağlantıları düzenle',
        'destroy' => 'Kısa bağlantıları sil',
    ],
];
