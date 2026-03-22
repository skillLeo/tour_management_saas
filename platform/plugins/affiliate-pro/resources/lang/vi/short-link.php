<?php

return [
    'name' => 'Liên kết rút gọn',
    'short_link' => 'Liên kết rút gọn',
    'short_links' => 'Liên kết rút gọn',
    'create' => 'Tạo liên kết rút gọn',
    'edit' => 'Chỉnh sửa liên kết rút gọn',
    'delete' => 'Xóa liên kết rút gọn',
    'short_link_details' => 'Chi tiết liên kết rút gọn',
    'untitled' => 'Liên kết không có tiêu đề',

    // Form fields
    'affiliate' => 'Đối tác',
    'title' => 'Tiêu đề',
    'title_placeholder' => 'Nhập tiêu đề mô tả cho liên kết rút gọn này',
    'short_code' => 'Mã rút gọn',
    'short_code_placeholder' => 'Nhập mã rút gọn duy nhất (ví dụ: summer2024)',
    'short_code_help' => 'Mã này sẽ được sử dụng trong URL: yoursite.com/go/[short-code]',
    'destination_url' => 'URL đích',
    'destination_url_help' => 'URL đầy đủ nơi người dùng sẽ được chuyển hướng',
    'product' => 'Sản phẩm',
    'product_help' => 'Tùy chọn: Liên kết liên kết rút gọn này với một sản phẩm cụ thể để theo dõi tốt hơn',
    'all_products' => 'Tất cả sản phẩm',
    'short_url' => 'URL rút gọn',
    'clicks' => 'Lượt nhấp',
    'conversions' => 'Chuyển đổi',
    'conversion_rate' => 'Tỷ lệ chuyển đổi',
    'total_clicks' => 'Tổng lượt nhấp',
    'total_conversions' => 'Tổng chuyển đổi',
    'statistics' => 'Thống kê',
    'actions' => 'Hành động',
    'created_at' => 'Ngày tạo',
    'updated_at' => 'Ngày cập nhật',

    // Validation messages
    'affiliate_required' => 'Vui lòng chọn đối tác.',
    'affiliate_not_exists' => 'Đối tác đã chọn không tồn tại.',
    'short_code_required' => 'Mã rút gọn là bắt buộc.',
    'short_code_unique' => 'Mã rút gọn này đã được sử dụng.',
    'short_code_alpha_dash' => 'Mã rút gọn chỉ có thể chứa chữ cái, số, dấu gạch ngang và gạch dưới.',
    'destination_url_required' => 'URL đích là bắt buộc.',
    'destination_url_invalid' => 'Vui lòng nhập một URL hợp lệ.',
    'product_not_exists' => 'Sản phẩm đã chọn không tồn tại.',

    // Actions
    'copy_url' => 'Sao chép URL',
    'copy_short_url' => 'Sao chép URL rút gọn',
    'test_link' => 'Kiểm tra liên kết',
    'visit_destination' => 'Truy cập đích',
    'copied_to_clipboard' => 'Đã sao chép URL vào clipboard!',
    'copy_failed' => 'Không thể sao chép URL. Vui lòng thử lại.',

    // UI sections
    'url_information' => 'Thông tin URL',
    'affiliate_product_info' => 'Thông tin đối tác & sản phẩm',
    'quick_actions' => 'Hành động nhanh',
    'performance' => 'Hiệu suất',
    'product_link' => 'Liên kết sản phẩm',
    'general_link' => 'Liên kết chung',

    // Performance indicators
    'excellent' => 'Xuất sắc',
    'good' => 'Tốt',
    'average' => 'Trung bình',
    'no_data' => 'Không có dữ liệu',

    'back' => 'Quay lại',

    // Status messages
    'affiliate_not_found' => 'Không tìm thấy đối tác',
    'short_link_not_found' => 'Không tìm thấy liên kết rút gọn',

    // Table headers
    'affiliate_column' => 'Đối tác',
    'title_column' => 'Tiêu đề & mã',
    'destination_column' => 'Đích',
    'short_url_column' => 'URL rút gọn',
    'product_column' => 'Sản phẩm',
    'stats_column' => 'Thống kê',
    'created_column' => 'Đã tạo',

    // Bulk actions
    'bulk_delete_confirm' => 'Bạn có chắc chắn muốn xóa các liên kết rút gọn này không?',
    'bulk_delete_success' => 'Các liên kết rút gọn đã chọn đã được xóa thành công.',

    // Permissions
    'permissions' => [
        'index' => 'Xem liên kết rút gọn',
        'create' => 'Tạo liên kết rút gọn',
        'edit' => 'Chỉnh sửa liên kết rút gọn',
        'destroy' => 'Xóa liên kết rút gọn',
    ],
];
