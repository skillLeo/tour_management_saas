@php
    Theme::set('pageTitle', __('Tours'));
    Theme::set('pageName', __('Tours'));
@endphp

<link rel="stylesheet" href="{{ asset('vendor/core/plugins/tours/css/cities-slider.css') }}">
<script src="{{ asset('vendor/core/plugins/tours/js/cities-slider.js') }}"></script>

<style>
/* Tours Page Custom Styles */
.tours-hero-section {
       background: url('https://images.pexels.com/photos/1371360/pexels-photo-1371360.jpeg?cs=srgb&dl=pexels-te-lensfix-380994-1371360.jpg&fm=jpg');
    background-size: cover;
    background-position: center center;
    background-repeat: no-repeat;
    background-attachment: fixed;
    color: white;
    padding: 80px 0;
    margin-bottom: 50px;
    position: relative;
    overflow: hidden;
}

.tours-hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
    background-size: cover;
    opacity: 0.3;
}

.tours-hero-content {
    position: relative;
    z-index: 2;
    text-align: center;
}

.tours-hero-title {
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 20px;
    color:white;
    animation: fadeInUp 0.8s ease;
}

.tours-hero-subtitle {
    font-size: 1.3rem;
    opacity: 0.9;
        color:white;
    margin-bottom: 30px;
    animation: fadeInUp 0.8s ease 0.2s;
    animation-fill-mode: both;
}

.tours-search-box {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 15px;
    padding: 30px;
    margin-top: 40px;
    animation: fadeInUp 0.8s ease 0.4s;
    animation-fill-mode: both;
}

.tours-search-form {
    display: flex;
    gap: 15px;
    align-items: end;
    flex-wrap: wrap;
}

.search-field {
    flex: 1;
    min-width: 200px;
}

.search-field label {
    display: block;
    color: #333;
    font-weight: 600;
    margin-bottom: 8px;
}

.search-field input,
.search-field select {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 16px;
    transition: all 0.3s ease;
}

.search-field input:focus,
.search-field select:focus {
    border-color: var(--color-brand);
    outline: none;
    box-shadow: 0 0 0 3px rgba(var(--color-brand-rgb, 102, 126, 234), 0.1);
}

.search-btn {
    background: linear-gradient(135deg, var(--color-brand) 0%, var(--color-brand-2, #764ba2) 100%);
    color: white;
    border: none;
    padding: 12px 30px;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    height: fit-content;
}

.search-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(var(--color-brand-rgb, 102, 126, 234), 0.4);
}

/* Tour Cards */
.tour-card-modern {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 8px 30px rgba(0,0,0,0.08);
    transition: all 0.4s ease;
    margin-bottom: 30px;
    position: relative;
}

.tour-card-modern:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 50px rgba(0,0,0,0.15);
}

.tour-image-container {
    position: relative;
    height: 280px;
    overflow: hidden;
}

.tour-image-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s ease;
}

.tour-card-modern:hover .tour-image-container img {
    transform: scale(1.1);
}

.tour-badge-featured {
    position: absolute;
    top: 20px;
    left: 20px;
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    z-index: 2;
    animation: pulse 2s infinite;
}

