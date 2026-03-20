/**
 * Cities Slider JavaScript
 * Horizontal scrolling slider for tour cities
 */
(function (document) {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        // Make sure jQuery is available
        if (typeof jQuery === 'undefined') {
            console.error('jQuery is required for cities-slider.js');
            return;
        }

        const $ = jQuery;

        // DOM Elements
        const $slider = $('.cities-slider');
        const $prevBtn = $('.slider-arrow-left');
        const $nextBtn = $('.slider-arrow-right');
        const $cityCards = $('.city-card');

        // Variables
        let cardWidth = $cityCards.first().outerWidth(true);
        let visibleWidth = $slider.parent().width();
        let scrollPosition = 0;
        let maxScroll = $cityCards.length * cardWidth - visibleWidth;

        // Initial check if arrows should be visible
        checkArrows();

        // Event listeners
        $nextBtn.on('click', scrollRight);
        $prevBtn.on('click', scrollLeft);

        // Handle window resize
        $(window).on('resize', function () {
            // Recalculate dimensions
            cardWidth = $cityCards.first().outerWidth(true);
            visibleWidth = $slider.parent().width();
            maxScroll = $cityCards.length * cardWidth - visibleWidth;

            // Reset position if needed
            if (scrollPosition > maxScroll) {
                scrollPosition = maxScroll > 0 ? maxScroll : 0;
                updateSliderPosition();
            }

            checkArrows();
        });

        // Touch events for mobile swipe
        let touchStartX = 0;
        let touchEndX = 0;

        $slider.on('touchstart', function (e) {
            touchStartX = e.originalEvent.touches[0].clientX;
        });

        $slider.on('touchend', function (e) {
            touchEndX = e.originalEvent.changedTouches[0].clientX;
            handleSwipe();
        });

        function handleSwipe() {
            const swipeThreshold = 50; // Minimum distance to be considered a swipe
            const swipeDistance = touchStartX - touchEndX;

            if (Math.abs(swipeDistance) >= swipeThreshold) {
                if (swipeDistance > 0) {
                    // Swipe left, move right
                    scrollRight();
                } else {
                    // Swipe right, move left
                    scrollLeft();
                }
            }
        }

        // Functions
        function scrollRight() {
            // Calculate scroll amount (one card at a time)
            const scrollAmount = Math.min(cardWidth * 3, maxScroll - scrollPosition);

            if (scrollAmount > 0) {
                scrollPosition += scrollAmount;
                updateSliderPosition();
                checkArrows();
            }
        }

        function scrollLeft() {
            // Calculate scroll amount (one card at a time)
            const scrollAmount = Math.min(cardWidth * 3, scrollPosition);

            if (scrollAmount > 0) {
                scrollPosition -= scrollAmount;
                updateSliderPosition();
                checkArrows();
            }
        }

        function updateSliderPosition() {
            $slider.css('transform', `translateX(-${scrollPosition}px)`);
        }

        function checkArrows() {
            // Show/hide arrows based on scroll position
            if (scrollPosition <= 0) {
                $prevBtn.addClass('d-none');
            } else {
                $prevBtn.removeClass('d-none');
            }

            if (scrollPosition >= maxScroll || maxScroll <= 0) {
                $nextBtn.addClass('d-none');
            } else {
                $nextBtn.removeClass('d-none');
            }
        }
    });
})(document);
