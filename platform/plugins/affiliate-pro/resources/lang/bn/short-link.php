<?php

return [
    'name' => 'সংক্ষিপ্ত লিংক',
    'short_link' => 'সংক্ষিপ্ত লিংক',
    'short_links' => 'সংক্ষিপ্ত লিংক',
    'create' => 'সংক্ষিপ্ত লিংক তৈরি করুন',
    'edit' => 'সংক্ষিপ্ত লিংক সম্পাদনা করুন',
    'delete' => 'সংক্ষিপ্ত লিংক মুছুন',
    'short_link_details' => 'সংক্ষিপ্ত লিংক বিস্তারিত',
    'untitled' => 'শিরোনামহীন লিংক',

    // Form fields
    'affiliate' => 'অ্যাফিলিয়েট',
    'title' => 'শিরোনাম',
    'title_placeholder' => 'এই সংক্ষিপ্ত লিংকের জন্য একটি বর্ণনামূলক শিরোনাম লিখুন',
    'short_code' => 'সংক্ষিপ্ত কোড',
    'short_code_placeholder' => 'অনন্য সংক্ষিপ্ত কোড লিখুন (যেমন: summer2024)',
    'short_code_help' => 'এটি URL-এ ব্যবহার করা হবে: yoursite.com/go/[short-code]',
    'destination_url' => 'গন্তব্য URL',
    'destination_url_help' => 'সম্পূর্ণ URL যেখানে ব্যবহারকারীদের পুনঃনির্দেশিত করা হবে',
    'product' => 'পণ্য',
    'product_help' => 'ঐচ্ছিক: আরও ভালো ট্র্যাকিংয়ের জন্য এই সংক্ষিপ্ত লিংকটিকে একটি নির্দিষ্ট পণ্যের সাথে লিংক করুন',
    'all_products' => 'সকল পণ্য',
    'short_url' => 'সংক্ষিপ্ত URL',
    'clicks' => 'ক্লিক',
    'conversions' => 'রূপান্তর',
    'conversion_rate' => 'রূপান্তর হার',
    'total_clicks' => 'মোট ক্লিক',
    'total_conversions' => 'মোট রূপান্তর',
    'statistics' => 'পরিসংখ্যান',
    'actions' => 'কার্যক্রম',
    'created_at' => 'তৈরির তারিখ',
    'updated_at' => 'আপডেটের তারিখ',

    // Validation messages
    'affiliate_required' => 'অনুগ্রহ করে একটি অ্যাফিলিয়েট নির্বাচন করুন।',
    'affiliate_not_exists' => 'নির্বাচিত অ্যাফিলিয়েট বিদ্যমান নেই।',
    'short_code_required' => 'সংক্ষিপ্ত কোড প্রয়োজন।',
    'short_code_unique' => 'এই সংক্ষিপ্ত কোডটি ইতিমধ্যে নেওয়া হয়েছে।',
    'short_code_alpha_dash' => 'সংক্ষিপ্ত কোডে শুধুমাত্র অক্ষর, সংখ্যা, ড্যাশ এবং আন্ডারস্কোর থাকতে পারে।',
    'destination_url_required' => 'গন্তব্য URL প্রয়োজন।',
    'destination_url_invalid' => 'অনুগ্রহ করে একটি বৈধ URL লিখুন।',
    'product_not_exists' => 'নির্বাচিত পণ্য বিদ্যমান নেই।',

    // Actions
    'copy_url' => 'URL কপি করুন',
    'copy_short_url' => 'সংক্ষিপ্ত URL কপি করুন',
    'test_link' => 'লিংক পরীক্ষা করুন',
    'visit_destination' => 'গন্তব্য পরিদর্শন করুন',
    'copied_to_clipboard' => 'URL ক্লিপবোর্ডে কপি করা হয়েছে!',
    'copy_failed' => 'URL কপি করতে ব্যর্থ। অনুগ্রহ করে আবার চেষ্টা করুন।',

    // UI sections
    'url_information' => 'URL তথ্য',
    'affiliate_product_info' => 'অ্যাফিলিয়েট এবং পণ্য তথ্য',
    'quick_actions' => 'দ্রুত কার্যক্রম',
    'performance' => 'কর্মক্ষমতা',
    'product_link' => 'পণ্য লিংক',
    'general_link' => 'সাধারণ লিংক',

    // Performance indicators
    'excellent' => 'চমৎকার',
    'good' => 'ভাল',
    'average' => 'গড়',
    'no_data' => 'কোনো ডেটা নেই',

    'back' => 'ফিরে যান',

    // Status messages
    'affiliate_not_found' => 'অ্যাফিলিয়েট পাওয়া যায়নি',
    'short_link_not_found' => 'সংক্ষিপ্ত লিংক পাওয়া যায়নি',

    // Table headers
    'affiliate_column' => 'অ্যাফিলিয়েট',
    'title_column' => 'শিরোনাম এবং কোড',
    'destination_column' => 'গন্তব্য',
    'short_url_column' => 'সংক্ষিপ্ত URL',
    'product_column' => 'পণ্য',
    'stats_column' => 'পরিসংখ্যান',
    'created_column' => 'তৈরি হয়েছে',

    // Bulk actions
    'bulk_delete_confirm' => 'আপনি কি নিশ্চিত যে এই সংক্ষিপ্ত লিংকগুলি মুছতে চান?',
    'bulk_delete_success' => 'নির্বাচিত সংক্ষিপ্ত লিংকগুলি সফলভাবে মুছে ফেলা হয়েছে।',

    // Permissions
    'permissions' => [
        'index' => 'সংক্ষিপ্ত লিংক দেখুন',
        'create' => 'সংক্ষিপ্ত লিংক তৈরি করুন',
        'edit' => 'সংক্ষিপ্ত লিংক সম্পাদনা করুন',
        'destroy' => 'সংক্ষিপ্ত লিংক মুছুন',
    ],
];
