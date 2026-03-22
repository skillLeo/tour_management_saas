<?php

return [
    'name' => 'קישורים קצרים',
    'short_link' => 'קישור קצר',
    'short_links' => 'קישורים קצרים',
    'create' => 'צור קישור קצר',
    'edit' => 'ערוך קישור קצר',
    'delete' => 'מחק קישור קצר',
    'short_link_details' => 'פרטי קישור קצר',
    'untitled' => 'קישור ללא כותרת',

    // Form fields
    'affiliate' => 'שותף',
    'title' => 'כותרת',
    'title_placeholder' => 'הזן כותרת תיאורית לקישור הקצר הזה',
    'short_code' => 'קוד קצר',
    'short_code_placeholder' => 'הזן קוד קצר ייחודי (למשל, summer2024)',
    'short_code_help' => 'זה ישמש ב-URL: yoursite.com/go/[short-code]',
    'destination_url' => 'URL יעד',
    'destination_url_help' => 'ה-URL המלא לאן המשתמשים יופנו',
    'product' => 'מוצר',
    'product_help' => 'אופציונלי: קשר את הקישור הקצר הזה למוצר ספציפי למעקב טוב יותר',
    'all_products' => 'כל המוצרים',
    'short_url' => 'URL קצר',
    'clicks' => 'לחיצות',
    'conversions' => 'המרות',
    'conversion_rate' => 'שיעור המרה',
    'total_clicks' => 'סה"כ לחיצות',
    'total_conversions' => 'סה"כ המרות',
    'statistics' => 'סטטיסטיקות',
    'actions' => 'פעולות',
    'created_at' => 'נוצר בתאריך',
    'updated_at' => 'עודכן בתאריך',

    // Validation messages
    'affiliate_required' => 'אנא בחר שותף.',
    'affiliate_not_exists' => 'השותף שנבחר אינו קיים.',
    'short_code_required' => 'קוד קצר נדרש.',
    'short_code_unique' => 'קוד קצר זה כבר תפוס.',
    'short_code_alpha_dash' => 'קוד קצר יכול להכיל רק אותיות, מספרים, מקפים וקווים תחתונים.',
    'destination_url_required' => 'URL יעד נדרש.',
    'destination_url_invalid' => 'אנא הזן URL תקין.',
    'product_not_exists' => 'המוצר שנבחר אינו קיים.',

    // Actions
    'copy_url' => 'העתק URL',
    'copy_short_url' => 'העתק URL קצר',
    'test_link' => 'בדוק קישור',
    'visit_destination' => 'בקר ביעד',
    'copied_to_clipboard' => 'URL הועתק ללוח!',
    'copy_failed' => 'נכשל בהעתקת URL. אנא נסה שוב.',

    // UI sections
    'url_information' => 'מידע URL',
    'affiliate_product_info' => 'מידע שותף ומוצר',
    'quick_actions' => 'פעולות מהירות',
    'performance' => 'ביצועים',
    'product_link' => 'קישור מוצר',
    'general_link' => 'קישור כללי',

    // Performance indicators
    'excellent' => 'מצוין',
    'good' => 'טוב',
    'average' => 'ממוצע',
    'no_data' => 'אין נתונים',

    'back' => 'חזרה',

    // Status messages
    'affiliate_not_found' => 'שותף לא נמצא',
    'short_link_not_found' => 'קישור קצר לא נמצא',

    // Table headers
    'affiliate_column' => 'שותף',
    'title_column' => 'כותרת וקוד',
    'destination_column' => 'יעד',
    'short_url_column' => 'URL קצר',
    'product_column' => 'מוצר',
    'stats_column' => 'סטטיסטיקות',
    'created_column' => 'נוצר',

    // Bulk actions
    'bulk_delete_confirm' => 'האם אתה בטוח שברצונך למחוק את הקישורים הקצרים האלה?',
    'bulk_delete_success' => 'הקישורים הקצרים שנבחרו נמחקו בהצלחה.',

    // Permissions
    'permissions' => [
        'index' => 'צפה בקישורים קצרים',
        'create' => 'צור קישורים קצרים',
        'edit' => 'ערוך קישורים קצרים',
        'destroy' => 'מחק קישורים קצרים',
    ],
];