.tour-badge-discount {
    position: absolute;
    top: 20px;
    right: 20px;
    background: linear-gradient(135deg, #ffd93d 0%, #ff9f43 100%);
    color: #333;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    z-index: 2;
}

.tour-card-content {
    padding: 25px;
}

.tour-category-link {
    color: var(--color-brand);
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.tour-title-link {
    color: #333;
    text-decoration: none;
    font-size: 1.4rem;
    font-weight: 700;
    line-height: 1.4;
    margin: 10px 0 15px 0;
    display: block;
    transition: color 0.3s ease;
}

.tour-title-link:hover {
    color: var(--color-brand);
}

.tour-meta-info {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.tour-meta-item {
    display: flex;
    align-items: center;
    color: #666;
    font-size: 14px;
}

.tour-meta-item i {
    margin-right: 6px;
    color: var(--color-brand);
    font-size: 16px;
}

.tour-price-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.tour-price-display {
    display: flex;
    flex-direction: column;
}

.tour-current-price {
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--color-brand);
}

        .tour-old-price {
            font-size: 1.1rem;
            color: #999;
            text-decoration: line-through;
        }
        
        .discount-badge {
            background: #dc3545;
            color: white;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 10px;
            font-weight: 600;
            margin-left: 5px;
        }

.tour-book-button {
    background: linear-gradient(135deg, var(--color-brand) 0%, var(--color-brand-2, #764ba2) 100%);
    color: white;
    padding: 12px 24px;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.tour-book-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(var(--color-brand-rgb, 102, 126, 234), 0.4);
    color: white;
    text-decoration: none;
}

.tour-book-button.loading {
    opacity: 0.7;
    pointer-events: none;
}

.tour-book-button.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
}

/* Sidebar Styles */
.tours-sidebar {
    background: white;
    border-radius: 15px;
    padding: 0;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    margin-bottom: 30px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.tours-sidebar:hover {
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    transform: translateY(-2px);
}

.sidebar-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: white;
    margin: 0;
    padding: 15px 20px;
    background: linear-gradient(135deg, var(--color-brand) 0%, var(--color-brand-2, #764ba2) 100%);
    position: relative;
}

.tours-sidebar > div:not(.sidebar-title) {
    padding: 20px;
}

.category-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.category-list li {
    margin-bottom: 8px;
    padding: 12px 15px;
    background: #f8f9fa;
    border-radius: 8px;
    transition: all 0.3s ease;
    border: 1px solid transparent;
}

.category-list li:hover {
    background: var(--color-brand);
    transform: translateX(5px);
    border-color: var(--color-brand);
    box-shadow: 0 2px 8px rgba(var(--color-brand-rgb, 102, 126, 234), 0.2);
}

.category-list li:hover a {
    color: white;
}

.category-list a {
    color: #333;
    text-decoration: none;
    font-weight: 500;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: color 0.3s ease;
    font-size: 14px;
    line-height: 1.4;
    word-break: break-word;
}

.category-count {
    background: #e0e0e0;
    color: #666;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    min-width: 20px;
    text-align: center;
    flex-shrink: 0;
    margin-left: 8px;
}

.category-list li:hover .category-count {
    background: white;
    color: var(--color-brand);
}

/* Sidebar Forms and Inputs */
.location-filter-container,
.price-filter-container,
.type-filter-container,
.length-filter-container,
.language-filter-container {
    margin: 20px 0;
}

.search-field {
    margin-bottom: 15px;
}

.search-field label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
}

.search-field input,
.search-field select {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    font-size: 14px;
    transition: all 0.3s ease;
    box-sizing: border-box;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background: #fff url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="%23666" viewBox="0 0 16 16"><path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/></svg>') no-repeat calc(100% - 15px) center;
    background-size: 12px;
    cursor: pointer;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

.search-field input:focus,
.search-field select:focus {
    border-color: var(--color-brand);
    outline: none;
    box-shadow: 0 0 0 3px rgba(var(--color-brand-rgb, 102, 126, 234), 0.1);
}

.search-field select:hover {
    border-color: var(--color-brand);
}

/* Custom styling for filter sections */
.tours-sidebar {
    margin-bottom: 25px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    border-radius: 15px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.tours-sidebar:hover {
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    transform: translateY(-2px);
}

.sidebar-title {
    background: linear-gradient(135deg, var(--color-brand) 0%, var(--color-brand-2, #764ba2) 100%);
    color: white;
    padding: 15px 20px;
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    border-radius: 15px 15px 0 0;
}

.price-range-slider {
    margin-bottom: 20px;
}

.price-slider-labels {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    font-size: 14px;
    color: #666;
}

.price-range-value {
    font-weight: 600;
    color: var(--color-brand);
}

.price-inputs {
    display: flex;
    gap: 15px;
}

.price-input-group {
    flex: 1;
}

.price-input-group label {
    display: block;
    font-size: 12px;
    color: #666;
    margin-bottom: 5px;
}

.price-inputs input {
    width: 100%;
    padding: 10px 12px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    font-size: 14px;
    box-sizing: border-box;
    text-align: center;
}

.price-inputs input:focus {
    border-color: var(--color-brand);
    outline: none;
    box-shadow: 0 0 0 3px rgba(var(--color-brand-rgb, 102, 126, 234), 0.1);
}

.price-quick-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin: 15px 0;
}

.price-quick-filter {
    padding: 6px 12px;
    border-radius: 20px;
    background: #f8f9fa;
    border: 1px solid #e0e0e0;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 12px;
    color: #666;
    text-align: center;
    flex: 1;
    min-width: 80px;
}

.price-quick-filter:hover {
    background: #f0f0f0;
    transform: translateY(-2px);
}

.price-quick-filter.active {
    border-color: var(--color-brand);
    background: rgba(var(--color-brand-rgb, 102, 126, 234), 0.1);
    color: var(--color-brand);
    font-weight: 600;
}

.filter-apply-btn {
    width: 100%;
    background: linear-gradient(135deg, var(--color-brand) 0%, var(--color-brand-2, #764ba2) 100%);
    color: white;
    border: none;
    padding: 12px 16px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    margin-top: 15px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    white-space: nowrap;
    text-overflow: ellipsis;
    overflow: hidden;
}

.filter-apply-btn i {
    margin-right: 8px;
    font-size: 14px;
    flex-shrink: 0;
}

.filter-apply-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(var(--color-brand-rgb, 102, 126, 234), 0.3);
}

/* Featured Tours */
.featured-tour-item {
    display: flex;
    gap: 15px;
    padding: 15px 0;
    border-bottom: 1px solid #eee;
    transition: all 0.3s ease;
}

.featured-tour-item:last-child {
    border-bottom: none;
}

.featured-tour-item:hover {
    transform: translateY(-2px);
    padding-left: 5px;
}

.featured-tour-image {
    flex-shrink: 0;
    width: 70px;
    height: 70px;
    border-radius: 8px;
    overflow: hidden;
}

.featured-tour-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.featured-tour-item:hover .featured-tour-image img {
    transform: scale(1.1);
}

.featured-tour-content {
    flex: 1;
    min-width: 0; /* Prevent overflow */
}

.featured-tour-content h6 {
    margin: 0 0 8px 0;
    font-size: 14px;
    line-height: 1.3;
}

.featured-tour-content h6 a {
    color: #333;
    text-decoration: none;
    transition: color 0.3s ease;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}

.featured-tour-content h6 a:hover {
    color: var(--color-brand);
}

.featured-tour-price {
    font-size: 14px;
    font-weight: 700;
    color: var(--color-brand);
}

.featured-tour-item:hover {
    background: #e9ecef;
    transform: translateY(-2px);
}

.featured-tour-image {
    width: 80px;
    height: 80px;
    border-radius: 8px;
    overflow: hidden;
    flex-shrink: 0;
}

.featured-tour-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.featured-tour-content h6 {
    margin: 0 0 8px 0;
    font-size: 14px;
    font-weight: 600;
}

.featured-tour-content a {
    color: #333;
    text-decoration: none;
}

.featured-tour-content a:hover {
    color: var(--color-brand);
}

.featured-tour-price {
    color: var(--color-brand);
    font-weight: 700;
    font-size: 16px;
}

/* No Tours Found */
.no-tours-container {
    text-align: center;
    padding: 80px 20px;
    background: white;
    border-radius: 20px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

.no-tours-icon {
    font-size: 5rem;
    color: #ddd;
    margin-bottom: 30px;
}

.no-tours-title {
    font-size: 2rem;
    color: #666;
    margin-bottom: 15px;
}

.no-tours-text {
    color: #999;
    font-size: 1.1rem;
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

/* Responsive Design */

/* Large tablets and small desktops */
@media (max-width: 1199px) {
    .tour-image-container {
        height: 250px;
    }
    
    .tour-card-content {
        padding: 20px;
    }
    
    .tour-title-link {
        font-size: 1.2rem;
    }
}

/* Tablets */
@media (max-width: 991px) {
    .tours-hero-section {
        padding: 60px 0;
        background-attachment: scroll; /* Fix for mobile browsers */
    }
    
    .tours-hero-title {
        font-size: 2.8rem;
    }
    
    .tours-search-box {
        padding: 20px;
    }
    
    .tour-image-container {
        height: 220px;
    }
    
    .tour-card-content {
        padding: 18px;
    }
    
    .tour-title-link {
        font-size: 1.1rem;
        margin: 8px 0 12px 0;
    }
    
    .tour-meta-info {
        gap: 15px;
        margin-bottom: 15px;
    }
    
    .tour-current-price {
        font-size: 1.4rem;
    }
    
    .tour-book-button {
        padding: 10px 20px;
        font-size: 14px;
        min-height: 44px; /* Better touch target */
    }
    
    /* Better touch targets for tablets */
    .search-btn {
        min-height: 44px;
        padding: 12px 24px;
    }
    
    .search-field input,
    .search-field select {
        min-height: 44px;
        padding: 12px 15px;
    }
    
    /* Sidebar improvements for tablets */
    .tours-sidebar {
        padding: 25px;
    }
    
    .filter-apply-btn {
        min-height: 44px;
        padding: 12px 20px;
        font-size: 14px;
    }
    
    .category-list li {
        padding: 14px 16px;
    }
    
    .category-list a {
        font-size: 14px;
    }
    
    .featured-tour-item {
        padding: 16px 0;
    }
}

/* Mobile phones */
@media (max-width: 768px) {
    .tours-hero-section {
        padding: 40px 0;
        background-attachment: scroll;
        background-size: cover;
        background-position: center;
    }
    
    .tours-hero-title {
        font-size: 2.2rem;
        margin-bottom: 15px;
    }
    
    .tours-hero-subtitle {
        font-size: 1.1rem;
        margin-bottom: 20px;
    }
    
    .tours-search-box {
        padding: 15px;
        margin-top: 20px;
    }
    
    .tours-search-form {
        flex-direction: column;
        gap: 10px;
    }
    
    .search-field {
        min-width: 100%;
        margin-bottom: 5px;
    }
    
    .search-btn {
        padding: 12px 20px;
        width: 100%;
    }
    
    /* Tour cards mobile optimization */
    .tour-card-modern {
        margin-bottom: 20px;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        min-height: auto;
    }
    
    .tour-card-modern:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    
    .tour-image-container {
        height: 200px;
    }
    
    .tour-card-content {
        padding: 15px;
    }
    
    .tour-category-link {
        font-size: 12px;
    }
    
    .tour-title-link {
        font-size: 1rem;
        line-height: 1.3;
        margin: 6px 0 10px 0;
    }
    
    .tour-meta-info {
        flex-direction: column;
        gap: 8px;
        margin-bottom: 12px;
    }
    
    .tour-meta-item {
        font-size: 13px;
    }
    
    .tour-meta-item i {
        font-size: 14px;
        margin-right: 5px;
    }
    
    .tour-price-section {
        flex-direction: column;
        gap: 12px;
        align-items: stretch;
        padding-top: 15px;
    }
    
    .tour-current-price {
        font-size: 1.3rem;
        text-align: center;
    }
    
    .tour-old-price {
        font-size: 1rem;
        text-align: center;
    }
    
    .tour-book-button {
        padding: 12px 20px;
        border-radius: 20px;
        text-align: center;
        width: 100%;
        font-size: 14px;
    }
    
    /* Sidebar adjustments */
    .tours-sidebar {
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .sidebar-title {
        font-size: 1.1rem;
        margin-bottom: 15px;
    }
    
    /* Filter button improvements for mobile */
    .filter-apply-btn {
        padding: 14px 16px;
        font-size: 14px;
        min-height: 48px;
    }
    
    .filter-apply-btn i {
        margin-right: 6px;
    }
    
    /* Input field improvements */
    .search-field input,
    .search-field select {
        padding: 12px;
        font-size: 16px; /* Prevent zoom on iOS */
        min-height: 44px;
    }
    
    .price-inputs input {
        padding: 12px;
        font-size: 16px;
        min-height: 44px;
    }
    
    /* Featured tours mobile */
    .featured-tour-item {
        padding: 12px 0;
    }
    
    .featured-tour-image {
        width: 60px;
        height: 60px;
    }
    
    .featured-tour-content h6 {
        font-size: 13px;
    }
    
    .featured-tour-price {
        font-size: 13px;
    }
    
    /* Grid adjustments for mobile */
    .row.flex-row-reverse {
        flex-direction: column-reverse;
    }
    
    .primary-sidebar {
        margin-top: 30px;
    }
    
    /* Rating adjustments */
    .tour-rating-section {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
        margin: 8px 0;
    }
    
    .tour-stars .star {
        font-size: 14px;
    }
    
    .tour-rating-text {
        font-size: 12px;
    }
}

/* Small phones */
@media (max-width: 576px) {
    .tours-hero-title {
        font-size: 1.8rem;
    }
    
    .tours-hero-subtitle {
        font-size: 1rem;
    }
    
    .tours-search-box {
        padding: 12px;
        margin-top: 15px;
    }
    
    .tour-image-container {
        height: 180px;
    }
    
    .tour-card-content {
        padding: 12px;
    }
    
    .tour-title-link {
        font-size: 0.95rem;
    }
    
    .tour-current-price {
        font-size: 1.2rem;
    }
    
    .tour-book-button {
        padding: 10px 16px;
        font-size: 13px;
    }
    
    .container {
        padding-left: 10px;
        padding-right: 10px;
    }
    
    .product-grid {
        margin-left: -10px;
        margin-right: -10px;
    }
    
    .product-grid > [class*="col-"] {
        padding-left: 10px;
        padding-right: 10px;
    }
    
    .tours-sidebar {
        padding: 15px;
    }
    
    /* Sidebar content improvements for small phones */
    .category-list li {
        padding: 10px 12px;
        margin-bottom: 6px;
    }
    
    .category-list a {
        font-size: 13px;
    }
    
    .category-count {
        font-size: 10px;
        padding: 3px 6px;
    }
    
    .filter-apply-btn {
        padding: 12px 14px;
        font-size: 13px;
        min-height: 44px;
    }
    
    .search-field input,
    .search-field select {
        padding: 10px;
        font-size: 16px;
    }
    
    .price-inputs input {
        padding: 10px;
        font-size: 16px;
    }
    
    .featured-tour-item {
        padding: 10px 0;
        gap: 12px;
    }
    
    .featured-tour-image {
        width: 50px;
        height: 50px;
    }
    
    .featured-tour-content h6 {
        font-size: 12px;
    }
    
    .featured-tour-price {
        font-size: 12px;
    }
}

/* Grid and Layout Fixes */
.product-grid {
    margin-left: -15px;
    margin-right: -15px;
}

.product-grid > [class*="col-"] {
    padding-left: 15px;
    padding-right: 15px;
}

/* Ensure equal height cards */
.product-grid .tour-card-modern {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.product-grid .tour-card-content {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.product-grid .tour-price-section {
    margin-top: auto;
}

/* Rating Styles */
.tour-rating-section {
    margin: 10px 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.tour-stars {
    display: flex;
    align-items: center;
    gap: 2px;
}

.tour-stars .star {
    font-size: 16px;
    line-height: 1;
}

.tour-stars .star.filled {
    color: #ffc107;
}

.tour-stars .star.half {
    color: #ffc107;
}

.tour-stars .star.empty {
    color: #e0e0e0;
}

.tour-rating-text {
    font-size: 13px;
    color: #666;
    font-weight: 500;
}

.tour-rating-text.no-reviews {
    color: #999;
    font-style: italic;
}

/* Tour Languages Styles */
.tour-languages-info {
    margin: 10px 0;
}

.language-flags {
    display: flex;
    align-items: center;
    gap: 5px;
    margin-left: 5px;
}

.language-flag {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #e0e0e0;
    background: #f5f5f5;
}

.language-flag img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.language-code {
    font-size: 10px;
    font-weight: bold;
    color: #666;
}

.more-languages {
    background: var(--color-brand);
    color: white;
    font-size: 10px;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
}

.tour-meta-item {
    display: flex;
    align-items: center;
    gap: 5px;
}

/* Custom Filter Styles */
.filter-select {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    font-size: 14px;
    transition: all 0.3s ease;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background: #fff url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="%23666" viewBox="0 0 16 16"><path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/></svg>') no-repeat calc(100% - 15px) center;
    background-size: 12px;
    cursor: pointer;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

.filter-select:focus {
    border-color: var(--color-brand);
    outline: none;
    box-shadow: 0 0 0 3px rgba(var(--color-brand-rgb, 102, 126, 234), 0.1);
}

.filter-icons {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 15px;
}

.filter-icon-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 10px;
    border-radius: 10px;
    background: #f8f9fa;
    border: 2px solid transparent;
    cursor: pointer;
    transition: all 0.3s ease;
    flex: 1;
    min-width: 80px;
    text-align: center;
}

.filter-icon-item:hover {
    background: #f0f0f0;
    transform: translateY(-2px);
}

.filter-icon-item.active {
    border-color: var(--color-brand);
    background: rgba(var(--color-brand-rgb, 102, 126, 234), 0.1);
}

.filter-icon {
    width: 40px;
    height: 40px;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.filter-icon-item:hover .filter-icon {
    transform: scale(1.1);
}

.filter-icon-item.active .filter-icon {
    background: var(--color-brand);
    color: white;
}

.filter-icon i {
    font-size: 18px;
    color: #666;
}

.filter-icon-item.active .filter-icon i {
    color: white;
}

.filter-icon-item span {
    font-size: 12px;
    font-weight: 500;
    color: #666;
    margin-top: 5px;
}

.filter-icon-item.active span {
    color: var(--color-brand);
    font-weight: 600;
}

.filter-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 15px;
}

.filter-btn {
    padding: 8px 15px;
    border-radius: 20px;
    background: #f8f9fa;
    border: 2px solid transparent;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 13px;
    font-weight: 500;
    color: #666;
    display: flex;
    align-items: center;
    gap: 5px;
}

.filter-btn:hover {
    background: #f0f0f0;
    transform: translateY(-2px);
}

.filter-btn.active {
    border-color: var(--color-brand);
    background: rgba(var(--color-brand-rgb, 102, 126, 234), 0.1);
    color: var(--color-brand);
    font-weight: 600;
}

.filter-btn i {
    font-size: 14px;
}

.language-filter-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 15px;
}

.language-chip {
    padding: 6px 12px;
    border-radius: 20px;
    background: #f8f9fa;
    border: 1px solid #e0e0e0;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 13px;
    color: #666;
    display: flex;
    align-items: center;
    gap: 5px;
}

.language-chip:hover {
    background: #f0f0f0;
    transform: translateY(-2px);
}

.language-chip.active {
    border-color: var(--color-brand);
    background: rgba(var(--color-brand-rgb, 102, 126, 234), 0.1);
    color: var(--color-brand);
    font-weight: 600;
}

.language-chip-flag {
    width: 16px;
    height: 16px;
    border-radius: 50%;
    object-fit: cover;
}

.language-chip.more-languages {
    background: var(--color-brand);
    color: white;
    border-color: var(--color-brand);
}

.language-chip.more-languages:hover {
    background: var(--color-brand-2, #764ba2);
    border-color: var(--color-brand-2, #764ba2);
}

/* Lightbox */
.lightbox-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.9);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.lightbox-image {
    max-width: 90vw;
    max-height: 90vh;
    border-radius: 8px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.5);
    transition: opacity 0.25s ease;
}

.lightbox-close,
.lightbox-prev,
.lightbox-next {
    position: absolute;
    background: rgba(255,255,255,0.15);
    color: #fff;
    border: none;
    cursor: pointer;
    border-radius: 50%;
    width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    transition: background 0.2s ease;
}

.lightbox-close { top: 20px; right: 20px; }
.lightbox-prev  { left: 20px; }
.lightbox-next  { right: 20px; }

.lightbox-prev,
.lightbox-next {
    top: 50%;
    transform: translateY(-50%);
}

.lightbox-close:hover,
.lightbox-prev:hover,
.lightbox-next:hover {
    background: rgba(255,255,255,0.25);
}

.lightbox-counter {
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    color: #fff;
    font-size: 14px;
    opacity: 0.8;
}
</style>

<!-- Hero Section -->
<div class="tours-hero-section">
    <div class="container">
        <div class="tours-hero-content">
            <h1 class="tours-hero-title">{{ __('Discover Amazing Tours') }}</h1>
            <p class="tours-hero-subtitle">{{ __('Explore the world with our carefully curated travel experiences and create unforgettable memories') }}</p>
            
            <div class="tours-search-box">
                <form class="tours-search-form" method="GET" action="{{ route('public.tours.index') }}">
                    <div class="search-field">
                        <label>{{ __('City') }}</label>
                        <select name="location">
                            <option value="">{{ __('All Cities') }}</option>
                            @foreach($citiesWithTours as $city)
                                <option value="{{ $city->name }}" {{ request('location') == $city->name ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="search-field">
                        <label>{{ __('Category') }}</label>
                        <select name="category">
                            <option value="">{{ __('All Categories') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="search-field">
                        <label>{{ __('Type') }}</label>
                        <select name="tour_type">
                            <option value="">{{ __('All Types') }}</option>
                            @foreach($tourTypes as $value => $label)
                                <option value="{{ $value }}" {{ request('tour_type') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="search-field">
                        <label>{{ __('Length') }}</label>
                        <select name="tour_length">
                            <option value="">{{ __('All Lengths') }}</option>
                            @foreach($tourLengths as $value => $label)
                                <option value="{{ $value }}" {{ request('tour_length') == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="search-field">
                        <label>{{ __('Language') }}</label>
                        <select name="language">
                            <option value="">{{ __('All Languages') }}</option>
                            @foreach($languages as $language)
                                <option value="{{ $language->id }}" {{ request('language') == $language->id ? 'selected' : '' }}>
                                    {{ $language->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="search-btn">
                        <i class="fi-rs-search"></i> {{ __('Search Tours') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Cities Slider Section -->
<div class="cities-slider-section">
    <div class="container">
        <h2 class="cities-slider-title">{{ __('Explore Popular Destinations') }}</h2>
        
        <div class="cities-slider-container">
            <!-- Left Arrow -->
            <div class="slider-arrow slider-arrow-left d-none">
                <i class="fi-rs-angle-left"></i>
            </div>
            
            <!-- Cities Slider -->
            <div class="cities-slider">
                @foreach($cities as $city)
                <a href="{{ route('public.tours.index', ['city' => $city->id]) }}" class="city-card">
                    <div class="city-card-image">
                        <img src="{{ RvMedia::getImageUrl($city->image, 'medium', false, RvMedia::getDefaultImage()) }}" alt="{{ $city->name }}">
                        <div class="city-card-overlay">
                            <h3 class="city-card-name">{{ $city->name }}</h3>
                            <div class="city-card-tours">
                                {{ $city->tours_count ?? 0 }} {{ __('Tours') }}
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            
            <!-- Right Arrow -->
            <div class="slider-arrow slider-arrow-right">
                <i class="fi-rs-angle-right"></i>
            </div>
        </div>
        
        <!-- View All Cities Button -->
        <div class="text-center mt-4">
            <a href="{{ route('public.cities.index') }}" class="btn btn-primary btn-lg">
                <i class="fi-rs-marker me-2"></i>
                {{ __('View All Cities') }}
            </a>
        </div>
    </div>
</div>

<div class="container mb-30">
    <div class="row flex-row-reverse">
        <div class="col-lg-9">
      
            <!-- Tours Grid -->
            <div class="row product-grid">
                @forelse($tours as $tour)
                    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="tour-card-modern wow animate__animated animate__fadeIn" data-wow-delay=".1s">
                            <div class="tour-image-container">
                                <img src="{{ RvMedia::getImageUrl($tour->image, 'medium', false, RvMedia::getDefaultImage()) }}" alt="{{ $tour->name }}" />
                                
                                @if($tour->is_featured)
                                    <div class="tour-badge-featured">
                                        <i class="fi-rs-star"></i> {{ __('Featured') }}
                                    </div>
                                @endif
                                

                            </div>
                            
                            <div class="tour-card-content">
                                <a href="{{ route('public.tours.category', $tour->category->slug ?? '') }}" class="tour-category-link">
                                    {{ $tour->category->name ?? __('Adventure') }}
                                </a>
                              
                                @if(!empty($tour->slug))
                                    <a href="{{ route('public.tours.detail', $tour->slug) }}" class="tour-title-link">
                                        {{ $tour->name }}
                                    </a>
                                @else
                                    <span class="tour-title-link">{{ $tour->name }}</span>
                                @endif
                                
                                <!-- Rating Section -->
                                <div class="tour-rating-section">
                                    @if($tour->reviews_count > 0)
                                        <div class="tour-stars">
                                            @php
                                                $averageRating = $tour->average_rating;
                                                $fullStars = floor($averageRating);
                                                $halfStar = ($averageRating - $fullStars) >= 0.5;
                                                $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                                            @endphp
                                            @for($i = 0; $i < $fullStars; $i++)
                                                <span class="star filled">★</span>
                                            @endfor
                                            @if($halfStar)
                                                <span class="star half">☆</span>
                                            @endif
                                            @for($i = 0; $i < $emptyStars; $i++)
                                                <span class="star empty">☆</span>
                                            @endfor
                                        </div>
                                        <span class="tour-rating-text">
                                            {{ number_format($tour->average_rating, 1) }} ({{ $tour->reviews_count }} {{ $tour->reviews_count == 1 ? __('review') : __('reviews') }})
                                        </span>
                                    @else
                                        <span class="tour-rating-text no-reviews">{{ __('No reviews yet') }}</span>
                                    @endif
                                </div>
                                
                                <div class="tour-meta-info">
                                    @if($tour->city)
                                        <div class="tour-meta-item">
                                            <i class="fi-rs-building"></i>
                                            {{ $tour->city->name }}
                                        </div>
                                    @endif
                                    @if($tour->location)
                                        <div class="tour-meta-item">
                                            <i class="fi-rs-marker"></i>
                                            {{ $tour->location }}
                                        </div>
                                    @endif
                                    <div class="tour-meta-item">
                                        <i class="fi-rs-clock"></i>
                                        @if(!empty($tour->duration_hours) && $tour->duration_hours > 0)
                                            {{ $tour->duration_hours }} {{ __('plugins/tours::tours.hours') }}
                                        @else
                                            {{ $tour->duration_days }} {{ __('days') }}
                                        @endif
                                    </div>
                                    @if($tour->max_people)
                                        <div class="tour-meta-item">
                                            <i class="fi-rs-users"></i>
                                            {{ __('Max') }} {{ $tour->max_people }}
                                        </div>
                                    @endif
                                </div>
                                
                                @if($tour->languages->count() > 0)
                                <div class="tour-languages-info">
                                    <div class="tour-meta-item">
                                        <i class="fi-rs-language"></i>
                                        <span>{{ __('Languages') }}:</span>
                                        <div class="language-flags">
                                            @foreach($tour->languages->take(3) as $language)
                                                <div class="language-flag" title="{{ $language->name }}">
                                                    @if($language->flag)
                                                        <img src="{{ RvMedia::getImageUrl($language->flag, 'thumb') }}" alt="{{ $language->name }}">
                                                    @else
                                                        <span class="language-code">{{ strtoupper(substr($language->code, 0, 2)) }}</span>
                                                    @endif
                                                </div>
                                            @endforeach
                                            @if($tour->languages->count() > 3)
                                                <div class="language-flag more-languages" title="{{ __('More languages') }}">
                                                    <span>+{{ $tour->languages->count() - 3 }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endif
                        
                                <div class="tour-price-section">
                                    <div class="tour-price-display">
                                        @if($tour->has_discount)
                                            <span class="tour-current-price">{{ format_tour_price($tour->current_price) }}</span>
                                            <span class="tour-old-price">{{ format_tour_price($tour->price) }}</span>
                                            <div class="discount-badge">{{ $tour->sale_percentage }}% OFF</div>
                                        @else
                                            <span class="tour-current-price">{{ format_tour_price($tour->current_price) }}</span>
                                        @endif
                                    </div>
                             
                                    @if(!empty($tour->slug))
                                        <a href="{{ route('public.tours.detail', $tour->slug) }}" class="tour-book-button">
                                            <i class="fi-rs-shopping-cart mr-5"></i>{{ __('Book Now') }}
                                        </a>
                                    @else
                                        <span class="tour-book-button disabled">
                                            <i class="fi-rs-shopping-cart mr-5"></i>{{ __('Book Now') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="no-tours-container">
                            <div class="no-tours-icon">
                                <i class="fi-rs-map"></i>
                            </div>
                            <h3 class="no-tours-title">{{ __('No tours found') }}</h3>
                            <p class="no-tours-text">{{ __('Sorry, we could not find any tours matching your criteria. Try adjusting your filters.') }}</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="pagination-area mt-20 mb-20">
                <nav aria-label="Page navigation example">
                    {{ $tours->withQueryString()->links() }}
                </nav>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-3 primary-sidebar sticky-sidebar">
            <!-- Categories -->
            <div class="tours-sidebar">
                <h5 class="sidebar-title">{{ __('Tour Categories') }}</h5>
                <ul class="category-list">
                    @foreach($categories as $category)
                        <li>
                            <a href="{{ route('public.tours.category', $category->slug) }}">
                                {{ $category->name }}
                                <span class="category-count">{{ $category->tours_count }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Locations Filter -->
            <div class="tours-sidebar">
                <h5 class="sidebar-title">{{ __('Filter by Location') }}</h5>
                <div class="location-filter-container">
                    <form method="GET" action="{{ route('public.tours.index') }}" id="location-filter-form">
                        @foreach(request()->except(['location', 'departure_location', 'return_location']) as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        
                        <div class="search-field mb-3">
                            <label>{{ __('City') }}</label>
                            <select name="location" class="filter-select">
                                <option value="">{{ __('All Cities') }}</option>
                                @foreach($citiesWithTours as $city)
                                    <option value="{{ $city->name }}" {{ request('location') == $city->name ? 'selected' : '' }}>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="search-field mb-3">
                            <label>{{ __('Departure From') }}</label>
                            <input type="text" name="departure_location" placeholder="{{ __('Departure location') }}" value="{{ request('departure_location') }}">
                        </div>
                        
                        <div class="search-field mb-3">
                            <label>{{ __('Return To') }}</label>
                            <input type="text" name="return_location" placeholder="{{ __('Return location') }}" value="{{ request('return_location') }}">
                        </div>
                        
                        <button type="submit" class="filter-apply-btn">
                            <i class="fi-rs-marker mr-5"></i> {{ __('Filter by Location') }}
                        </button>
                    </form>
                </div>
                
                @if(isset($popularLocations) && count($popularLocations) > 0)
                <div class="mt-3">
                    <h6 class="mb-2">{{ __('Popular Destinations') }}</h6>
                    <ul class="category-list">
                        @foreach($popularLocations as $location)
                            <li>
                                <a href="{{ route('public.tours.index', ['location' => $location->location]) }}">
                                    {{ $location->location }}
                                    <span class="category-count">{{ $location->count }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                @if(isset($popularDepartures) && count($popularDepartures) > 0)
                <div class="mt-3">
                    <h6 class="mb-2">{{ __('Popular Departure Points') }}</h6>
                    <ul class="category-list">
                        @foreach($popularDepartures as $departure)
                            <li>
                                <a href="{{ route('public.tours.index', ['departure_location' => $departure->departure_location]) }}">
                                    {{ $departure->departure_location }}
                                    <span class="category-count">{{ $departure->count }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>

            <!-- Type Filter -->
            <div class="tours-sidebar">
                <h5 class="sidebar-title">{{ __('Filter by Type') }}</h5>
                <div class="type-filter-container">
                    <form method="GET" action="{{ route('public.tours.index') }}" id="type-filter-form">
                        @foreach(request()->except(['tour_type']) as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        
                        <div class="search-field mb-3">
                            <select name="tour_type" class="filter-select" onchange="this.form.submit()">
                                <option value="">{{ __('Select Tour Type') }}</option>
                                @foreach($tourTypes as $value => $label)
                                    <option value="{{ $value }}" {{ request('tour_type') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="filter-icons">
                            @foreach($tourTypes as $value => $label)
                                <div class="filter-icon-item {{ request('tour_type') == $value ? 'active' : '' }}" 
                                     onclick="document.querySelector('#type-filter-form select').value='{{ $value }}'; document.querySelector('#type-filter-form').submit();">
                                    <div class="filter-icon">
                                        @if($value == 'shared')
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                        @elseif($value == 'private')
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                        @elseif($value == 'transfer')
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"></rect><path d="M2 10h20"></path><path d="M6 15h4"></path><path d="M14 15h4"></path></svg>
                                        @elseif($value == 'small_group')
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                        @endif
                                    </div>
                                    <span>{{ $label }}</span>
                                </div>
                            @endforeach
                            <div class="filter-icon-item {{ !request('tour_type') ? 'active' : '' }}"
                                 onclick="document.querySelector('#type-filter-form select').value=''; document.querySelector('#type-filter-form').submit();">
                                <div class="filter-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.5 2v6h-6M2.5 22v-6h6M2 11.5a10 10 0 0 1 18.8-4.3M22 12.5a10 10 0 0 1-18.8 4.2"></path></svg>
                                </div>
                                <span>{{ __('All Types') }}</span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Length Filter -->
            <div class="tours-sidebar">
                <h5 class="sidebar-title">{{ __('Filter by Length') }}</h5>
                <div class="length-filter-container">
                    <form method="GET" action="{{ route('public.tours.index') }}" id="length-filter-form">
                        @foreach(request()->except(['tour_length']) as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        
                        <div class="search-field mb-3">
                            <select name="tour_length" class="filter-select" onchange="this.form.submit()">
                                <option value="">{{ __('Select Tour Length') }}</option>
                                @foreach($tourLengths as $value => $label)
                                    <option value="{{ $value }}" {{ request('tour_length') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="filter-buttons">
                            @foreach($tourLengths as $value => $label)
                                <button type="button" 
                                        class="filter-btn {{ request('tour_length') == $value ? 'active' : '' }}"
                                        onclick="document.querySelector('#length-filter-form select').value='{{ $value }}'; document.querySelector('#length-filter-form').submit();">
                                    @if($value == 'half_day')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                    @elseif($value == 'full_day')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 16"></polyline></svg>
                                    @endif
                                    {{ $label }}
                                </button>
                            @endforeach
                            <button type="button" 
                                    class="filter-btn {{ !request('tour_length') ? 'active' : '' }}"
                                    onclick="document.querySelector('#length-filter-form select').value=''; document.querySelector('#length-filter-form').submit();">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.5 2v6h-6M2.5 22v-6h6M2 11.5a10 10 0 0 1 18.8-4.3M22 12.5a10 10 0 0 1-18.8 4.2"></path></svg>
                                {{ __('All Lengths') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Language Filter -->
            <div class="tours-sidebar">
                <h5 class="sidebar-title">{{ __('Filter by Language') }}</h5>
                <div class="language-filter-container">
                    <form method="GET" action="{{ route('public.tours.index') }}" id="language-filter-form">
                        @foreach(request()->except(['language']) as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        
                        <div class="search-field mb-3">
                            <select name="language" class="filter-select" onchange="this.form.submit()">
                                <option value="">{{ __('Select Language') }}</option>
                                @foreach($languages as $language)
                                    <option value="{{ $language->id }}" {{ request('language') == $language->id ? 'selected' : '' }}>
                                        {{ $language->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="language-filter-chips">
                            <div class="language-chip {{ !request('language') ? 'active' : '' }}"
                                 onclick="document.querySelector('#language-filter-form select').value=''; document.querySelector('#language-filter-form').submit();">
                                <span>{{ __('All') }}</span>
                            </div>
                            @foreach($languages->take(6) as $language)
                                <div class="language-chip {{ request('language') == $language->id ? 'active' : '' }}"
                                     onclick="document.querySelector('#language-filter-form select').value='{{ $language->id }}'; document.querySelector('#language-filter-form').submit();">
                                    @if($language->flag)
                                        <img src="{{ RvMedia::getImageUrl($language->flag, 'thumb') }}" alt="{{ $language->name }}" class="language-chip-flag">
                                    @endif
                                    <span>{{ $language->name }}</span>
                                </div>
                            @endforeach
                            @if($languages->count() > 6)
                                <div class="language-chip more-languages">
                                    <span>+{{ $languages->count() - 6 }} {{ __('more') }}</span>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Price Filter -->
            <div class="tours-sidebar">
                <h5 class="sidebar-title">{{ __('Filter by Price') }}</h5>
                <div class="price-filter-container">
                    <form method="GET" action="{{ route('public.tours.index') }}" id="price-filter-form">
                        @foreach(request()->except(['min_price', 'max_price']) as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        
                        <div class="price-range-slider">
                            <div class="price-slider-labels">
                                <span>{{ __('Price Range') }}</span>
                                <span class="price-range-value">
                                    <span id="price-min">{{ request('min_price') ? format_tour_price(request('min_price')) : format_tour_price(0) }}</span> - 
                                    <span id="price-max">{{ request('max_price') ? format_tour_price(request('max_price')) : format_tour_price(5000) }}</span>
                                </span>
                            </div>
                            <div class="price-inputs">
                                <div class="price-input-group">
                                    <label>{{ __('Min') }}</label>
                                    <input type="number" name="min_price" id="min_price_input" placeholder="{{ __('Min') }}" value="{{ request('min_price') }}" min="0">
                                </div>
                                <div class="price-input-group">
                                    <label>{{ __('Max') }}</label>
                                    <input type="number" name="max_price" id="max_price_input" placeholder="{{ __('Max') }}" value="{{ request('max_price') }}" min="0">
                                </div>
                            </div>
                        </div>
                        
                        <div class="price-quick-filters">
                            <div class="price-quick-filter {{ (!request('min_price') && !request('max_price')) ? 'active' : '' }}" 
                                 data-min="0" data-max="0">{{ __('Any Price') }}</div>
                            <div class="price-quick-filter {{ (request('min_price') == '0' && request('max_price') == '100') ? 'active' : '' }}" 
                                 data-min="0" data-max="100">{{ __('Under') }} {{ format_tour_price(100) }}</div>
                            <div class="price-quick-filter {{ (request('min_price') == '100' && request('max_price') == '500') ? 'active' : '' }}" 
                                 data-min="100" data-max="500">{{ format_tour_price(100) }} - {{ format_tour_price(500) }}</div>
                            <div class="price-quick-filter {{ (request('min_price') == '500' && request('max_price') == '1000') ? 'active' : '' }}" 
                                 data-min="500" data-max="1000">{{ format_tour_price(500) }} - {{ format_tour_price(1000) }}</div>
                            <div class="price-quick-filter {{ (request('min_price') == '1000' && !request('max_price')) ? 'active' : '' }}" 
                                 data-min="1000" data-max="0">{{ __('Over') }} {{ format_tour_price(1000) }}</div>
                        </div>
                        
                        <button type="submit" class="filter-apply-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 5px;"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
                            {{ __('Apply Price Filter') }}
                        </button>
                    </form>
                </div>
            </div>
            
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Price quick filters
                    document.querySelectorAll('.price-quick-filter').forEach(function(filter) {
                        filter.addEventListener('click', function() {
                            const minPrice = this.getAttribute('data-min');
                            const maxPrice = this.getAttribute('data-max');
                            
                            document.getElementById('min_price_input').value = minPrice == '0' && maxPrice == '0' ? '' : minPrice;
                            document.getElementById('max_price_input').value = maxPrice == '0' ? '' : maxPrice;
                            
                            // Update display values
                            document.getElementById('price-min').textContent = minPrice == '0' && maxPrice == '0' ? 
                                '{{ format_tour_price(0) }}' : '{{ format_tour_price("") }}' + minPrice;
                            
                            document.getElementById('price-max').textContent = maxPrice == '0' ? 
                                '{{ format_tour_price(5000) }}' : '{{ format_tour_price("") }}' + maxPrice;
                            
                            // Remove active class from all filters
                            document.querySelectorAll('.price-quick-filter').forEach(function(f) {
                                f.classList.remove('active');
                            });
                            
                            // Add active class to clicked filter
                            this.classList.add('active');
                        });
                    });
                    
                    // Update price display when inputs change
                    document.getElementById('min_price_input').addEventListener('input', function() {
                        document.getElementById('price-min').textContent = this.value ? 
                            '{{ format_tour_price("") }}' + this.value : '{{ format_tour_price(0) }}';
                            
                        // Remove active class from all filters
                        document.querySelectorAll('.price-quick-filter').forEach(function(f) {
                            f.classList.remove('active');
                        });
                    });
                    
                    document.getElementById('max_price_input').addEventListener('input', function() {
                        document.getElementById('price-max').textContent = this.value ? 
                            '{{ format_tour_price("") }}' + this.value : '{{ format_tour_price(5000) }}';
                            
                        // Remove active class from all filters
                        document.querySelectorAll('.price-quick-filter').forEach(function(f) {
                            f.classList.remove('active');
                        });
                    });
                });
            </script>

            <!-- Featured Tours -->
            @if($featuredTours->count() > 0)
                <div class="tours-sidebar">
                    <h5 class="sidebar-title">{{ __('Featured Tours') }}</h5>
                    @foreach($featuredTours->take(3) as $featuredTour)
                        <div class="featured-tour-item">
                            <div class="featured-tour-image">
                                <img src="{{ RvMedia::getImageUrl($featuredTour->image, 'thumb') }}" alt="{{ $featuredTour->name }}" />
                            </div>
                            <div class="featured-tour-content">
                                <h6>
                                    @if(!empty($featuredTour->slug))
                                        <a href="{{ route('public.tours.detail', $featuredTour->slug) }}">{{ $featuredTour->name }}</a>
                                    @else
                                        <span>{{ $featuredTour->name }}</span>
                                    @endif
                                </h6>
                                <div class="featured-tour-price">{{ format_tour_price($featuredTour->current_price) }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Lightbox Overlay -->
<div class="lightbox-overlay" id="lightbox-overlay" aria-hidden="true">
    <button type="button" class="lightbox-close" id="lightbox-close" aria-label="Close">×</button>
    <button type="button" class="lightbox-prev" id="lightbox-prev" aria-label="Previous">‹</button>
    <img class="lightbox-image" id="lightbox-image" src="" alt="" />
    <button type="button" class="lightbox-next" id="lightbox-next" aria-label="Next">›</button>
    <div class="lightbox-counter" id="lightbox-counter">1 / 1</div>
    <input type="hidden" id="lightbox-index" value="0" />
    <input type="hidden" id="lightbox-total" value="0" />
    <input type="hidden" id="lightbox-list" value="" />
    <input type="hidden" id="lightbox-keyboard" value="1" />
    <input type="hidden" id="lightbox-touch" value="1" />
    <input type="hidden" id="lightbox-active" value="0" />
    <input type="hidden" id="lightbox-lastx" value="0" />
    <input type="hidden" id="lightbox-lasty" value="0" />
    <input type="hidden" id="lightbox-touching" value="0" />
    <input type="hidden" id="lightbox-threshold" value="40" />
    <input type="hidden" id="lightbox-delta" value="0" />
    <input type="hidden" id="lightbox-direction" value="" />
    <input type="hidden" id="lightbox-time" value="0" />
    <input type="hidden" id="lightbox-anim" value="0" />
    <input type="hidden" id="lightbox-lockscroll" value="1" />
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Smooth animations for tour cards
    document.querySelectorAll('.tour-card-modern').forEach(function(card, index) {
        card.style.animationDelay = (index * 0.1) + 's';
    });
    
    // Enhanced hover effects
    document.querySelectorAll('.tour-card-modern').forEach(function(card) {
        card.addEventListener('mouseenter', function() {
            const btn = this.querySelector('.tour-book-button');
            if (btn) btn.style.transform = 'scale(1.05)';
        });
        card.addEventListener('mouseleave', function() {
            const btn = this.querySelector('.tour-book-button');
            if (btn) btn.style.transform = 'scale(1)';
        });
    });
    
    // Search form enhancements
    document.querySelectorAll('.tours-search-form input, .tours-search-form select').forEach(function(input) {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });
    });
    
    // Category hover effects
    document.querySelectorAll('.category-list li').forEach(function(item) {
        item.addEventListener('mouseenter', function() {
            const count = this.querySelector('.category-count');
            if (count) count.style.transform = 'scale(1.1)';
        });
        item.addEventListener('mouseleave', function() {
            const count = this.querySelector('.category-count');
            if (count) count.style.transform = 'scale(1)';
        });
    });
    
    // Scroll to top button
    if (document.querySelectorAll('.tour-card-modern').length > 6) {
        const scrollBtn = document.createElement('button');
        scrollBtn.id = 'scroll-to-top';
        scrollBtn.style.cssText = 'position: fixed; bottom: 30px; right: 30px; background: var(--color-brand); color: white; border: none; border-radius: 50%; width: 50px; height: 50px; cursor: pointer; display: none; z-index: 1000;';
        scrollBtn.innerHTML = '<i class="fi-rs-angle-up"></i>';
        document.body.appendChild(scrollBtn);
        
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                scrollBtn.style.display = 'block';
            } else {
                scrollBtn.style.display = 'none';
            }
        });
        
        scrollBtn.addEventListener('click', function() {
            window.scrollTo({top: 0, behavior: 'smooth'});
        });
    }
    
    // Loading animation for book buttons (only for non-link elements)
    document.querySelectorAll('.tour-book-button:not(a)').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            const originalText = this.innerHTML;
            
            this.innerHTML = '<i class="fi-rs-loading"></i> ' + '{{ __("Loading...") }}';
            this.style.pointerEvents = 'none';
            
            setTimeout(() => {
                this.innerHTML = originalText;
                this.style.pointerEvents = 'auto';
            }, 2000);
        });
    });
    
    // For link buttons, just show loading state without preventing navigation
    document.querySelectorAll('a.tour-book-button').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            this.classList.add('loading');
            const icon = this.querySelector('i');
            if (icon) {
                icon.classList.remove('fi-rs-shopping-cart');
                icon.classList.add('fi-rs-loading');
            }
        });
    });

});
</script>