<?php

return [
    'name' => '단축 링크',
    'short_link' => '단축 링크',
    'short_links' => '단축 링크',
    'create' => '단축 링크 생성',
    'edit' => '단축 링크 편집',
    'delete' => '단축 링크 삭제',
    'short_link_details' => '단축 링크 세부정보',
    'untitled' => '제목 없는 링크',

    // Form fields
    'affiliate' => '제휴',
    'title' => '제목',
    'title_placeholder' => '이 단축 링크에 대한 설명적 제목 입력',
    'short_code' => '단축 코드',
    'short_code_placeholder' => '고유한 단축 코드 입력(예: summer2024)',
    'short_code_help' => '이것은 URL에서 사용됩니다: yoursite.com/go/[short-code]',
    'destination_url' => '대상 URL',
    'destination_url_help' => '사용자가 리디렉션될 전체 URL',
    'product' => '제품',
    'product_help' => '선택사항: 더 나은 추적을 위해 이 단축 링크를 특정 제품에 연결',
    'all_products' => '모든 제품',
    'short_url' => '단축 URL',
    'clicks' => '클릭 수',
    'conversions' => '전환 수',
    'conversion_rate' => '전환율',
    'total_clicks' => '총 클릭 수',
    'total_conversions' => '총 전환 수',
    'statistics' => '통계',
    'actions' => '작업',
    'created_at' => '생성일',
    'updated_at' => '업데이트일',

    // Validation messages
    'affiliate_required' => '제휴 파트너를 선택하세요.',
    'affiliate_not_exists' => '선택한 제휴 파트너가 존재하지 않습니다.',
    'short_code_required' => '단축 코드는 필수입니다.',
    'short_code_unique' => '이 단축 코드는 이미 사용 중입니다.',
    'short_code_alpha_dash' => '단축 코드는 문자, 숫자, 대시, 밑줄만 포함할 수 있습니다.',
    'destination_url_required' => '대상 URL은 필수입니다.',
    'destination_url_invalid' => '유효한 URL을 입력하세요.',
    'product_not_exists' => '선택한 제품이 존재하지 않습니다.',

    // Actions
    'copy_url' => 'URL 복사',
    'copy_short_url' => '단축 URL 복사',
    'test_link' => '링크 테스트',
    'visit_destination' => '대상 방문',
    'copied_to_clipboard' => 'URL이 클립보드에 복사되었습니다!',
    'copy_failed' => 'URL 복사에 실패했습니다. 다시 시도하세요.',

    // UI sections
    'url_information' => 'URL 정보',
    'affiliate_product_info' => '제휴 및 제품 정보',
    'quick_actions' => '빠른 작업',
    'performance' => '성과',
    'product_link' => '제품 링크',
    'general_link' => '일반 링크',

    // Performance indicators
    'excellent' => '우수',
    'good' => '좋음',
    'average' => '평균',
    'no_data' => '데이터 없음',

    'back' => '뒤로',

    // Status messages
    'affiliate_not_found' => '제휴 파트너를 찾을 수 없습니다',
    'short_link_not_found' => '단축 링크를 찾을 수 없습니다',

    // Table headers
    'affiliate_column' => '제휴',
    'title_column' => '제목 및 코드',
    'destination_column' => '대상',
    'short_url_column' => '단축 URL',
    'product_column' => '제품',
    'stats_column' => '통계',
    'created_column' => '생성일',

    // Bulk actions
    'bulk_delete_confirm' => '이 단축 링크들을 삭제하시겠습니까?',
    'bulk_delete_success' => '선택한 단축 링크가 성공적으로 삭제되었습니다.',

    // Permissions
    'permissions' => [
        'index' => '단축 링크 보기',
        'create' => '단축 링크 생성',
        'edit' => '단축 링크 편집',
        'destroy' => '단축 링크 삭제',
    ],
];
