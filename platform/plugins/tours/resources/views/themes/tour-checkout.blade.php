@php
    Theme::set('pageTitle', __('Tour Checkout'));
    Theme::set('pageName', __('Tour Checkout'));
@endphp

<div class="page-header breadcrumb-wrap">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('public.index') }}" rel="nofollow"><i class="fi-rs-home mr-5"></i>{{ __('Home') }}</a>
            <span></span> <a href="{{ route('public.tours.index') }}">{{ __('Tours') }}</a>
            <span></span> {{ __('Checkout') }}
        </div>
    </div>
</div>

<div class="container mb-80 mt-50">
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h4 class="mb-0"><i class="fi-rs-clipboard mr-10"></i>{{ __('Booking Summary') }}</h4>
                </div>
                <div class="card-body">
                    <div class="product-detail bg-light rounded p-4 mb-4">
                        @if($tour->image)
                            <div class="product-image-container mb-3">
                                <img src="{{ RvMedia::getImageUrl($tour->image, 'medium') }}" alt="{{ $tour->name }}" class="img-fluid rounded" style="width: 100%; height: 200px; object-fit: cover;">
                            </div>
                        @endif
                        
                        <h3 class="product-title mb-3">{{ $tour->name }}</h3>
                        
                        <div class="product-meta mb-3">
                            <span class="badge bg-primary">
                                <i class="fi-rs-calendar mr-5"></i>{{ \Carbon\Carbon::parse($checkoutData['tour_date'])->format('M d, Y') }}
                            </span>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <td><i class="fi-rs-user mr-5"></i>{{ __('Adults') }} ({{ $checkoutData['adults'] }})</td>
                                        <td class="text-end">{{ format_price($checkoutData['adult_price']) }} × {{ $checkoutData['adults'] }} = {{ format_price($checkoutData['adult_price'] * $checkoutData['adults']) }}</td>
                                    </tr>
                                    @if($checkoutData['children'] > 0)
                                    <tr>
                                        <td><i class="fi-rs-user mr-5"></i>{{ __('Children') }} ({{ $checkoutData['children'] }})</td>
                                        <td class="text-end">{{ format_price($checkoutData['child_price']) }} × {{ $checkoutData['children'] }} = {{ format_price($checkoutData['child_price'] * $checkoutData['children']) }}</td>
                                    </tr>
                                    @endif
                                    @if($checkoutData['infants'] > 0)
                                    <tr>
                                        <td><i class="fi-rs-user mr-5"></i>{{ __('Infants') }} ({{ $checkoutData['infants'] }})</td>
                                        <td class="text-end">{{ format_price($checkoutData['infant_price']) }} × {{ $checkoutData['infants'] }} = {{ format_price($checkoutData['infant_price'] * $checkoutData['infants']) }}</td>
                                    </tr>
                                    @endif
                                    <tr class="border-top">
                                        <td><strong>{{ __('Total Amount') }}</strong></td>
                                        <td class="text-end"><strong class="text-brand">{{ format_price($checkoutData['total_amount']) }}</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="customer-info bg-light rounded p-4">
                        <h5 class="mb-3"><i class="fi-rs-user-circle mr-10"></i>{{ __('Customer Information') }}</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fi-rs-user text-muted mr-10"></i>
                                    <div>
                                        <small class="text-muted">{{ __('Name') }}</small>
                                        <div>{{ $checkoutData['customer_name'] }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fi-rs-envelope text-muted mr-10"></i>
                                    <div>
                                        <small class="text-muted">{{ __('Email') }}</small>
                                        <div>{{ $checkoutData['customer_email'] }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fi-rs-smartphone text-muted mr-10"></i>
                                    <div>
                                        <small class="text-muted">{{ __('Phone') }}</small>
                                        <div>{{ $checkoutData['customer_phone'] }}</div>
                                    </div>
                                </div>
                            </div>
                            @if(!empty($checkoutData['special_requirements']))
                            <div class="col-12">
                                <div class="d-flex align-items-start">
                                    <i class="fi-rs-edit text-muted mr-10 mt-1"></i>
                                    <div>
                                        <small class="text-muted">{{ __('Special Requirements') }}</small>
                                        <div>{{ $checkoutData['special_requirements'] }}</div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <form action="{{ route('public.tours.checkout.process') }}" method="POST" id="checkout-form">
                @csrf
                <input type="hidden" name="currency" value="{{ strtoupper($checkoutData['currency']) }}">
                
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h4 class="mb-0"><i class="fi-rs-credit-card mr-10"></i>{{ __('Payment Method') }}</h4>
                    </div>
                    <div class="card-body">
                        @if($paymentMethods)
                            {!! $paymentMethods !!}
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="fi-rs-exclamation display-4 mb-3"></i>
                                <p>{{ __('No payment methods available') }}</p>
                            </div>
                        @endif
                        
                        <button type="submit" class="btn btn-fill-out btn-block mt-3" id="checkout-btn">
                            <i class="fi-rs-lock mr-10"></i>
                            {{ __('Complete Payment') }} - {{ format_price($checkoutData['total_amount']) }}
                        </button>

                        <div class="text-center mt-3">
                            <small class="text-muted">
                                <i class="fi-rs-shield mr-5"></i>
                                {{ __('Secure 256-bit SSL encryption') }}
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Trust Indicators -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <h6 class="mb-3">{{ __('Why book with us?') }}</h6>
                        <div class="row">
                            <div class="col-4">
                                <i class="fi-rs-trophy text-warning display-6 mb-2"></i>
                                <small class="d-block text-muted">{{ __('Best Price Guarantee') }}</small>
                            </div>
                            <div class="col-4">
                                <i class="fi-rs-headphones text-info display-6 mb-2"></i>
                                <small class="d-block text-muted">{{ __('24/7 Support') }}</small>
                            </div>
                            <div class="col-4">
                                <i class="fi-rs-refresh text-success display-6 mb-2"></i>
                                <small class="d-block text-muted">{{ __('Free Cancellation') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style id="tour-checkout-styles">
/* 🎨 تصميم احترافي مبهر لطرق الدفع - أولوية قصوى */
body .list_payment_method,
.list_payment_method {
    list-style: none !important;
    padding: 0 !important;
    margin: 0 !important;
}

body .list_payment_method li,
.list_payment_method li {
    border: 3px solid #f1f3f4 !important;
    border-radius: 16px !important;
    padding: 20px !important;
    margin-bottom: 16px !important;
    cursor: pointer !important;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
    display: flex !important;
    align-items: center !important;
    gap: 16px !important;
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%) !important;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04) !important;
    position: relative !important;
    overflow: hidden !important;
}

.list_payment_method li::before {
    content: '' !important;
    position: absolute !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: 100% !important;
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.05) 0%, rgba(147, 51, 234, 0.05) 100%) !important;
    opacity: 0 !important;
    transition: opacity 0.3s ease !important;
    z-index: 0 !important;
}

