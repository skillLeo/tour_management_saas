<?php

return [
    'name' => '短縮リンク',
    'short_link' => '短縮リンク',
    'short_links' => '短縮リンク',
    'create' => '短縮リンクを作成',
    'edit' => '短縮リンクを編集',
    'delete' => '短縮リンクを削除',
    'short_link_details' => '短縮リンク詳細',
    'untitled' => '無題のリンク',

    // Form fields
    'affiliate' => 'アフィリエイト',
    'title' => 'タイトル',
    'title_placeholder' => 'この短縮リンクの説明的なタイトルを入力',
    'short_code' => '短縮コード',
    'short_code_placeholder' => '一意の短縮コードを入力（例: summer2024）',
    'short_code_help' => 'これはURLで使用されます: yoursite.com/go/[short-code]',
    'destination_url' => '宛先URL',
    'destination_url_help' => 'ユーザーがリダイレクトされる完全なURL',
    'product' => '製品',
    'product_help' => 'オプション: この短縮リンクを特定の製品にリンクして、より良いトラッキングを実現',
    'all_products' => 'すべての製品',
    'short_url' => '短縮URL',
    'clicks' => 'クリック数',
    'conversions' => 'コンバージョン数',
    'conversion_rate' => 'コンバージョン率',
    'total_clicks' => '総クリック数',
    'total_conversions' => '総コンバージョン数',
    'statistics' => '統計',
    'actions' => 'アクション',
    'created_at' => '作成日',
    'updated_at' => '更新日',

    // Validation messages
    'affiliate_required' => 'アフィリエイトを選択してください。',
    'affiliate_not_exists' => '選択されたアフィリエイトは存在しません。',
    'short_code_required' => '短縮コードは必須です。',
    'short_code_unique' => 'この短縮コードは既に使用されています。',
    'short_code_alpha_dash' => '短縮コードは文字、数字、ダッシュ、アンダースコアのみ使用できます。',
    'destination_url_required' => '宛先URLは必須です。',
    'destination_url_invalid' => '有効なURLを入力してください。',
    'product_not_exists' => '選択された製品は存在しません。',

    // Actions
    'copy_url' => 'URLをコピー',
    'copy_short_url' => '短縮URLをコピー',
    'test_link' => 'リンクをテスト',
    'visit_destination' => '宛先を訪問',
    'copied_to_clipboard' => 'URLがクリップボードにコピーされました！',
    'copy_failed' => 'URLのコピーに失敗しました。もう一度お試しください。',

    // UI sections
    'url_information' => 'URL情報',
    'affiliate_product_info' => 'アフィリエイトと製品情報',
    'quick_actions' => 'クイックアクション',
    'performance' => 'パフォーマンス',
    'product_link' => '製品リンク',
    'general_link' => '一般リンク',

    // Performance indicators
    'excellent' => '優秀',
    'good' => '良好',
    'average' => '平均',
    'no_data' => 'データなし',

    'back' => '戻る',

    // Status messages
    'affiliate_not_found' => 'アフィリエイトが見つかりませんでした',
    'short_link_not_found' => '短縮リンクが見つかりませんでした',

    // Table headers
    'affiliate_column' => 'アフィリエイト',
    'title_column' => 'タイトルとコード',
    'destination_column' => '宛先',
    'short_url_column' => '短縮URL',
    'product_column' => '製品',
    'stats_column' => '統計',
    'created_column' => '作成日',

    // Bulk actions
    'bulk_delete_confirm' => 'これらの短縮リンクを削除してもよろしいですか？',
    'bulk_delete_success' => '選択された短縮リンクが正常に削除されました。',

    // Permissions
    'permissions' => [
        'index' => '短縮リンクを表示',
        'create' => '短縮リンクを作成',
        'edit' => '短縮リンクを編集',
        'destroy' => '短縮リンクを削除',
    ],
];
