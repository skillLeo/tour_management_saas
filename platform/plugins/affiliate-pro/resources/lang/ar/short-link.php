<?php

return [
    'name' => 'الروابط المختصرة',
    'short_link' => 'رابط مختصر',
    'short_links' => 'الروابط المختصرة',
    'create' => 'إنشاء رابط مختصر',
    'edit' => 'تحرير الرابط المختصر',
    'delete' => 'حذف الرابط المختصر',
    'short_link_details' => 'تفاصيل الرابط المختصر',
    'untitled' => 'رابط بدون عنوان',

    // Form fields
    'affiliate' => 'المسوق بالعمولة',
    'title' => 'العنوان',
    'title_placeholder' => 'أدخل عنواناً وصفياً لهذا الرابط المختصر',
    'short_code' => 'الكود المختصر',
    'short_code_placeholder' => 'أدخل كوداً مختصراً فريداً (مثال: summer2024)',
    'short_code_help' => 'سيتم استخدام هذا في URL: yoursite.com/go/[short-code]',
    'destination_url' => 'URL الوجهة',
    'destination_url_help' => 'عنوان URL الكامل حيث سيتم توجيه المستخدمين',
    'product' => 'المنتج',
    'product_help' => 'اختياري: ربط هذا الرابط المختصر بمنتج معين لتتبع أفضل',
    'all_products' => 'جميع المنتجات',
    'short_url' => 'URL المختصر',
    'clicks' => 'النقرات',
    'conversions' => 'التحويلات',
    'conversion_rate' => 'معدل التحويل',
    'total_clicks' => 'إجمالي النقرات',
    'total_conversions' => 'إجمالي التحويلات',
    'statistics' => 'الإحصائيات',
    'actions' => 'الإجراءات',
    'created_at' => 'تاريخ الإنشاء',
    'updated_at' => 'تاريخ التحديث',

    // Validation messages
    'affiliate_required' => 'يرجى اختيار مسوق بالعمولة.',
    'affiliate_not_exists' => 'المسوق بالعمولة المحدد غير موجود.',
    'short_code_required' => 'الكود المختصر مطلوب.',
    'short_code_unique' => 'هذا الكود المختصر مستخدم بالفعل.',
    'short_code_alpha_dash' => 'يمكن أن يحتوي الكود المختصر على حروف وأرقام وشرطات وشرطات سفلية فقط.',
    'destination_url_required' => 'URL الوجهة مطلوب.',
    'destination_url_invalid' => 'يرجى إدخال URL صالح.',
    'product_not_exists' => 'المنتج المحدد غير موجود.',

    // Actions
    'copy_url' => 'نسخ URL',
    'copy_short_url' => 'نسخ URL المختصر',
    'test_link' => 'اختبار الرابط',
    'visit_destination' => 'زيارة الوجهة',
    'copied_to_clipboard' => 'تم نسخ URL إلى الحافظة!',
    'copy_failed' => 'فشل نسخ URL. يرجى المحاولة مرة أخرى.',

    // UI sections
    'url_information' => 'معلومات URL',
    'affiliate_product_info' => 'معلومات المسوق بالعمولة والمنتج',
    'quick_actions' => 'إجراءات سريعة',
    'performance' => 'الأداء',
    'product_link' => 'رابط المنتج',
    'general_link' => 'رابط عام',

    // Performance indicators
    'excellent' => 'ممتاز',
    'good' => 'جيد',
    'average' => 'متوسط',
    'no_data' => 'لا توجد بيانات',

    'back' => 'رجوع',

    // Status messages
    'affiliate_not_found' => 'المسوق بالعمولة غير موجود',
    'short_link_not_found' => 'الرابط المختصر غير موجود',

    // Table headers
    'affiliate_column' => 'المسوق بالعمولة',
    'title_column' => 'العنوان والكود',
    'destination_column' => 'الوجهة',
    'short_url_column' => 'URL المختصر',
    'product_column' => 'المنتج',
    'stats_column' => 'الإحصائيات',
    'created_column' => 'تاريخ الإنشاء',

    // Bulk actions
    'bulk_delete_confirm' => 'هل أنت متأكد من حذف هذه الروابط المختصرة؟',
    'bulk_delete_success' => 'تم حذف الروابط المختصرة المحددة بنجاح.',

    // Permissions
    'permissions' => [
        'index' => 'عرض الروابط المختصرة',
        'create' => 'إنشاء الروابط المختصرة',
        'edit' => 'تحرير الروابط المختصرة',
        'destroy' => 'حذف الروابط المختصرة',
    ],
];