.list_payment_method li:hover::before {
    opacity: 1 !important;
}

.list_payment_method li:hover {
    border-color: #3b82f6 !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.15) !important;
}

.list_payment_method li.selected {
    border-color: #10b981 !important;
    background: linear-gradient(135deg, #ecfdf5 0%, #f0fdf4 100%) !important;
    box-shadow: 0 8px 25px rgba(16, 185, 129, 0.2) !important;
    transform: translateY(-1px) !important;
}

.list_payment_method li.selected::before {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(34, 197, 94, 0.1) 100%) !important;
    opacity: 1 !important;
}

/* 🎯 تصميم الراديو بتن المخصص */
.list_payment_method input[type="radio"] {
    appearance: none !important;
    width: 24px !important;
    height: 24px !important;
    border: 3px solid #d1d5db !important;
    border-radius: 50% !important;
    margin: 0 !important;
    cursor: pointer !important;
    position: relative !important;
    transition: all 0.3s ease !important;
    flex-shrink: 0 !important;
    z-index: 1 !important;
}

.list_payment_method input[type="radio"]:checked {
    border-color: #10b981 !important;
    background: #10b981 !important;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2) !important;
}

.list_payment_method input[type="radio"]:checked::after {
    content: '✓' !important;
    position: absolute !important;
    top: 50% !important;
    left: 50% !important;
    transform: translate(-50%, -50%) !important;
    color: white !important;
    font-size: 12px !important;
    font-weight: bold !important;
}

