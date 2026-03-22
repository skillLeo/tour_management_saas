<?php

return [
    'name' => 'Къси линкове',
    'short_link' => 'Къс линк',
    'short_links' => 'Къси линкове',
    'create' => 'Създаване на къс линк',
    'edit' => 'Редактиране на къс линк',
    'delete' => 'Изтриване на къс линк',
    'short_link_details' => 'Детайли на къс линк',
    'untitled' => 'Линк без заглавие',

    // Form fields
    'affiliate' => 'Партньор',
    'title' => 'Заглавие',
    'title_placeholder' => 'Въведете описателно заглавие за този къс линк',
    'short_code' => 'Къс код',
    'short_code_placeholder' => 'Въведете уникален къс код (напр., лято2024)',
    'short_code_help' => 'Това ще се използва в URL адреса: вашсайт.com/go/[къс-код]',
    'destination_url' => 'Целев URL',
    'destination_url_help' => 'Пълният URL адрес, към който ще бъдат пренасочени потребителите',
    'product' => 'Продукт',
    'product_help' => 'Незадължително: Свържете този къс линк с конкретен продукт за по-добро проследяване',
    'all_products' => 'Всички продукти',
    'short_url' => 'Къс URL',
    'clicks' => 'Кликвания',
    'conversions' => 'Преобразувания',
    'conversion_rate' => 'Процент на преобразуване',
    'total_clicks' => 'Общо кликвания',
    'total_conversions' => 'Общо преобразувания',
    'statistics' => 'Статистика',
    'actions' => 'Действия',
    'created_at' => 'Създаден на',
    'updated_at' => 'Актуализиран на',

    // Validation messages
    'affiliate_required' => 'Моля, изберете партньор.',
    'affiliate_not_exists' => 'Избраният партньор не съществува.',
    'short_code_required' => 'Късият код е задължителен.',
    'short_code_unique' => 'Този къс код вече е зает.',
    'short_code_alpha_dash' => 'Късият код може да съдържа само букви, цифри, тирета и долни черти.',
    'destination_url_required' => 'Целевият URL е задължителен.',
    'destination_url_invalid' => 'Моля, въведете валиден URL.',
    'product_not_exists' => 'Избраният продукт не съществува.',

    // Actions
    'copy_url' => 'Копиране на URL',
    'copy_short_url' => 'Копиране на къс URL',
    'test_link' => 'Тестване на линк',
    'visit_destination' => 'Посетете дестинацията',
    'copied_to_clipboard' => 'URL адресът е копиран в клипборда!',
    'copy_failed' => 'Неуспешно копиране на URL. Моля, опитайте отново.',

    // UI sections
    'url_information' => 'URL информация',
    'affiliate_product_info' => 'Информация за партньор и продукт',
    'quick_actions' => 'Бързи действия',
    'performance' => 'Ефективност',
    'product_link' => 'Линк към продукт',
    'general_link' => 'Общ линк',

    // Performance indicators
    'excellent' => 'Отличен',
    'good' => 'Добър',
    'average' => 'Среден',
    'no_data' => 'Няма данни',

    'back' => 'Назад',

    // Status messages
    'affiliate_not_found' => 'Партньорът не е намерен',
    'short_link_not_found' => 'Късият линк не е намерен',

    // Table headers
    'affiliate_column' => 'Партньор',
    'title_column' => 'Заглавие и код',
    'destination_column' => 'Дестинация',
    'short_url_column' => 'Къс URL',
    'product_column' => 'Продукт',
    'stats_column' => 'Статистика',
    'created_column' => 'Създаден',

    // Bulk actions
    'bulk_delete_confirm' => 'Сигурни ли сте, че искате да изтриете тези къси линкове?',
    'bulk_delete_success' => 'Избраните къси линкове са изтрити успешно.',

    // Permissions
    'permissions' => [
        'index' => 'Преглед на къси линкове',
        'create' => 'Създаване на къси линкове',
        'edit' => 'Редактиране на къси линкове',
        'destroy' => 'Изтриване на къси линкове',
    ],
];
