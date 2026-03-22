<?php

return [
    'name' => 'Короткие ссылки',
    'short_link' => 'Короткая ссылка',
    'short_links' => 'Короткие ссылки',
    'create' => 'Создать короткую ссылку',
    'edit' => 'Редактировать короткую ссылку',
    'delete' => 'Удалить короткую ссылку',
    'short_link_details' => 'Детали короткой ссылки',
    'untitled' => 'Безымянная ссылка',

    // Form fields
    'affiliate' => 'Партнёр',
    'title' => 'Заголовок',
    'title_placeholder' => 'Введите описательный заголовок для этой короткой ссылки',
    'short_code' => 'Короткий код',
    'short_code_placeholder' => 'Введите уникальный короткий код (например, leto2024)',
    'short_code_help' => 'Это будет использоваться в URL: vashsite.com/go/[korotkij-kod]',
    'destination_url' => 'Целевой URL',
    'destination_url_help' => 'Полный URL, на который будут перенаправлены пользователи',
    'product' => 'Товар',
    'product_help' => 'Необязательно: Свяжите эту короткую ссылку с конкретным товаром для лучшего отслеживания',
    'all_products' => 'Все товары',
    'short_url' => 'Короткий URL',
    'clicks' => 'Клики',
    'conversions' => 'Конверсии',
    'conversion_rate' => 'Коэффициент конверсии',
    'total_clicks' => 'Всего кликов',
    'total_conversions' => 'Всего конверсий',
    'statistics' => 'Статистика',
    'actions' => 'Действия',
    'created_at' => 'Создано',
    'updated_at' => 'Обновлено',

    // Validation messages
    'affiliate_required' => 'Пожалуйста, выберите партнёра.',
    'affiliate_not_exists' => 'Выбранный партнёр не существует.',
    'short_code_required' => 'Короткий код обязателен.',
    'short_code_unique' => 'Этот короткий код уже занят.',
    'short_code_alpha_dash' => 'Короткий код может содержать только буквы, цифры, дефисы и подчёркивания.',
    'destination_url_required' => 'Целевой URL обязателен.',
    'destination_url_invalid' => 'Пожалуйста, введите действительный URL.',
    'product_not_exists' => 'Выбранный товар не существует.',

    // Actions
    'copy_url' => 'Копировать URL',
    'copy_short_url' => 'Копировать короткий URL',
    'test_link' => 'Проверить ссылку',
    'visit_destination' => 'Посетить назначение',
    'copied_to_clipboard' => 'URL скопирован в буфер обмена!',
    'copy_failed' => 'Не удалось скопировать URL. Попробуйте ещё раз.',

    // UI sections
    'url_information' => 'Информация об URL',
    'affiliate_product_info' => 'Информация о партнёре и товаре',
    'quick_actions' => 'Быстрые действия',
    'performance' => 'Производительность',
    'product_link' => 'Ссылка на товар',
    'general_link' => 'Общая ссылка',

    // Performance indicators
    'excellent' => 'Отлично',
    'good' => 'Хорошо',
    'average' => 'Средне',
    'no_data' => 'Нет данных',

    'back' => 'Назад',

    // Status messages
    'affiliate_not_found' => 'Партнёр не найден',
    'short_link_not_found' => 'Короткая ссылка не найдена',

    // Table headers
    'affiliate_column' => 'Партнёр',
    'title_column' => 'Заголовок и код',
    'destination_column' => 'Назначение',
    'short_url_column' => 'Короткий URL',
    'product_column' => 'Товар',
    'stats_column' => 'Статистика',
    'created_column' => 'Создано',

    // Bulk actions
    'bulk_delete_confirm' => 'Вы уверены, что хотите удалить эти короткие ссылки?',
    'bulk_delete_success' => 'Выбранные короткие ссылки успешно удалены.',

    // Permissions
    'permissions' => [
        'index' => 'Просмотр коротких ссылок',
        'create' => 'Создание коротких ссылок',
        'edit' => 'Редактирование коротких ссылок',
        'destroy' => 'Удаление коротких ссылок',
    ],
];
