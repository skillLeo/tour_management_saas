<?php

use Botble\Base\Facades\BaseHelper;

if (!function_exists('format_tour_price')) {
    function format_tour_price($price, $currency = null)
    {
        // استخدام دالة format_price من البرنامج الرئيسي إن وجدت
        if (function_exists('format_price')) {
            return format_price($price);
        }
        
        // الحصول على العملة الحالية للتطبيق
        $applicationCurrency = $currency ?? 
            (function_exists('get_application_currency') 
                ? get_application_currency() 
                : null);
            
        if ($applicationCurrency) {
            $symbol = $applicationCurrency->symbol ?? '€';
            $decimals = $applicationCurrency->decimals ?? 2;
            
            // التحقق من موضع رمز العملة
            if (property_exists($applicationCurrency, 'is_prefix_symbol') && $applicationCurrency->is_prefix_symbol) {
                return $symbol . number_format($price, $decimals);
            } else {
                return number_format($price, $decimals) . ' ' . $symbol;
            }
        }
        
        // حالة الفولباك النهائية
        return number_format($price, 2) . ' €';
    }
}