/* 🖼️ تصميم أيقونات الدفع الاحترافي */
.payment-method-logo {
    width: 60px !important;
    height: 40px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%) !important;
    border: 2px solid #e5e7eb !important;
    border-radius: 12px !important;
    padding: 6px !important;
    flex-shrink: 0 !important;
    overflow: hidden !important;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important;
    transition: all 0.3s ease !important;
    z-index: 1 !important;
    position: relative !important;
}

.list_payment_method li:hover .payment-method-logo {
    transform: scale(1.05) !important;
    box-shadow: 0 4px 12px rgba(0,0,0,0.12) !important;
}

.list_payment_method li.selected .payment-method-logo {
    border-color: #10b981 !important;
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%) !important;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2) !important;
}

.payment-method-logo img {
    max-width: 48px !important;
    max-height: 32px !important;
    width: auto !important;
    height: auto !important;
    object-fit: contain !important;
    border-radius: 6px !important;
    filter: drop-shadow(0 1px 2px rgba(0,0,0,0.1)) !important;
}

/* 📝 تصميم معلومات طريقة الدفع */
.payment-method-info {
    flex: 1 !important;
    display: flex !important;
    flex-direction: column !important;
    z-index: 1 !important;
    position: relative !important;
}

.payment-method-name {
    font-weight: 700 !important;
    font-size: 16px !important;
    color: #1f2937 !important;
    margin-bottom: 4px !important;
    transition: color 0.3s ease !important;
}

.list_payment_method li.selected .payment-method-name {
    color: #059669 !important;
}

.payment-method-description {
    font-size: 13px !important;
    color: #6b7280 !important;
    margin: 0 !important;
    line-height: 1.4 !important;
    transition: color 0.3s ease !important;
}

.list_payment_method li.selected .payment-method-description {
    color: #047857 !important;
}

/* 💳 تصميم خاص لـ COD */
.list_payment_method li[data-bs-target="#collapse_cod"] .payment-method-logo,
.list_payment_method li:has(input[value="cod"]) .payment-method-logo {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%) !important;
    border-color: #f59e0b !important;
}

.list_payment_method li[data-bs-target="#collapse_cod"].selected .payment-method-logo,
.list_payment_method li:has(input[value="cod"]).selected .payment-method-logo {
    background: linear-gradient(135deg, #fef3c7 0%, #fed7aa 100%) !important;
    border-color: #f59e0b !important;
}

/* 🔘 زر الدفع المبهر */
.btn-fill-out {
    background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 50%, #06b6d4 100%) !important;
    border: none !important;
    border-radius: 16px !important;
    padding: 16px 24px !important;
    font-weight: 700 !important;
    font-size: 16px !important;
    color: white !important;
    position: relative !important;
    overflow: hidden !important;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3) !important;
}

.btn-fill-out::before {
    content: '' !important;
    position: absolute !important;
    top: 0 !important;
    left: -100% !important;
    width: 100% !important;
    height: 100% !important;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent) !important;
    transition: left 0.5s !important;
}

.btn-fill-out:hover::before {
    left: 100% !important;
}

.btn-fill-out:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 12px 35px rgba(59, 130, 246, 0.4) !important;
}

.btn-fill-out:active {
    transform: translateY(0) !important;
}

.btn-fill-out:disabled {
    opacity: 0.6 !important;
    cursor: not-allowed !important;
    transform: none !important;
}

