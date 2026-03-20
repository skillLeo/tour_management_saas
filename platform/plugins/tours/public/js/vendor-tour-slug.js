/**
 * Tour Vendor Form Slug Handler - Vanilla JS version
 */
(function () {
    // Wait for DOM to be ready
    function initSlugHandler() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initSlugHandler);
            return;
        }

        console.log('Slug handler initialized');

        let autoSlugMode = true; // Auto-generate by default

        // Function to generate slug from text
        function generateSlug(text) {
            if (!text) return '';

            // For Latin text, generate slug client-side
            return text
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '') // Remove special characters
                .replace(/\s+/g, '-')         // Replace spaces with hyphens
                .replace(/-+/g, '-')          // Replace multiple hyphens with single
                .replace(/^-|-$/g, '');       // Remove leading/trailing hyphens
        }

        // Auto-generate slug from tour name when in auto mode
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');
        const autoBtn = document.getElementById('auto-slug-btn');

        if (!nameInput || !slugInput) {
            console.error('Name or slug input not found');
            return;
        }

        nameInput.addEventListener('input', function () {
            console.log('Name input changed:', this.value);
            if (autoSlugMode) {
                const slug = generateSlug(this.value);
                slugInput.value = slug;
                updatePreview(slug);
            }
        });

        // Auto-generate button click
        if (autoBtn) {
            autoBtn.addEventListener('click', function (e) {
                console.log('Auto slug button clicked');
                e.preventDefault();

                const nameValue = nameInput.value;
                if (nameValue) {
                    const slug = generateSlug(nameValue);
                    slugInput.value = slug;
                    updatePreview(slug);
                    autoSlugMode = true;
                    updateSlugState();
                }
            });
        }

        // When user types in slug field manually
        slugInput.addEventListener('input', function () {
            console.log('Slug input changed:', this.value);
            // Disable auto mode when user types manually
            autoSlugMode = false;
            updateSlugState();
            updatePreview(this.value);
        });

        // Validate and clean slug on blur
        slugInput.addEventListener('blur', function () {
            let slug = this.value;
            if (slug) {
                slug = slug
                    .toLowerCase()
                    .replace(/[^a-z0-9\s-]/g, '') // Remove special characters
                    .replace(/\s+/g, '-')         // Replace spaces with hyphens
                    .replace(/-+/g, '-')          // Replace multiple hyphens with single
                    .replace(/^-|-$/g, '');       // Remove leading/trailing hyphens

                this.value = slug;
                updatePreview(slug);
            }
        });

        // Update visual state of controls
        function updateSlugState() {
            if (autoSlugMode) {
                slugInput.classList.add('border-info');
                if (autoBtn) {
                    autoBtn.classList.remove('btn-outline-secondary');
                    autoBtn.classList.add('btn-outline-info');
                    autoBtn.setAttribute('title', 'Auto-generating from name');
                }
            } else {
                slugInput.classList.remove('border-info');
                if (autoBtn) {
                    autoBtn.classList.remove('btn-outline-info');
                    autoBtn.classList.add('btn-outline-secondary');
                    autoBtn.setAttribute('title', 'Click to auto-generate from name');
                }
            }
        }

        // Update the preview URL
        function updatePreview(slug) {
            console.log('Updating preview with slug:', slug);
            let preview = document.querySelector('.permalink-preview');
            
            if (!preview) {
                const previewHtml = `
                    <div class="permalink-preview">
                        <span class="text-muted">{{ __('Preview') }}: </span>
                        <a href="${window.location.origin}/tours/${slug || ''}" target="_blank" class="text-decoration-none">
                            ${window.location.origin}/tours/<span class="permalink-text">${slug || ''}</span>
                        </a>
                    </div>
                `;
                const formGroup = slugInput.closest('.permalink-field-wrapper') || slugInput.closest('.form-group') || slugInput.parentElement.parentElement.parentElement;
                if (formGroup) {
                    formGroup.insertAdjacentHTML('beforeend', previewHtml);
                }
            } else {
                const permalinkText = preview.querySelector('.permalink-text');
                const permalinkLink = preview.querySelector('a');
                if (permalinkText) {
                    permalinkText.textContent = slug || '';
                }
                if (permalinkLink) {
                    permalinkLink.href = `${window.location.origin}/tours/${slug || ''}`;
                }
            }
        }

        // Initialize state
        updateSlugState();

        // If slug is empty on page load, enable auto mode and generate from name
        if (!slugInput.value && nameInput.value) {
            autoSlugMode = true;
            const slug = generateSlug(nameInput.value);
            slugInput.value = slug;
            updatePreview(slug);
            updateSlugState();
        } else if (slugInput.value) {
            // If slug already has value, show preview
            updatePreview(slugInput.value);
        }
    }

    // Start the initialization process
    initSlugHandler();
})();