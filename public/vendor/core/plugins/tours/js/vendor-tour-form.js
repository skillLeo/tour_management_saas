/**
 * Tour Vendor Form JavaScript - Complete Image Upload Handler
 */
class TourImageUploader {
    constructor() {
        this.maxFiles = 10;
        this.maxFileSize = 5 * 1024 * 1024; // 5MB
        this.allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        this.galleryFiles = []; // Store gallery files for form submission
        this.removedImages = []; // Track removed gallery images for edit page
        this.init();
    }

    init() {
        this.setupFeaturedImageUpload();
        this.setupGalleryUpload();
        this.setupCurrentGalleryManagement();
        this.createGalleryContainer();
        this.setupFormSubmission();
    }

    setupFeaturedImageUpload() {
        const input = document.getElementById('image');
        if (!input) return;

        input.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file && this.validateFile(file)) {
                this.previewFeaturedImage(file);
            } else {
                // Clear input if invalid
                e.target.value = '';
                this.removeFeaturedPreview();
            }
        });
    }

    setupGalleryUpload() {
        const input = document.getElementById('gallery');
        if (!input) return;

        this.createGalleryUploadArea(input);

        // Handle file input change
        input.addEventListener('change', (e) => {
            this.handleGalleryChange(e);
        });
    }

    setupCurrentGalleryManagement() {
        // Handle removing current gallery images in edit mode
        document.addEventListener('click', (e) => {
            if (e.target.closest('.remove-gallery-image')) {
                e.preventDefault();
                const button = e.target.closest('.remove-gallery-image');
                const galleryItem = button.closest('.gallery-item-current');
                const imagePath = galleryItem.getAttribute('data-image');

                // Add to removed images list
                this.removedImages.push(imagePath);
                this.updateRemovedImagesInput();

                // Remove from DOM with animation
                galleryItem.style.opacity = '0.5';
                galleryItem.style.transform = 'scale(0.8)';
                setTimeout(() => {
                    galleryItem.remove();
                }, 200);
            }
        });
    }

    updateRemovedImagesInput() {
        const input = document.getElementById('removed_gallery_images');
        if (input) {
            input.value = JSON.stringify(this.removedImages);
        }
    }

    createGalleryUploadArea(input) {
        const container = input.parentElement;

        // Create upload area
        const uploadArea = document.createElement('div');
        uploadArea.className = 'tour-image-upload';
        uploadArea.innerHTML = `
            <div>
                <i class="ti ti-cloud-upload" style="font-size: 32px; color: #6c757d;"></i>
                <p class="mb-2 mt-2">Click to upload or drag and drop</p>
                <small class="text-muted">PNG, JPG, GIF up to 5MB (Max ${this.maxFiles} images)</small>
            </div>
            <div class="upload-progress" style="display: none;">
                <div class="upload-progress-bar"></div>
            </div>
            <div class="upload-error" style="display: none;"></div>
        `;

        container.insertBefore(uploadArea, input);

        // Add click handler
        uploadArea.addEventListener('click', () => input.click());

        // Add drag and drop
        this.setupDragAndDrop(uploadArea, input);

        // Set upload URL
        uploadArea.dataset.uploadUrl = '/vendor/tours/upload';
    }

    setupDragAndDrop(uploadArea, input) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, this.preventDefaults, false);
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, () => {
                uploadArea.classList.add('dragover');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, () => {
                uploadArea.classList.remove('dragover');
            }, false);
        });

        uploadArea.addEventListener('drop', (e) => {
            const files = Array.from(e.dataTransfer.files);
            this.handleFiles(files);
        }, false);
    }

    preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    createGalleryContainer() {
        const input = document.getElementById('gallery');
        if (!input) return;

        const container = document.createElement('div');
        container.className = 'tour-gallery-container sortable';
        container.id = 'tour-gallery-container';
        input.parentElement.appendChild(container);
    }

    validateFile(file) {
        if (!this.allowedTypes.includes(file.type)) {
            this.showError('Please select a valid image file (PNG, JPG, GIF)');
            return false;
        }
        if (file.size > this.maxFileSize) {
            this.showError('File size must be less than 5MB');
            return false;
        }
        return true;
    }

    previewFeaturedImage(file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            const container = document.getElementById('image').parentElement;
            this.removeFeaturedPreview();

            const preview = document.createElement('div');
            preview.className = 'featured-image-preview';
            preview.innerHTML = `
                <img src="${e.target.result}" alt="Featured Image Preview">
                <button type="button" class="remove-featured" title="Remove Image">
                    <i class="ti ti-x"></i>
                </button>
            `;

            container.appendChild(preview);

            // Add remove handler
            preview.querySelector('.remove-featured').addEventListener('click', () => {
                this.removeFeaturedImage();
            });
        };
        reader.readAsDataURL(file);
    }

    removeFeaturedImage() {
        document.getElementById('image').value = '';
        this.removeFeaturedPreview();
    }

    removeFeaturedPreview() {
        const container = document.getElementById('image').parentElement;
        const existing = container.querySelector('.featured-image-preview');
        if (existing) existing.remove();
    }

    handleGalleryChange(e) {
        const files = Array.from(e.target.files);

        // Always add files to gallery array when selected via input
        console.log('Gallery input changed, files selected:', files.length);
        files.forEach(file => {
            if (this.validateFile(file) && this.galleryFiles.length < this.maxFiles) {
                console.log('Adding file to gallery:', file.name);
                this.addImageToGallery(file);
            }
        });

        // Don't clear input to preserve files for form submission
        // e.target.value = '';
    }

    handleFiles(files) {
        const validFiles = files.filter(file => this.validateFile(file));

        if (validFiles.length === 0) return;

        const remainingSlots = this.maxFiles - this.galleryFiles.length;

        if (validFiles.length > remainingSlots) {
            this.showError(`You can only upload ${remainingSlots} more images. Maximum ${this.maxFiles} images allowed.`);
            validFiles.splice(remainingSlots);
        }

        validFiles.forEach(file => {
            this.addImageToGallery(file);
        });
    }

    addImageToGallery(file) {
        const container = document.getElementById('tour-gallery-container');
        const reader = new FileReader();

        reader.onload = (e) => {
            const index = this.galleryFiles.length;
            this.galleryFiles.push(file);

            const item = document.createElement('div');
            item.className = 'tour-gallery-item';
            item.dataset.index = index;
            item.innerHTML = `
                <img src="${e.target.result}" alt="Gallery Image">
                <button type="button" class="remove-btn" title="Remove Image">
                    <i class="ti ti-x"></i>
                </button>
                <div class="loading-overlay" style="display: none;">
                    Processing...
                </div>
            `;

            container.appendChild(item);

            // Add remove handler
            item.querySelector('.remove-btn').addEventListener('click', () => {
                this.removeGalleryImage(item, index);
            });
        };

        reader.readAsDataURL(file);
    }

    removeGalleryImage(item, index) {
        // Remove from files array
        this.galleryFiles.splice(index, 1);

        // Remove DOM element
        item.remove();

        // Update indices for remaining items
        const items = document.querySelectorAll('#tour-gallery-container .tour-gallery-item');
        items.forEach((item, newIndex) => {
            item.dataset.index = newIndex;
        });
    }

    showError(message) {
        const errorDiv = document.querySelector('.upload-error');
        if (errorDiv) {
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
            setTimeout(() => {
                errorDiv.style.display = 'none';
            }, 5000);
        } else {
            alert(message);
        }
    }

    setupFormSubmission() {
        const form = document.querySelector('form[action*="tours.store"], form[action*="tours.update"]');
        if (!form) return;

        form.addEventListener('submit', (e) => {
            console.log('Form submission started');

            // Update gallery input with current files
            this.updateGalleryInput();

            // Debug places data
            const placesInputs = form.querySelectorAll('input[name*="places"]');
            console.log('Places inputs found:', placesInputs.length);
            placesInputs.forEach((input, index) => {
                console.log(`Places input ${index}:`, {
                    name: input.name,
                    value: input.value,
                    type: input.type,
                    files: input.files ? input.files.length : 'N/A'
                });
            });
        });
    }

    updateGalleryInput() {
        const galleryInput = document.getElementById('gallery');
        if (!galleryInput) return;

        console.log('UpdateGalleryInput called:');
        console.log('- galleryFiles length:', this.galleryFiles.length);
        console.log('- input files length:', galleryInput.files.length);

        // If we have files in our gallery array, use them
        if (this.galleryFiles.length > 0) {
            console.log('Using galleryFiles array');
            // Create new DataTransfer object to build FileList
            const dt = new DataTransfer();

            this.galleryFiles.forEach(file => {
                console.log('Adding file to DataTransfer:', file.name);
                dt.items.add(file);
            });

            // Update the input's files
            galleryInput.files = dt.files;
            console.log('Final input files length:', galleryInput.files.length);
        } else {
            console.log('No galleryFiles, keeping original input files');
        }
        // If no files in galleryFiles but input has files, keep the input files
        // This allows for simple file selection without drag-drop
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function () {
    // Initialize image uploader
    window.tourImageUploader = new TourImageUploader();

    // Make gallery sortable if jQuery UI is available
    if (typeof $ !== 'undefined' && $.ui && $.ui.sortable) {
        setTimeout(() => {
            $('#tour-gallery-container').sortable({
                tolerance: 'pointer',
                cursor: 'move',
                opacity: 0.8,
                placeholder: 'tour-gallery-placeholder',
                update: function (event, ui) {
                    // Update files array order when items are reordered
                    const newOrder = [];
                    $('#tour-gallery-container .tour-gallery-item').each(function () {
                        const index = parseInt($(this).data('index'));
                        if (!isNaN(index) && window.tourImageUploader.galleryFiles[index]) {
                            newOrder.push(window.tourImageUploader.galleryFiles[index]);
                        }
                    });
                    window.tourImageUploader.galleryFiles = newOrder;

                    // Update indices
                    $('#tour-gallery-container .tour-gallery-item').each(function (newIndex) {
                        $(this).data('index', newIndex);
                    });
                }
            });
        }, 100);
    }

    // Initialize tour sections manager
    window.tourSectionsManager = new TourSectionsManager();

    // Setup image preview for existing places
    document.querySelectorAll('input.place-image-file').forEach(input => {
        input.addEventListener('change', (e) => {
            console.log('Existing place image input changed');
            const file = e.target.files[0];
            console.log('Selected file for existing place:', file);
            if (file && window.tourSectionsManager && window.tourSectionsManager.validatePlaceImage(file)) {
                console.log('File is valid for existing place, creating preview');
                const placeItem = e.target.closest('.place-item');
                window.tourSectionsManager.previewPlaceImage(file, placeItem);
            } else {
                console.log('File is invalid for existing place or validation failed');
            }
        });
    });

    // Initialize CKEditor for description and content
    window.tourSectionsManager.initCkEditor();
});

