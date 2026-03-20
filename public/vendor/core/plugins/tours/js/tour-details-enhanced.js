/**
 * Enhanced Tour Details JavaScript
 * Adds animations, shimmer loading effect, and UI improvements
 */
(function (root) {
    'use strict';

    // Check if jQuery is available
    if (typeof jQuery === 'undefined') {
        console.error('jQuery is required for tour-details-enhanced.js');
        return;
    }

    const $ = jQuery;

    // DOM elements
    const $tourDetailContainer = $('.tour-detail-container');
    const $tourSections = $('.tour-detail-section');
    const $galleryImages = $('.tour-gallery-image');
    const $galleryDots = $('.gallery-dot');

    // Show shimmer loading effect
    function showShimmerLoading() {
        // Create shimmer loading template
        const shimmerTemplate = `
            <div class="shimmer-container">
                <div class="shimmer shimmer-image mb-4"></div>
                <div class="shimmer shimmer-text-lg"></div>
                <div class="shimmer shimmer-text-md"></div>
                <div class="row mt-4">
                    <div class="col-md-8">
                        <div class="shimmer shimmer-box"></div>
                        <div class="shimmer shimmer-line"></div>
                        <div class="shimmer shimmer-line"></div>
                        <div class="shimmer shimmer-line"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="shimmer shimmer-box"></div>
                    </div>
                </div>
            </div>
        `;

        // Insert shimmer before the tour detail container
        $tourDetailContainer.before(shimmerTemplate);

        // Hide the actual content during loading
        $tourDetailContainer.css('display', 'none');
    }

    // Hide shimmer and show content
    function hideShimmerAndShowContent() {
        setTimeout(() => {
            // Remove shimmer
            $('.shimmer-container').fadeOut(300, function () {
                $(this).remove();

                // Show the content with animation
                $tourDetailContainer.css('display', 'block');
                setTimeout(() => {
                    $tourDetailContainer.addClass('loaded');

                    // Animate sections sequentially
                    animateSectionsSequentially();
                }, 100);
            });
        }, 1500); // Simulate loading time
    }

    // Animate sections sequentially with a delay
    function animateSectionsSequentially() {
        $tourSections.each(function (index) {
            const $section = $(this);
            setTimeout(() => {
                $section.addClass('visible');
            }, 200 * index);
        });
    }

    // Gallery functionality
    function setupGallery() {
        // Show first image by default
        $galleryImages.first().addClass('active');
        $galleryDots.first().addClass('active');

        // Click event for gallery dots
        $galleryDots.on('click', function () {
            const index = $(this).data('index');

            // Update active classes
            $galleryDots.removeClass('active');
            $galleryImages.removeClass('active');
            $(this).addClass('active');
            $galleryImages.eq(index).addClass('active');
        });

        // Auto-rotate gallery images every 5 seconds
        let currentIndex = 0;
        const totalImages = $galleryImages.length;

        if (totalImages > 1) {
            setInterval(() => {
                currentIndex = (currentIndex + 1) % totalImages;
                $galleryDots.removeClass('active');
                $galleryImages.removeClass('active');
                $galleryDots.eq(currentIndex).addClass('active');
                $galleryImages.eq(currentIndex).addClass('active');
            }, 5000);
        }
    }

    // Scroll animations
    function setupScrollAnimations() {
        // Add animation classes to elements when they come into view
        const $animatedElements = $('.animate-on-scroll');

        function checkInView() {
            const windowHeight = $(window).height();
            const windowTopPosition = $(window).scrollTop();
            const windowBottomPosition = windowTopPosition + windowHeight;

            $animatedElements.each(function () {
                const $element = $(this);
                const elementHeight = $element.outerHeight();
                const elementTopPosition = $element.offset().top;
                const elementBottomPosition = elementTopPosition + elementHeight;

                // Check if element is in viewport
                if ((elementBottomPosition >= windowTopPosition) &&
                    (elementTopPosition <= windowBottomPosition)) {
                    $element.addClass($element.data('animation'));
                }
            });
        }

        // Initial check
        checkInView();

        // Check on scroll
        $(window).on('scroll', checkInView);
    }

    // Booking form interactions
    function setupBookingForm() {
        const $bookingForm = $('.tour-booking-form');
        const $dateInput = $('#tour_date');
        const $adultsInput = $('#adults');
        const $childrenInput = $('#children');
        const $infantsInput = $('#infants');
        const $totalPrice = $('.booking-total-price');

        // Update total price when inputs change
        function updateTotalPrice() {
            if (!$bookingForm.length) return;

            const adultPrice = parseFloat($bookingForm.data('adult-price') || 0);
            const childPrice = parseFloat($bookingForm.data('child-price') || 0);
            const infantPrice = parseFloat($bookingForm.data('infant-price') || 0);

            const adults = parseInt($adultsInput.val() || 0);
            const children = parseInt($childrenInput.val() || 0);
            const infants = parseInt($infantsInput.val() || 0);

            const total = (adults * adultPrice) + (children * childPrice) + (infants * infantPrice);

            // Format and display total
            $totalPrice.text(formatPrice(total));

            // Add animation to price change
            $totalPrice.addClass('bounce-in');
            setTimeout(() => {
                $totalPrice.removeClass('bounce-in');
            }, 600);
        }

        // Format price with currency
        function formatPrice(price) {
            const currency = $bookingForm.data('currency') || '$';
            return currency + price.toFixed(2);
        }

        // Attach event listeners
        $adultsInput.on('change', updateTotalPrice);
        $childrenInput.on('change', updateTotalPrice);
        $infantsInput.on('change', updateTotalPrice);

        // Initialize
        updateTotalPrice();
    }

    // Initialize everything
    function init() {
        // Show loading effect first
        showShimmerLoading();

        // When page is loaded
        $(window).on('load', function () {
            hideShimmerAndShowContent();
            setupGallery();
            setupScrollAnimations();
            setupBookingForm();
        });

        // If window.load already fired
        if (document.readyState === 'complete') {
            hideShimmerAndShowContent();
            setupGallery();
            setupScrollAnimations();
            setupBookingForm();
        }
    }

    // Run initialization
    init();

})(window);