/* ⚡ أنيميشن التحميل */
.loading-spinner {
    display: inline-block !important;
    width: 20px !important;
    height: 20px !important;
    border: 3px solid rgba(255, 255, 255, 0.3) !important;
    border-radius: 50% !important;
    border-top-color: white !important;
    animation: spin 1s linear infinite !important;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* 📱 تصميم متجاوب */
@media (max-width: 768px) {
    .list_payment_method li {
        padding: 16px !important;
        gap: 12px !important;
        border-radius: 12px !important;
    }
    
    .payment-method-logo {
        width: 50px !important;
        height: 35px !important;
        border-radius: 8px !important;
    }
    
    .payment-method-logo img {
        max-width: 40px !important;
        max-height: 28px !important;
    }
    
    .payment-method-name {
        font-size: 15px !important;
    }
    
    .payment-method-description {
        font-size: 12px !important;
    }
    
    .btn-fill-out {
        position: fixed !important;
        bottom: 20px !important;
        left: 20px !important;
        right: 20px !important;
        z-index: 999 !important;
        border-radius: 12px !important;
    }
    
    .card:last-child {
        margin-bottom: 100px !important;
    }
}

/* ✨ تأثيرات إضافية */
.list_payment_method li {
    animation: slideInUp 0.6s ease forwards !important;
}

.list_payment_method li:nth-child(1) { animation-delay: 0.1s !important; }
.list_payment_method li:nth-child(2) { animation-delay: 0.2s !important; }
.list_payment_method li:nth-child(3) { animation-delay: 0.3s !important; }
.list_payment_method li:nth-child(4) { animation-delay: 0.4s !important; }

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

@push('footer')
<script>
document.addEventListener("DOMContentLoaded", function() {
    // 🎨 تطبيق الـ styles بقوة
    const forceStyles = () => {
        document.querySelectorAll(".list_payment_method").forEach(function(list) {
            list.style.cssText += "list-style: none !important; padding: 0 !important; margin: 0 !important;";
        });
        
        document.querySelectorAll(".list_payment_method li").forEach(function(item) {
            item.style.cssText += `
                border: 3px solid #f1f3f4 !important;
                border-radius: 16px !important;
                padding: 20px !important;
                margin-bottom: 16px !important;
                cursor: pointer !important;
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
                display: flex !important;
                align-items: center !important;
                gap: 16px !important;
                background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%) !important;
                box-shadow: 0 2px 8px rgba(0,0,0,0.04) !important;
                position: relative !important;
                overflow: hidden !important;
            `;
        });
        
        document.querySelectorAll(".payment-method-logo").forEach(function(logo) {
            logo.style.cssText += `
                width: 60px !important;
                height: 40px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%) !important;
                border: 2px solid #e5e7eb !important;
                border-radius: 12px !important;
                padding: 6px !important;
                flex-shrink: 0 !important;
                overflow: hidden !important;
                box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important;
                transition: all 0.3s ease !important;
            `;
        });
        
        document.querySelectorAll(".payment-method-logo img").forEach(function(img) {
            img.style.cssText += `
                max-width: 48px !important;
                max-height: 32px !important;
                width: auto !important;
                height: auto !important;
                object-fit: contain !important;
                border-radius: 6px !important;
            `;
        });
    };
    
    // تطبيق الـ styles فوراً
    forceStyles();
    
    // تطبيق الـ styles مرة أخرى بعد 100ms للتأكد
    setTimeout(forceStyles, 100);

    // 🎯 معالج اختيار طرق الدفع
    document.querySelectorAll(".list_payment_method li").forEach(function(item, index) {
        // تأخير الأنيميشن
        item.style.animationDelay = (index * 0.1) + 's';
        
        item.addEventListener("click", function() {
            // إزالة التحديد من جميع العناصر
            document.querySelectorAll(".list_payment_method li").forEach(function(li) {
                li.classList.remove("selected");
            });
            
            // إضافة التحديد للعنصر المختار
            this.classList.add("selected");
            
            // تحديد الراديو بتن
            const radio = this.querySelector("input[type=radio]");
            if (radio) {
                radio.checked = true;
                // تأثير صوتي بصري للتأكيد
                radio.style.transform = "scale(1.2)";
                setTimeout(() => {
                    radio.style.transform = "scale(1)";
                }, 200);
            }
        });
    });

    // 🚀 معالج إرسال النموذج
    document.getElementById("checkout-form").addEventListener("submit", function(e) {
        e.preventDefault(); // منع السلوك الافتراضي للنموذج
        
        const selectedPayment = document.querySelector("input[name=payment_method]:checked");
        
        if (!selectedPayment) {
            // تنبيه مخصص
            const alertDiv = document.createElement('div');
            alertDiv.innerHTML = `
                <div style="position: fixed; top: 20px; right: 20px; background: linear-gradient(135deg, #ef4444, #dc2626); color: white; padding: 16px 24px; border-radius: 12px; box-shadow: 0 8px 25px rgba(239, 68, 68, 0.3); z-index: 10000; animation: slideInRight 0.3s ease;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span style="font-size: 20px;">⚠️</span>
                        <span style="font-weight: 600;">{{ __('Please select a payment method') }}</span>
                    </div>
                </div>
            `;
            document.body.appendChild(alertDiv);
            
            // إزالة التنبيه بعد 3 ثواني
            setTimeout(() => {
                alertDiv.remove();
            }, 3000);
            
            return;
        }
        
        // تحديث زر الدفع
        const btn = document.getElementById("checkout-btn");
        btn.disabled = true;
        btn.innerHTML = `
            <span class="loading-spinner"></span> 
            {{ __('Processing Payment...') }}
        `;
        
        // تأثير بصري إضافي
        btn.style.transform = "scale(0.98)";
        setTimeout(() => {
            btn.style.transform = "scale(1)";
        }, 150);
        
        // إرسال النموذج باستخدام AJAX
        const formData = new FormData(this);
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        
        // تأكد من إضافة طريقة الدفع إلى FormData
        if (selectedPayment && selectedPayment.value) {
            console.log('[Checkout] Selected payment method:', selectedPayment.value);
            // حذف أي قيمة سابقة لطريقة الدفع
            formData.delete('payment_method');
            // إضافة طريقة الدفع المحددة
            formData.append('payment_method', selectedPayment.value);
        } else {
            console.error('[Checkout] No payment method selected or value is empty');
        }
        
        // عرض جميع بيانات النموذج للتصحيح
        console.log('[Checkout] Form data entries:');
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }
        
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: csrfToken ? {
                'X-CSRF-TOKEN': csrfToken
            } : {},
            credentials: 'same-origin'
        })
        .then(function(response) {
            console.log('[Checkout] Response status:', response.status);
            
            // التحقق من نوع المحتوى
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json().then(data => {
                    console.log('[Checkout] JSON response:', data);
                    
                    if (data.error) {
                        alert(data.message || 'An error occurred during payment processing');
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fi-rs-lock mr-10"></i>{{ __('Complete Payment') }} - {{ format_price($checkoutData['total_amount']) }}';
                    } else if (data.data && data.data.redirect_url) {
                        console.log('[Checkout] Redirecting to:', data.data.redirect_url);
                        window.location.href = data.data.redirect_url;
                    } else {
                        window.location.reload();
                    }
                });
            } else {
                console.log('[Checkout] Non-JSON response, redirecting to response URL');
                window.location.href = response.url;
            }
        })
        .catch(function(error) {
            console.error('[Checkout] Error:', error);
            alert('An error occurred during payment processing. Please try again.');
            btn.disabled = false;
            btn.innerHTML = '<i class="fi-rs-lock mr-10"></i>{{ __('Complete Payment') }} - {{ format_price($checkoutData['total_amount']) }}';
        });
    });

    // 🎨 تحديد أول طريقة دفع تلقائياً
    const firstPaymentMethod = document.querySelector(".list_payment_method input[type=radio]");
    if (firstPaymentMethod) {
        firstPaymentMethod.checked = true;
        firstPaymentMethod.closest("li").classList.add("selected");
    }
    
    // ✨ تأثيرات إضافية للتفاعل
    document.querySelectorAll(".btn-fill-out").forEach(function(btn) {
        btn.addEventListener("mouseenter", function() {
            this.style.transform = "translateY(-2px) scale(1.02)";
        });
        
        btn.addEventListener("mouseleave", function() {
            if (!this.disabled) {
                this.style.transform = "translateY(0) scale(1)";
            }
        });
    });
});

// 🎭 تأثيرات CSS إضافية
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    .list_payment_method li {
        will-change: transform;
    }
    
    .payment-method-logo {
        will-change: transform;
    }
`;
document.head.appendChild(style);
</script>
@endpush