/**
 * Tour Advanced Sections Manager
 */
class TourSectionsManager {
    constructor() {
        this.faqIndex = 0;
        this.placeIndex = 0;
        this.scheduleIndex = 0;
        this.timeSlotIndex = 0;
        this.init();
    }

    init() {
        this.setupFAQs();
        this.setupPlaces();
        this.setupSchedules();
        this.setupTimeSlots();
        this.initializeIndexes();
    }

    initializeIndexes() {
        // Set initial indexes based on existing items
        this.faqIndex = document.querySelectorAll('.faq-item').length;
        this.placeIndex = document.querySelectorAll('.place-item').length;
        this.scheduleIndex = document.querySelectorAll('.schedule-item').length;
        this.timeSlotIndex = document.querySelectorAll('.time-slot-item').length;
    }

    setupFAQs() {
        const addBtn = document.getElementById('add-faq');
        if (!addBtn) {
            // No add button means this is create page, skip setup
            return;
        }

        addBtn.addEventListener('click', () => {
            this.addFAQ();
        });

        // Handle remove buttons
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('remove-faq') || e.target.closest('.remove-faq')) {
                e.preventDefault();
                const faqItem = e.target.closest('.faq-item');
                if (faqItem) {
                    faqItem.remove();
                    // Check if container is now empty
                    setTimeout(() => this.checkEmptyContainer('tour-faqs-container', '.faq-item'), 100);
                }
            }
        });
    }

    addFAQ() {
        const container = document.getElementById('tour-faqs-container');
        if (!container) return;

        // Hide "No items" message if this is the first item
        const noItemsMsg = container.querySelector('.text-muted.text-center');
        if (noItemsMsg && this.faqIndex === 0) {
            noItemsMsg.style.display = 'none';
        }

        const faqHtml = `
            <div class="faq-item border rounded p-3 mb-3" data-index="${this.faqIndex}" style="background: #f8f9fa;">
                <div class="mb-3">
                    <label class="form-label fw-bold">Question</label>
                    <textarea name="faqs[${this.faqIndex}][question]" class="form-control" rows="2" required placeholder="Enter question here..."></textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Answer</label>
                    <textarea name="faqs[${this.faqIndex}][answer]" class="form-control" rows="4" required placeholder="Enter answer here..."></textarea>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Order</label>
                        <input type="number" name="faqs[${this.faqIndex}][order]" class="form-control" value="${this.faqIndex}" min="0" placeholder="Order">
                    </div>
                    <div class="col-md-6 mb-3 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm remove-faq w-100">
                            <i class="ti ti-trash"></i> Remove
                        </button>
                    </div>
                </div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', faqHtml);
        this.faqIndex++;
    }

    setupPlaces() {
        const addBtn = document.getElementById('add-place');
        if (!addBtn) {
            // No add button means this is create page, skip setup
            return;
        }

        addBtn.addEventListener('click', () => {
            this.addPlace();
        });

        // Handle remove buttons
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('remove-place') || e.target.closest('.remove-place')) {
                e.preventDefault();
                const placeItem = e.target.closest('.place-item');
                if (placeItem) {
                    placeItem.remove();
                    // Check if container is now empty
                    setTimeout(() => this.checkEmptyContainer('tour-places-container', '.place-item'), 100);
                }
            }
        });
    }

    addPlace() {
        const container = document.getElementById('tour-places-container');
        if (!container) return;

        // Hide "No items" message if this is the first item
        const noItemsMsg = container.querySelector('.text-muted.text-center');
        if (noItemsMsg && this.placeIndex === 0) {
            noItemsMsg.style.display = 'none';
        }

        const placeHtml = `
            <div class="place-item border rounded p-3 mb-3" data-index="${this.placeIndex}" style="background: #f0f8ff;">
                <div class="mb-3">
                    <label class="form-label fw-bold">Place Name</label>
                    <input type="text" name="places[${this.placeIndex}][name]" class="form-control" required placeholder="Enter place name...">
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Place Image</label>
                    <div class="form-group">
                        <label for="place_image_${this.placeIndex}" class="form-label">Upload Place Image</label>
                        <input type="file" name="places[${this.placeIndex}][image_file]" id="place_image_${this.placeIndex}" class="form-control place-image-file" accept="image/*">
                        <input type="hidden" name="places[${this.placeIndex}][image]" value="">
                        <small class="form-text text-muted">Recommended size: 800x600px, Max 5MB</small>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Order</label>
                        <input type="number" name="places[${this.placeIndex}][order]" class="form-control" value="${this.placeIndex}" min="0" placeholder="Order">
                    </div>
                    <div class="col-md-6 mb-3 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm remove-place w-100">
                            <i class="ti ti-trash"></i> Remove
                        </button>
                    </div>
                </div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', placeHtml);

        // Setup image preview for the new place
        const newPlaceItem = container.lastElementChild;
        const imageInput = newPlaceItem.querySelector('input.place-image-file');
        if (imageInput) {
            imageInput.addEventListener('change', (e) => {
                console.log('Place image input changed');
                const file = e.target.files[0];
                console.log('Selected file:', file);
                if (file && this.validatePlaceImage(file)) {
                    console.log('File is valid, creating preview');
                    this.previewPlaceImage(file, newPlaceItem);
                } else {
                    console.log('File is invalid or validation failed');
                }
            });
        }

        this.placeIndex++;
    }

    setupSchedules() {
        const addBtn = document.getElementById('add-schedule');
        if (!addBtn) {
            // No add button means this is create page, skip setup
            return;
        }

        addBtn.addEventListener('click', () => {
            this.addSchedule();
        });

        // Handle remove buttons
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('remove-schedule') || e.target.closest('.remove-schedule')) {
                e.preventDefault();
                const scheduleItem = e.target.closest('.schedule-item');
                if (scheduleItem) {
                    scheduleItem.remove();
                    // Check if container is now empty
                    setTimeout(() => this.checkEmptyContainer('tour-schedules-container', '.schedule-item'), 100);
                }
            }
        });
    }

    addSchedule() {
        const container = document.getElementById('tour-schedules-container');
        if (!container) return;

        // Hide "No items" message if this is the first item
        const noItemsMsg = container.querySelector('.text-muted.text-center');
        if (noItemsMsg && this.scheduleIndex === 0) {
            noItemsMsg.style.display = 'none';
        }

        const scheduleHtml = `
            <div class="schedule-item border rounded p-3 mb-3" data-index="${this.scheduleIndex}" style="background: #f0fff0;">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Day</label>
                        <input type="number" name="schedules[${this.scheduleIndex}][day]" class="form-control" required min="1" placeholder="Day number">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Time</label>
                        <input type="text" name="schedules[${this.scheduleIndex}][time]" class="form-control" placeholder="e.g. 09:00 AM">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Title</label>
                    <input type="text" name="schedules[${this.scheduleIndex}][title]" class="form-control" required placeholder="Enter schedule title...">
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Description</label>
                    <textarea name="schedules[${this.scheduleIndex}][description]" class="form-control" rows="3" placeholder="Enter schedule description..."></textarea>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Order</label>
                        <input type="number" name="schedules[${this.scheduleIndex}][order]" class="form-control" value="${this.scheduleIndex}" min="0" placeholder="Order">
                    </div>
                    <div class="col-md-6 mb-3 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm remove-schedule w-100">
                            <i class="ti ti-trash"></i> Remove
                        </button>
                    </div>
                </div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', scheduleHtml);
        this.scheduleIndex++;
    }

    setupTimeSlots() {
        // Time slots are now handled by inline script in edit.blade.php
        // This is kept for backward compatibility but does nothing
        return;
    }

    addTimeSlot() {
        const container = document.getElementById('tour-time-slots-container');
        if (!container) return;

        // Hide "No items" message if this is the first item
        const noItemsMsg = container.querySelector('.text-muted.text-center');
        if (noItemsMsg && this.timeSlotIndex === 0) {
            noItemsMsg.style.display = 'none';
        }

        const days = {
            'sunday': 'Sunday',
            'monday': 'Monday',
            'tuesday': 'Tuesday',
            'wednesday': 'Wednesday',
            'thursday': 'Thursday',
            'friday': 'Friday',
            'saturday': 'Saturday'
        };

        let daysCheckboxes = '';
        for (const [dayValue, dayLabel] of Object.entries(days)) {
            daysCheckboxes += `
                <div class="form-check form-check-inline mr-3">
                    <input class="form-check-input" 
                           type="checkbox" 
                           name="time_slots[${this.timeSlotIndex}][restricted_days][]"
                           id="day-${dayValue}-${this.timeSlotIndex}" 
                           value="${dayValue}">
                    <label class="form-check-label" for="day-${dayValue}-${this.timeSlotIndex}">
                        ${dayLabel}
                    </label>
                </div>
            `;
        }

        const timeSlotHtml = `
            <div class="time-slot-item border rounded p-3 mb-3" data-index="${this.timeSlotIndex}" style="background: #f0f8ff;">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Start Time</label>
                        <input type="time" name="time_slots[${this.timeSlotIndex}][start_time]" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Order</label>
                        <input type="number" name="time_slots[${this.timeSlotIndex}][order]" class="form-control" value="${this.timeSlotIndex}" min="0" placeholder="Order">
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-12">
                        <label class="form-label fw-bold">Unavailable Days</label>
                        <div class="d-flex flex-wrap">
                            ${daysCheckboxes}
                        </div>
                        <small class="form-text text-muted">
                            Available days: sunday, monday, tuesday, wednesday, thursday, friday, saturday
                        </small>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12 d-flex justify-content-end">
                        <button type="button" class="btn btn-danger btn-sm remove-time-slot">
                            <i class="ti ti-trash"></i> Remove
                        </button>
                    </div>
                </div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', timeSlotHtml);
        this.timeSlotIndex++;
    }

    // Collect time slot data including restricted days
    collectTimeSlotData() {
        const timeSlots = [];

        document.querySelectorAll('.time-slot-item').forEach((item) => {
            const startTime = item.querySelector('input[name*="[start_time]"]')?.value;
            const order = item.querySelector('input[name*="[order]"]')?.value;

            // Collect restricted days
            const restrictedDays = [];
            item.querySelectorAll('input[type="checkbox"]:checked').forEach((checkbox) => {
                restrictedDays.push(checkbox.value);
            });

            if (startTime) {
                timeSlots.push({
                    start_time: startTime,
                    order: order || 0,
                    restricted_days: restrictedDays
                });
            }
        });

        return timeSlots;
    }

    // Helper function to show/hide "No items" messages
    checkEmptyContainer(containerId, itemSelector) {
        const container = document.getElementById(containerId);
        if (!container) return;

        const items = container.querySelectorAll(itemSelector);
        const noItemsMsg = container.querySelector('.text-muted.text-center');

        if (items.length === 0 && noItemsMsg) {
            noItemsMsg.style.display = 'block';
        } else if (noItemsMsg) {
            noItemsMsg.style.display = 'none';
        }
    }

    // Validate place image file
    validatePlaceImage(file) {
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        const maxSize = 5 * 1024 * 1024; // 5MB

        if (!allowedTypes.includes(file.type)) {
            alert('Please select a valid image file (PNG, JPG, GIF)');
            return false;
        }

        if (file.size > maxSize) {
            alert('File size must be less than 5MB');
            return false;
        }

        return true;
    }

    // Preview place image
    previewPlaceImage(file, placeItem) {
        const reader = new FileReader();
        reader.onload = (e) => {
            // Remove existing preview if any
            const existingPreview = placeItem.querySelector('.place-image-preview');
            if (existingPreview) {
                existingPreview.remove();
            }

            // Create preview container
            const preview = document.createElement('div');
            preview.className = 'place-image-preview mb-3';
            preview.innerHTML = `
                <img src="${e.target.result}" alt="Place Image Preview" class="img-thumbnail" style="max-width: 200px;">
                <button type="button" class="btn btn-sm btn-danger remove-place-image" style="margin-left: 10px;">
                    <i class="ti ti-x"></i> Remove
                </button>
            `;

            // Insert after the file input
            const fileInput = placeItem.querySelector('input[type="file"]');
            fileInput.parentElement.insertBefore(preview, fileInput.nextSibling);

            // Add remove handler
            preview.querySelector('.remove-place-image').addEventListener('click', () => {
                this.removePlaceImage(placeItem);
            });

            // Log for debugging
            console.log('Place image preview created for file:', file.name);

            // Update the hidden input with file data for form submission
            this.updatePlaceImageData(placeItem, file);
        };
        reader.readAsDataURL(file);
    }

    // Update place image data for form submission
    updatePlaceImageData(placeItem, file) {
        // Find the place index from the name attribute
        const nameInput = placeItem.querySelector('input[name*="[name]"]');
        if (!nameInput) return;

        const nameMatch = nameInput.name.match(/places\[(\d+)\]/);
        if (!nameMatch) return;

        const placeIndex = nameMatch[1];
        console.log('Updating place image data for index:', placeIndex);

        // Create a new file input with the correct name
        const existingFileInput = placeItem.querySelector('input[type="file"]');
        if (existingFileInput) {
            // Update the name to include the index
            existingFileInput.name = `places[${placeIndex}][image_file]`;
            console.log('Updated file input name to:', existingFileInput.name);
        }
    }

    // Remove place image
    removePlaceImage(placeItem) {
        const fileInput = placeItem.querySelector('input[type="file"]');
        const hiddenInput = placeItem.querySelector('input[type="hidden"]');
        const preview = placeItem.querySelector('.place-image-preview');

        // Clear inputs
        fileInput.value = '';
        if (hiddenInput) {
            hiddenInput.value = '';
        }

        // Remove preview
        if (preview) {
            preview.remove();
        }

        console.log('Place image removed');
    }

    // Initialize CKEditor for description and content fields
    initCkEditor() {
        if (typeof window.editorManagement !== 'undefined') {
            const editorConfig = {
                toolbar: {
                    items: [
                        'heading',
                        '|',
                        'fontColor',
                        'fontSize',
                        'fontBackgroundColor',
                        'fontFamily',
                        'bold',
                        'italic',
                        'underline',
                        'link',
                        'strikethrough',
                        'bulletedList',
                        'numberedList',
                        '|',
                        'alignment',
                        'direction',
                        'shortcode',
                        'outdent',
                        'indent',
                        '|',
                        'htmlEmbed',
                        'imageInsert',
                        'ckfinder',
                        'blockQuote',
                        'insertTable',
                        'mediaEmbed',
                        'undo',
                        'redo',
                        'findAndReplace',
                        'removeFormat',
                        'sourceEditing',
                        'codeBlock',
                        'fullScreen'
                    ],
                    shouldNotGroupWhenFull: true
                }
            };

            // Initialize description editor
            const descriptionEditor = document.getElementById('description');
            if (descriptionEditor) {
                window.editorManagement.initCkEditor('description', editorConfig);
            }

            // Initialize content editor
            const contentEditor = document.getElementById('content');
            if (contentEditor) {
                window.editorManagement.initCkEditor('content', editorConfig);
            }
        }
    }
}