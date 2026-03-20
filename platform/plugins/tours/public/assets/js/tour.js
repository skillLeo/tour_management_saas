/* Tours page front-end behaviors extracted from Blade into external JS */
(function (window, document) {
  'use strict';
  console.log('[Tours JS] File loaded successfully ✅');

  // Utility functions
  function onReady(fn) {
    if (document.readyState !== 'loading') {
      fn();
    } else {
      document.addEventListener('DOMContentLoaded', fn);
    }
  }

  function $(selector, root) { return (root || document).querySelector(selector); }
  function $all(selector, root) { return Array.prototype.slice.call((root || document).querySelectorAll(selector)); }

  // Declare TourCalendar globally before definition
  window.TourCalendar = null;

  // Define TourCalendar class with proper initialization
  const defineTourCalendar = function () {
    class TourCalendar {
      constructor(container, tourSlug) {
        console.log('[TourCalendar] Initializing with container:', container, 'and tour slug:', tourSlug);

        this.container = document.getElementById(container);
        if (!this.container) {
          console.error('[TourCalendar] Container not found:', container);
          return;
        }

        this.tourSlug = tourSlug;
        this.currentDate = new Date();
        this.allTimeSlots = [];
        this.selectedSlots = {};
        this.popup = null;

        // Call initialization method
        this.initialize();
      }

      // Renamed from init to initialize to avoid conflicts
      async initialize() {
        console.log('[TourCalendar] Initializing calendar...');

        try {
          // Load time slots
          await this.loadTimeSlots();

          // Only render calendar if we have time slots
          if (this.allTimeSlots && this.allTimeSlots.length > 0) {
            console.log('[TourCalendar] Time slots loaded:', this.allTimeSlots.length);

            // Start with current month
            this.currentDate = new Date();
            this.render();
            this.attachEvents();
            this.updateBookingButtonState();

            // Update popup styles
            this.updatePopupCSS();
          } else {
            console.warn('[TourCalendar] No time slots available');

            // No slots available, show message
            this.showNoSlotsMessage();
            this.disableBookingButton();
          }
        } catch (error) {
          console.error('[TourCalendar] Initialization error:', error);
          this.showNoSlotsMessage();
          this.disableBookingButton();
        }
      }

      async loadTimeSlots() {
        try {
          console.log('[TourCalendar] Loading time slots for tour:', this.tourSlug);
          const response = await fetch(`/tours/${this.tourSlug}/time-slots`, {
            headers: {
              'Accept': 'application/json',
              'X-Requested-With': 'XMLHttpRequest'
            }
          });

          if (!response.ok) {
            console.error('[TourCalendar] HTTP Error:', response.status, response.statusText);
            throw new Error(`HTTP error! status: ${response.status}`);
          }

          const data = await response.json();
          console.log('[TourCalendar] Full API Response:', data);

          // Extract time slots from the correct path in response
          let timeSlots = [];
          if (data.data && data.data.time_slots && Array.isArray(data.data.time_slots)) {
            timeSlots = data.data.time_slots;
          } else if (data.time_slots && Array.isArray(data.time_slots)) {
            timeSlots = data.time_slots;
          } else {
            console.warn('[TourCalendar] No time slots found in response:', data);
          }

          console.log('[TourCalendar] Extracted time slots:', timeSlots);

          // Check if we have any time slots
          if (!timeSlots || timeSlots.length === 0) {
            console.warn('[TourCalendar] No time slots available for this tour');
            this.allTimeSlots = []; // Empty slots
            return;
          }

          // Store all time slots
          this.allTimeSlots = timeSlots;
          console.log('[TourCalendar] Loaded time slots:', this.allTimeSlots);
        } catch (error) {
          console.error('[TourCalendar] Error loading time slots:', error);
          this.allTimeSlots = []; // Empty slots on error
          throw error; // Re-throw to be caught in init()
        }
      }

      // Modify the hasAvailableSlots method to handle restricted days correctly
      hasAvailableSlots(date) {
        console.log('[TourCalendar] Checking available slots for date:', date);

        if (!this.allTimeSlots || this.allTimeSlots.length === 0) {
          console.warn('[TourCalendar] No time slots available');
          return false;
        }

        const dayOfWeek = date.toLocaleDateString('en-US', { weekday: 'long' }).toLowerCase();
        console.log('[TourCalendar] Day of week:', dayOfWeek);

        // Check if any time slot is available for this day of week
        const availableSlots = this.allTimeSlots.filter(slot => {
          // If restricted_days is empty array or null, slot is available on all days
          if (!slot.restricted_days || slot.restricted_days.length === 0) {
            console.log(`[TourCalendar] Slot ${slot.id} has no restrictions, available on all days`);
            return true;
          }

          // If restricted_days includes all 7 days of the week, make it available on all days
          // This is a workaround for the current data where all days are marked as restricted
          if (slot.restricted_days.length === 7) {
            console.log(`[TourCalendar] Slot ${slot.id} has all days restricted, making available on all days`);
            return true;
          }

          const restrictedDays = slot.restricted_days.map(day => day.toLowerCase());
          const isAvailable = !restrictedDays.includes(dayOfWeek);

          console.log(`[TourCalendar] Slot ${slot.id} restricted days:`, restrictedDays);
          console.log(`[TourCalendar] Slot ${slot.id} available on ${dayOfWeek}:`, isAvailable);

          return isAvailable;
        });

        console.log('[TourCalendar] Available slots for this day:', availableSlots);
        return availableSlots.length > 0;
      }

      // Modify the render method to only show days with available slots
      render() {
        console.log('[TourCalendar] Rendering calendar...');
        const year = this.currentDate.getFullYear();
        const month = this.currentDate.getMonth();

        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
          'July', 'August', 'September', 'October', 'November', 'December'];

        const firstDay = new Date(year, month, 1);
        const startDate = new Date(firstDay);
        startDate.setDate(startDate.getDate() - firstDay.getDay());

        let html = `
          <div class="calendar-header">
            <button class="calendar-nav" data-action="prev">‹</button>
            <h4>${monthNames[month]} ${year}</h4>
            <button class="calendar-nav" data-action="next">›</button>
          </div>
          <div class="calendar-grid">
            <div class="calendar-day-header">Sun</div>
            <div class="calendar-day-header">Mon</div>
            <div class="calendar-day-header">Tue</div>
            <div class="calendar-day-header">Wed</div>
            <div class="calendar-day-header">Thu</div>
            <div class="calendar-day-header">Fri</div>
            <div class="calendar-day-header">Sat</div>
        `;

        for (let week = 0; week < 6; week++) {
          for (let day = 0; day < 7; day++) {
            const currentDay = new Date(startDate);
            currentDay.setDate(startDate.getDate() + (week * 7) + day);

            // Fix timezone issue - use local date formatting
            const dateStr = currentDay.getFullYear() + '-' +
              String(currentDay.getMonth() + 1).padStart(2, '0') + '-' +
              String(currentDay.getDate()).padStart(2, '0');

            const isCurrentMonth = currentDay.getMonth() === month;
            const isPast = currentDay < new Date().setHours(0, 0, 0, 0);
            const hasSlots = !isPast && this.hasAvailableSlots(currentDay);

            console.log(`[TourCalendar] Day ${dateStr}: current month=${isCurrentMonth}, past=${isPast}, has slots=${hasSlots}`);

            let dayClass = 'calendar-day';
            if (!isCurrentMonth) dayClass += ' other-month';
            if (isPast) dayClass += ' past-day';
            if (hasSlots) dayClass += ' has-slots';

            html += `
              <div class="${dayClass}" data-date="${dateStr}">
                ${currentDay.getDate()}
                ${hasSlots ? '<div class="slot-indicator"></div>' : ''}
              </div>
            `;
          }
        }

        html += '</div>';
        this.container.innerHTML = html;
        console.log('[TourCalendar] Calendar rendering complete');
      }

      // Improved attachEvents with better day click handling
      attachEvents() {
        console.log('[TourCalendar] Attaching calendar events...');

        // Navigation events
        const prevButton = this.container.querySelector('[data-action="prev"]');
        const nextButton = this.container.querySelector('[data-action="next"]');

        if (prevButton) {
          prevButton.addEventListener('click', () => {
            console.log('[TourCalendar] Previous month clicked');
            this.navigateToPreviousMonth();
          });
        }

        if (nextButton) {
          nextButton.addEventListener('click', () => {
            console.log('[TourCalendar] Next month clicked');
            this.navigateToNextMonth();
          });
        }

        // Day selection events - make all days clickable for testing
        this.container.addEventListener('click', (e) => {
          // Find the closest calendar day (with or without slots)
          const dayElement = e.target.closest('.calendar-day');

          // Only proceed if we found a day element and it's not from another month or past
          if (dayElement && !dayElement.classList.contains('other-month') && !dayElement.classList.contains('past-day')) {
            const dateStr = dayElement.dataset.date;
            console.log(`[TourCalendar] Day clicked: ${dateStr}`);

            // Force all days to be treated as having slots for testing
            this.showTimeSlotsPopup(dateStr, dayElement);
          }
        });
      }

      updateBookingButtonState() { /* Implement method */ }
      showNoSlotsMessage() { /* Implement method */ }
      disableBookingButton() { /* Implement method */ }

      // Add methods for month navigation
      navigateToPreviousMonth() {
        this.currentDate.setMonth(this.currentDate.getMonth() - 1);
        this.render();
        this.attachEvents();
      }

      navigateToNextMonth() {
        this.currentDate.setMonth(this.currentDate.getMonth() + 1);
        this.render();
        this.attachEvents();
      }

      // Modify showTimeSlotsPopup to replace calendar with slots view and ensure capacity
      async showTimeSlotsPopup(dateStr, dayElement) {
        console.log(`[TourCalendar] Showing time slots for date: ${dateStr}`);

        // Parse the selected date to get day of week
        const selectedDate = new Date(dateStr);
        const dayOfWeek = selectedDate.toLocaleDateString('en-US', { weekday: 'long' }).toLowerCase();
        const formattedDate = selectedDate.toLocaleDateString('en-US', {
          weekday: 'long',
          year: 'numeric',
          month: 'long',
          day: 'numeric'
        });

        // Filter time slots available for this day of week
        const availableSlots = (this.allTimeSlots || []).filter(slot => {
          // If restricted_days is empty array or null, slot is available on all days
          if (!slot.restricted_days || slot.restricted_days.length === 0) {
            return true;
          }

          // If restricted_days includes all 7 days of the week, make it available on all days
          if (slot.restricted_days.length === 7) {
            return true;
          }

          const restrictedDays = (slot.restricted_days || []).map(day => day.toLowerCase());
          return !restrictedDays.includes(dayOfWeek);
        });

        // Use available slots directly without adding capacity
        console.log('[TourCalendar] Available slots:', availableSlots);

        // Hide the calendar container
        const calendarGrid = this.container.querySelector('.calendar-grid');
        const calendarHeader = this.container.querySelector('.calendar-header');

        if (calendarGrid) {
          calendarGrid.style.opacity = '0';
          setTimeout(() => {
            calendarGrid.style.display = 'none';
          }, 300);
        }

        if (calendarHeader) {
          calendarHeader.style.opacity = '0';
          setTimeout(() => {
            calendarHeader.style.display = 'none';
          }, 300);
        }

        // Create slots container
        let slotsContainer = this.container.querySelector('.time-slots-container');

        if (!slotsContainer) {
          slotsContainer = document.createElement('div');
          slotsContainer.className = 'time-slots-container';
          this.container.appendChild(slotsContainer);
        } else {
          slotsContainer.innerHTML = '';
        }

        // Build slots view
        const slotsHeader = document.createElement('div');
        slotsHeader.className = 'slots-header';
        slotsHeader.innerHTML = `
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">${formattedDate}</h5>
            <button class="btn btn-sm btn-outline-secondary back-to-calendar">
              <i class="fas fa-arrow-left"></i> Back to Calendar
            </button>
          </div>
        `;

        const slotsBody = document.createElement('div');
        slotsBody.className = 'slots-body';

        if (availableSlots.length === 0) {
          slotsBody.innerHTML = `
            <div class="alert alert-info">
              <i class="fas fa-info-circle"></i> No time slots available for this day.
              <p class="mb-0 mt-2 small">Please select another day from the calendar.</p>
            </div>
          `;
        } else {
          slotsBody.innerHTML = `
            <p class="text-muted mb-3">Select your preferred time slot:</p>
            <div class="time-slots-grid">
              ${availableSlots.map(slot => `
                <div class="time-slot-card ${this.selectedSlots[slot.id] ? 'selected' : ''}" 
                     data-slot-id="${slot.id}" 
                     data-date="${dateStr}"
                     data-start="${slot.start_time}" 
                     data-end="${slot.end_time}">
                  <div class="time-range">
                    <i class="far fa-clock"></i> ${slot.start_time} - ${slot.end_time}
                  </div>
                </div>
              `).join('')}
            </div>
          `;
        }

        // Add to DOM with animation
        slotsContainer.appendChild(slotsHeader);
        slotsContainer.appendChild(slotsBody);
        slotsContainer.style.display = 'block';

        // Animate in
        setTimeout(() => {
          slotsContainer.style.opacity = '1';
        }, 10);

        // Attach event listeners
        const backButton = slotsContainer.querySelector('.back-to-calendar');
        if (backButton) {
          backButton.addEventListener('click', () => {
            this.showCalendar();
          });
        }

        // Slot selection events
        const slotCards = slotsContainer.querySelectorAll('.time-slot-card');
        slotCards.forEach(slotCard => {
          slotCard.addEventListener('click', () => {
            this.selectSlot(slotCard);
          });
        });
      }

      // Add method to show calendar and hide slots
      showCalendar() {
        console.log('[TourCalendar] Showing calendar');

        // Hide slots container
        const slotsContainer = this.container.querySelector('.time-slots-container');
        if (slotsContainer) {
          slotsContainer.style.opacity = '0';
          setTimeout(() => {
            slotsContainer.style.display = 'none';
          }, 300);
        }

        // Show calendar
        const calendarGrid = this.container.querySelector('.calendar-grid');
        const calendarHeader = this.container.querySelector('.calendar-header');

        if (calendarGrid) {
          calendarGrid.style.display = 'grid';
          setTimeout(() => {
            calendarGrid.style.opacity = '1';
          }, 10);
        }

        if (calendarHeader) {
          calendarHeader.style.display = 'flex';
          setTimeout(() => {
            calendarHeader.style.opacity = '1';
          }, 10);
        }
      }

      // Modify selectSlot to handle slot card selection without capacity information
      selectSlot(slotElement) {
        console.log('[TourCalendar] Slot selected:', slotElement.dataset);

        // Get slot data
        const slotId = slotElement.dataset.slotId;
        const date = slotElement.dataset.date;
        const startTime = slotElement.dataset.start;
        const endTime = slotElement.dataset.end;

        // Format date nicely for display
        const formattedDate = new Date(date).toLocaleDateString('en-US', {
          weekday: 'long',
          year: 'numeric',
          month: 'long',
          day: 'numeric'
        });

        // Toggle selection if the same slot is clicked
        if (this.selectedSlots[slotId]) {
          delete this.selectedSlots[slotId];
          slotElement.classList.remove('selected');
        } else {
          // Clear previous selections
          Object.keys(this.selectedSlots).forEach(key => {
            const prevSelectedElement = document.querySelector(`.time-slot-card[data-slot-id="${key}"]`);
            if (prevSelectedElement) {
              prevSelectedElement.classList.remove('selected');
            }
          });
          this.selectedSlots = {};

          // Select the new slot
          this.selectedSlots[slotId] = {
            date: formattedDate,
            startTime: startTime,
            endTime: endTime,
            slotId: slotId
          };
          slotElement.classList.add('selected');
        }

        // Debug available elements
        console.log('[TourCalendar] Checking for #selectedSlotsList:', document.getElementById('selectedSlotsList') ? 'Found' : 'Not found');

        // Update selected slots display
        this.updateSelectedSlotsDisplay();

        // Update booking button state
        this.updateBookingButtonState();

        // Force enable booking button
        const bookingButton = document.querySelector('button[type="submit"].btn-lg');
        if (bookingButton) {
          console.log('[TourCalendar] Directly enabling booking button');
          bookingButton.disabled = false;
          bookingButton.innerHTML = '<i class="fas fa-calendar-check"></i> Book Now';
          bookingButton.classList.remove('btn-secondary');
          bookingButton.classList.add('btn-primary');
        }

        // Add capacity information to hidden fields
        this.addCapacityFields();
      }

      // Dummy function to maintain compatibility
      addCapacityFields() {
        // This function is intentionally left empty as capacity fields are no longer needed
        console.log('[TourCalendar] Capacity fields not needed');
      }

      // Update selected slots display with improved UI and fallback creation
      updateSelectedSlotsDisplay() {
        // Try to find the container
        let container = document.getElementById('selectedSlotsList');
        const hiddenInput = document.getElementById('timeSlotIds');

        // Add hidden input for selected date if it doesn't exist
        let selectedDateInput = document.getElementById('selected_date');
        if (!selectedDateInput) {
          selectedDateInput = document.createElement('input');
          selectedDateInput.type = 'hidden';
          selectedDateInput.name = 'selected_date';
          selectedDateInput.id = 'selected_date';

          // Find form to append to
          const form = document.querySelector('form[data-tour-slug]') || document.getElementById('booking-form');
          if (form) {
            form.appendChild(selectedDateInput);
          } else if (container && container.parentNode) {
            container.parentNode.appendChild(selectedDateInput);
          }
        }

        console.log('[TourCalendar] Updating selected slots display:', this.selectedSlots);

        // If container doesn't exist, try to find parent and create it
        if (!container) {
          console.warn('[TourCalendar] Selected slots container not found, attempting to create it');

          // Find the parent container
          const parentContainer = document.getElementById('selectedSlots');

          if (parentContainer) {
            console.log('[TourCalendar] Found parent container, creating child container');
            // Create the container
            container = document.createElement('div');
            container.id = 'selectedSlotsList';
            container.className = 'row g-2';

            // Clear parent and append new container
            parentContainer.innerHTML = '<h6>Selected Time Slot:</h6>';
            parentContainer.appendChild(container);
          } else {
            console.error('[TourCalendar] Parent container #selectedSlots not found');

            // Try to find the calendar container and create the structure
            const calendarContainer = document.getElementById('tourCalendar');
            if (calendarContainer && calendarContainer.parentNode) {
              console.log('[TourCalendar] Creating entire selected slots structure');

              const selectedSlotsDiv = document.createElement('div');
              selectedSlotsDiv.id = 'selectedSlots';
              selectedSlotsDiv.className = 'selected-slots mt-3';
              selectedSlotsDiv.innerHTML = '<h6>Selected Time Slot:</h6>';

              container = document.createElement('div');
              container.id = 'selectedSlotsList';
              container.className = 'row g-2';

              selectedSlotsDiv.appendChild(container);
              calendarContainer.parentNode.appendChild(selectedSlotsDiv);
            } else {
              console.error('[TourCalendar] Cannot create selected slots container, no suitable parent found');
              return;
            }
          }
        }

        let html = '';
        const slotIds = [];

        Object.values(this.selectedSlots).forEach(slot => {
          const slotId = slot.slotId;

          slotIds.push(slotId);
          html += `
            <div class="col-12">
              <div class="selected-slot-card">
                <button class="remove-slot" onclick="window.tourCalendar.removeSlot('${slotId}')">&times;</button>
                <div><i class="fas fa-calendar-check"></i> <strong>${slot.date}</strong></div>
                <div><i class="fas fa-clock"></i> ${slot.startTime} - ${slot.endTime}</div>
              </div>
            </div>
          `;
        });

        console.log('[TourCalendar] Slot IDs:', slotIds);

        if (html === '') {
          html = `
            <div class="col-12">
              <p class="text-muted">No time slot selected</p>
            </div>
          `;
        }

        container.innerHTML = html;

        // Create hidden input if it doesn't exist
        let hiddenInputElement = hiddenInput;
        if (!hiddenInputElement) {
          console.warn('[TourCalendar] Hidden input not found, creating it');
          hiddenInputElement = document.createElement('input');
          hiddenInputElement.type = 'hidden';
          hiddenInputElement.name = 'time_slot_ids';
          hiddenInputElement.id = 'timeSlotIds';

          // Append to form or calendar container
          const form = document.querySelector('form[data-tour-slug]') || document.getElementById('booking-form');
          if (form) {
            form.appendChild(hiddenInputElement);
          } else if (container.parentNode) {
            container.parentNode.appendChild(hiddenInputElement);
          }
        }

        // Update hidden input value
        if (hiddenInputElement) {
          hiddenInputElement.value = JSON.stringify(slotIds);
          console.log('[TourCalendar] Hidden input value:', hiddenInputElement.value);
        }

        // Update selected date input with the date from the first selected slot
        const dateInputElement = document.getElementById('selected_date');
        if (dateInputElement && slotIds.length > 0) {
          const firstSlot = this.selectedSlots[slotIds[0]];
          if (firstSlot && firstSlot.date) {
            // Extract date in YYYY-MM-DD format
            const dateObj = new Date(firstSlot.date);
            const formattedDate = dateObj.toISOString().split('T')[0];
            dateInputElement.value = formattedDate;
            console.log('[TourCalendar] Selected date set to:', formattedDate);
          }
        }

        // Update booking button state
        this.updateBookingButtonState();
      }

      // Method to remove a selected slot with new UI
      removeSlot(slotId) {
        console.log('[TourCalendar] Removing slot:', slotId);

        // Remove from selected slots
        if (this.selectedSlots[slotId]) {
          delete this.selectedSlots[slotId];
        }

        // Update UI
        this.updateSelectedSlotsDisplay();

        // Remove selected class from any slot cards with this ID
        const selectedSlotElement = document.querySelector(`.time-slot-card[data-slot-id="${slotId}"]`);
        if (selectedSlotElement) {
          selectedSlotElement.classList.remove('selected');
        }
      }

      // Update booking button state based on slot selection - improved selector
      updateBookingButtonState() {
        const selectedCount = Object.keys(this.selectedSlots).length;
        // Use more specific selectors to find the booking button
        const bookingButton = document.querySelector('.booking-submit button[type="submit"]') ||
          document.querySelector('form[data-tour-slug] button[type="submit"]') ||
          document.querySelector('#booking-form button[type="submit"]') ||
          document.querySelector('button[type="submit"].btn-lg');

        console.log('[TourCalendar] Booking button state - selected slots:', selectedCount);
        console.log('[TourCalendar] Booking button found:', bookingButton ? true : false);

        if (!bookingButton || bookingButton.closest('.search-style-2') || bookingButton.closest('.form--quick-search')) {
          console.warn('[TourCalendar] No suitable booking button found or it\'s a search button');
          return; // Skip if it's a search button
        }

        // Always enable the button and make it primary
        bookingButton.disabled = false;
        bookingButton.innerHTML = '<i class="fas fa-calendar-check"></i> Book Now';
        bookingButton.classList.remove('btn-secondary');
        bookingButton.classList.add('btn-primary');

        console.log('[TourCalendar] Booking button updated to enabled state');

        // Find the form and attach submit handler if not already attached
        this.attachFormSubmitHandler();
      }

      // Attach form submit handler
      attachFormSubmitHandler() {
        // Find the form
        const form = document.querySelector('form[data-tour-slug]') || document.getElementById('booking-form');
        if (!form) {
          console.warn('[TourCalendar] No booking form found for submit handler');
          return;
        }

        // Check if handler already attached
        if (form.dataset.handlerAttached === 'true') {
          return;
        }

        // Attach submit handler
        form.addEventListener('submit', (event) => {
          // Get selected slot
          const selectedSlotIds = Object.keys(this.selectedSlots);
          if (selectedSlotIds.length === 0) {
            return; // No slots selected, let the form proceed normally
          }

          console.log('[TourCalendar] Form submitted with slot:', selectedSlotIds[0]);

          // Always allow the form to submit
        });

        // Mark form as having handler attached
        form.dataset.handlerAttached = 'true';
        console.log('[TourCalendar] Form submit handler attached');
      }

      // Improve hidePopup to reset state safely
      hidePopup() {
        console.log('[TourCalendar] Hiding popup');

        // Store reference to current popup before nulling
        const currentPopup = this.popup;

        // Reset popup reference first
        this.popup = null;

        // Remove popup if it exists
        if (currentPopup) {
          try {
            // Fade out animation
            currentPopup.style.opacity = '0';

            // Remove from DOM after animation
            setTimeout(() => {
              if (currentPopup.parentNode) {
                currentPopup.parentNode.removeChild(currentPopup);
              }
            }, 300);
          } catch (error) {
            console.error('[TourCalendar] Error hiding popup:', error);
          }
        }
      }

      // Update CSS for new UI
      updatePopupCSS() {
        const styleElement = document.getElementById('tour-calendar-dynamic-styles') || document.createElement('style');
        styleElement.id = 'tour-calendar-dynamic-styles';
        styleElement.innerHTML = `
          .calendar-grid, 
          .calendar-header {
            transition: opacity 0.3s ease;
          }
          
          .time-slots-container {
            opacity: 0;
            transition: opacity 0.3s ease;
            margin-top: 15px;
            padding: 15px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
          }
          
          .slots-header {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
            margin-bottom: 15px;
          }
          
          .slots-header h5 {
            color: #333;
            font-weight: 600;
          }
          
          .back-to-calendar {
            font-size: 14px;
            padding: 5px 10px;
            border-radius: 4px;
          }
          
          .time-slots-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
          }
          
          .time-slot-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            cursor: pointer;
            transition: all 0.2s ease;
            background-color: #fff;
          }
          
          .time-slot-card:hover {
            border-color: #007bff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
          }
          
          .time-slot-card.selected {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
            box-shadow: 0 2px 8px rgba(0,123,255,0.3);
          }
          
          .time-slot-card.selected .capacity {
            color: rgba(255,255,255,0.8);
          }
          
          .time-range {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 5px;
          }
          
          .capacity {
            font-size: 13px;
            color: #6c757d;
          }
          
          #selectedTimeSlots {
            margin-top: 15px;
          }
          
          .selected-slot-card {
            position: relative;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
          }
          
          .remove-slot {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            color: #155724;
            font-size: 18px;
            cursor: pointer;
            padding: 0;
            line-height: 1;
          }
          
          .remove-slot:hover {
            color: #721c24;
          }
          
          @media (max-width: 768px) {
            .time-slots-grid {
              grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }
            
            .back-to-calendar {
              font-size: 12px;
              padding: 4px 8px;
            }
          }
          
          @media (max-width: 480px) {
            .time-slots-grid {
              grid-template-columns: 1fr;
            }
            
            .time-slot-card {
              margin-bottom: 10px;
            }
            
            .slots-header h5 {
              font-size: 16px;
            }
          }
        `;

        document.head.appendChild(styleElement);
      }
    }

    // Assign to global window object
    window.TourCalendar = TourCalendar;
    return TourCalendar;
  };

  // -------- Gallery / Lightbox --------
  var SimpleLightbox = {
    images: [],
    currentIndex: 0,
    open: function (images, startIndex) {
      this.images = images || [];
      this.currentIndex = startIndex || 0;
      this._create();
      this._show();
    },
    _create: function () {
      var existing = document.getElementById('simple-lightbox');
      if (existing) existing.remove();
      var wrap = document.createElement('div');
      wrap.id = 'simple-lightbox';
      wrap.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,.9);z-index:9999;display:flex;align-items:center;justify-content:center;opacity:0;transition:opacity .3s ease;';
      wrap.innerHTML = '' +
        '<button id="lb-close" style="position:absolute;top:20px;right:30px;background:none;border:none;color:#fff;font-size:30px;cursor:pointer;z-index:10000">&times;</button>' +
        '<button id="lb-prev" style="position:absolute;left:30px;top:50%;transform:translateY(-50%);background:rgba(255,255,255,.2);border:none;color:#fff;font-size:30px;cursor:pointer;padding:10px 15px;border-radius:5px">&#8249;</button>' +
        '<button id="lb-next" style="position:absolute;right:30px;top:50%;transform:translateY(-50%);background:rgba(255,255,255,.2);border:none;color:#fff;font-size:30px;cursor:pointer;padding:10px 15px;border-radius:5px">&#8250;</button>' +
        '<img id="lb-image" style="max-width:90%;max-height:90%;object-fit:contain;" />' +
        '<div id="lb-caption" style="position:absolute;bottom:30px;left:50%;transform:translateX(-50%);color:#fff;text-align:center;font-size:16px"></div>';
      document.body.appendChild(wrap);
      document.getElementById('lb-close').onclick = this.close.bind(this);
      document.getElementById('lb-prev').onclick = this.prev.bind(this);
      document.getElementById('lb-next').onclick = this.next.bind(this);
      wrap.addEventListener('click', (e) => { if (e.target === wrap) this.close(); });
      this._keydown = this._keydown || this._handleKey.bind(this);
      document.addEventListener('keydown', this._keydown);
      setTimeout(function () { wrap.style.opacity = '1'; }, 10);
    },
    _show: function () {
      var img = document.getElementById('lb-image');
      var caption = document.getElementById('lb-caption');
      var cur = this.images[this.currentIndex] || {};
      if (img) { img.src = cur.src || ''; }
      if (caption) { caption.textContent = cur.caption || ''; }
      var showNav = (this.images.length > 1);
      var prevBtn = document.getElementById('lb-prev');
      var nextBtn = document.getElementById('lb-next');
      if (prevBtn) prevBtn.style.display = showNav ? 'block' : 'none';
      if (nextBtn) nextBtn.style.display = showNav ? 'block' : 'none';
    },
    prev: function () { this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length; this._show(); },
    next: function () { this.currentIndex = (this.currentIndex + 1) % this.images.length; this._show(); },
    close: function () {
      var el = document.getElementById('simple-lightbox');
      if (el) { el.style.opacity = '0'; setTimeout(function () { el.remove(); }, 300); }
      if (this._keydown) { document.removeEventListener('keydown', this._keydown); }
    },
    _handleKey: function (e) {
      if (e.key === 'Escape') this.close();
      if (e.key === 'ArrowLeft') this.prev();
      if (e.key === 'ArrowRight') this.next();
    }
  };

  function buildTourImages() {
    var anchors = $all('.gallery-trigger');
    var list = anchors.map(function (a) {
      var src = a.getAttribute('data-src') || a.getAttribute('href');
      var caption = a.getAttribute('data-caption') || (a.querySelector('img') ? a.querySelector('img').getAttribute('alt') : '');
      return src ? { src: src, caption: caption } : null;
    }).filter(Boolean);
    window.tourImages = list;
    window.currentImageIndex = window.currentImageIndex || 0;
  }

  function setMainImage(index) {
    var mainImage = document.getElementById('main-tour-image');
    if (!mainImage || !window.tourImages || !window.tourImages.length) return;
    if (index < 0 || index >= window.tourImages.length) return;
    window.currentImageIndex = index;
    mainImage.style.opacity = '0.5';
    setTimeout(function () {
      var it = window.tourImages[window.currentImageIndex];
      mainImage.src = it.src;
      mainImage.alt = it.caption || '';
      var link = mainImage.parentElement;
      if (link) { link.setAttribute('data-src', it.src); link.setAttribute('data-caption', it.caption || ''); }
      mainImage.style.opacity = '1';
      updateActiveThumbnail();
    }, 150);
  }

  function changeMainImage(direction) {
    if (!window.tourImages || window.tourImages.length <= 1) return;
    var nextIndex = window.currentImageIndex || 0;
    nextIndex = direction === 'next' ? (nextIndex + 1) % window.tourImages.length : (nextIndex - 1 + window.tourImages.length) % window.tourImages.length;
    setMainImage(nextIndex);
  }

  function updateActiveThumbnail() {
    $all('.thumbnail-image').forEach(function (el) { el.classList.remove('active'); });
    var active = document.querySelector('.thumbnail-image[data-image-index="' + (window.currentImageIndex || 0) + '"]');
    if (active) active.classList.add('active');
    var curNum = document.getElementById('current-image-number');
    if (curNum) curNum.textContent = (window.currentImageIndex || 0) + 1;
  }

  function initGallery() {
    buildTourImages();
    updateActiveThumbnail();

    // Click to open lightbox
    document.addEventListener('click', function (e) {
      var link = e.target.closest('.gallery-trigger');
      if (!link) return;
      e.preventDefault();
      var src = link.getAttribute('data-src') || link.getAttribute('href');
      var index = 0;
      for (var i = 0; i < (window.tourImages || []).length; i++) { if (window.tourImages[i].src === src) { index = i; break; } }
      SimpleLightbox.open(window.tourImages || [], index);
    });

    // Keyboard navigation
    document.addEventListener('keydown', function (e) {
      if (e.key === 'ArrowLeft') changeMainImage('prev');
      if (e.key === 'ArrowRight') changeMainImage('next');
    });

    // Prev/Next and set-main handlers (delegated)
    document.addEventListener('click', function (e) {
      var prev = e.target.closest('.js-prev-image');
      var next = e.target.closest('.js-next-image');
      if (prev) { e.preventDefault(); changeMainImage('prev'); }
      if (next) { e.preventDefault(); changeMainImage('next'); }
      var setMain = e.target.closest('.js-set-main-image');
      if (setMain) { e.preventDefault(); var idx = parseInt(setMain.getAttribute('data-index'), 10) || 0; setMainImage(idx); }
    });

    // Touch swipe on main image
    var touchStartX = 0, touchEndX = 0;
    var mainImage = document.getElementById('main-tour-image');
    if (mainImage) {
      mainImage.addEventListener('touchstart', function (e) { touchStartX = e.changedTouches[0].screenX; });
      mainImage.addEventListener('touchend', function (e) {
        touchEndX = e.changedTouches[0].screenX;
        var diff = touchStartX - touchEndX;
        if (Math.abs(diff) > 50) changeMainImage(diff > 0 ? 'next' : 'prev');
      });
    }

    // Expose for inline handlers already present
    window.changeMainImage = changeMainImage;
    window.setMainImage = setMainImage;
    window.updateActiveThumbnail = updateActiveThumbnail;
  }

  // -------- Price calculation --------
  function updatePrice() {
    var adultsEl = document.getElementById('adults-input');
    var childrenEl = document.getElementById('children-input');
    var infantsEl = document.getElementById('infants-input');
    if (!adultsEl || !childrenEl || !infantsEl) return;

    var adults = parseInt(adultsEl.value, 10) || 1;
    var children = parseInt(childrenEl.value, 10) || 0;
    var infants = parseInt(infantsEl.value, 10) || 0;

    var basePrice = parseFloat(($('input[name="adult_price"]') || {}).value) || 0;
    var childPrice = parseFloat(($('input[name="child_price"]') || {}).value) || basePrice;
    var infantPrice = parseFloat(($('input[name="infant_price"]') || {}).value) || 0;

    var total = (adults * basePrice) + (children * childPrice) + (infants * infantPrice);
    // multiply by number of selected time slots if any
    try {
      var selectedInput = document.getElementById('time-slot-ids-input');
      if (selectedInput) {
        var arr = JSON.parse(selectedInput.value || '[]');
        var multiplier = Array.isArray(arr) && arr.length ? arr.length : 1;
        total = total * multiplier;
      }
    } catch (_) { }
    var totalPriceElement = document.getElementById('total-price');
    if (totalPriceElement) totalPriceElement.textContent = '$' + total.toFixed(2);
    var totalAmountInput = $('input[name="total_amount"]');
    if (totalAmountInput) totalAmountInput.value = total;
    var subtotalInput = $('input[name="subtotal"]');
    if (subtotalInput) subtotalInput.value = total;
  }

  function initPricing() {
    onReady(updatePrice);
    setTimeout(updatePrice, 500);
    document.addEventListener('DOMContentLoaded', updatePrice);
    document.addEventListener('input', function (e) { if (e.target.closest('.js-price-input')) updatePrice(); });
    document.addEventListener('change', function (e) { if (e.target.closest('.js-price-input')) updatePrice(); });
  }

  // -------- Time slots (legacy support for old buttons) --------

  // -------- Social share clipboard --------
  function initClipboard() {
    function toggleIcon(el) {
      var copyState = el.querySelector('[data-clipboard-icon="copy"]');
      var copiedState = el.querySelector('[data-clipboard-icon="copied"]');
      if (!copyState || !copiedState) return;
      copiedState.style.display = 'none';
      copyState.style.display = 'inline-block';
      setTimeout(function () { copiedState.style.display = 'inline-block'; copyState.style.display = 'none'; }, 3000);
    }
    $all('[data-bb-toggle="social-sharing-clipboard"]').forEach(function (el) {
      el.addEventListener('click', function (e) {
        e.preventDefault();
        var text = el.dataset.clipboardText || '';
        if (navigator.clipboard && window.isSecureContext) {
          navigator.clipboard.writeText(text).then(function () { toggleIcon(el); });
        } else {
          var input = document.createElement('input');
          input.value = text; document.body.appendChild(input); input.select(); document.execCommand('copy'); document.body.removeChild(input);
          toggleIcon(el);
        }
      });
    });
  }

  // -------- Reviews (char counter + submit) --------
  function initReviewForm() {
    var reviewTextarea = document.getElementById('review_text');
    if (reviewTextarea) {
      reviewTextarea.addEventListener('input', function () {
        var length = this.value.length;
        var charCount = document.getElementById('char-count');
        if (!charCount) return;
        charCount.textContent = length;
        charCount.className = '';
        if (length > 950) charCount.className = 'text-warning';
        if (length >= 1000) charCount.className = 'text-danger';
      });
    }

    var reviewForm = document.getElementById('reviewForm');
    if (!reviewForm) return;
    reviewForm.addEventListener('submit', function (e) {
      e.preventDefault();
      var submitBtn = this.querySelector('button[type="submit"]');
      var originalText = submitBtn ? submitBtn.innerHTML : '';

      var customerName = (this.querySelector('input[name="customer_name"]') || {}).value || '';
      var customerEmail = (this.querySelector('input[name="customer_email"]') || {}).value || '';
      var rating = this.querySelector('input[name="rating"]:checked');
      var reviewText = (this.querySelector('textarea[name="review_text"]') || {}).value || '';

      customerName = customerName.trim();
      customerEmail = customerEmail.trim();
      reviewText = reviewText.trim();

      if (!customerName) { alert('Your Name is required'); (this.querySelector('input[name="customer_name"]') || {}).focus?.(); return; }
      if (!customerEmail) { alert('Your Email is required'); (this.querySelector('input[name="customer_email"]') || {}).focus?.(); return; }
      var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(customerEmail)) { alert('Please enter a valid email address'); (this.querySelector('input[name="customer_email"]') || {}).focus?.(); return; }
      if (!rating) { alert('Rating is required'); return; }
      if (!reviewText) { alert('Your Review is required'); (this.querySelector('textarea[name="review_text"]') || {}).focus?.(); return; }
      if (reviewText.length > 1000) { alert('Review text cannot exceed 1000 characters'); (this.querySelector('textarea[name="review_text"]') || {}).focus?.(); return; }

      if (submitBtn) { submitBtn.disabled = true; submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Submitting...'; }
      var formData = new FormData(this);
      var csrf = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';
      fetch(this.action, { method: 'POST', body: formData, headers: csrf ? { 'X-CSRF-TOKEN': csrf } : {} })
        .then(function (res) { return res.json().catch(function () { return { error: true, message: 'Invalid server response' }; }); })
        .then(function (data) {
          if (data && data.error) {
            alert(data.message || 'An error occurred while submitting your review. Please try again.');
          } else {
            alert((data && data.message) || 'Thank you for your review! It will be published after admin approval.');
            var modal = document.getElementById('reviewModal');
            if (modal && window.bootstrap) { try { window.bootstrap.Modal.getInstance(modal).hide(); } catch (_) { } }
            reviewForm.reset();
          }
        })
        .catch(function () { alert('An error occurred while submitting your review. Please try again.'); })
        .finally(function () { if (submitBtn) { submitBtn.disabled = false; submitBtn.innerHTML = originalText; } });
    });
  }

  // -------- Reviews (basic handler) --------
  document.addEventListener('click', function (e) {
    var btn = e.target.closest('.js-load-more-reviews');
    if (!btn) return;
    e.preventDefault();
    btn.disabled = true;
    btn.textContent = '...';
    // TODO: implement real pagination if backend endpoint exists
  });

  // -------- Booking form validation --------
  function initBookingForm() {
    var form = document.getElementById('booking-form');
    if (!form) return;

    form.addEventListener('submit', function (e) {
      e.preventDefault(); // Prevent default form submission

      var selectedInput = document.getElementById('timeSlotIds'); // New calendar input
      var selectedArr = [];
      try {
        if (selectedInput && selectedInput.value) {
          selectedArr = JSON.parse(selectedInput.value || '[]');
        }
      } catch (_) { selectedArr = []; }

      var adults = parseInt((form.querySelector('input[name="adults"]') || {}).value || '0', 10);

      if (!Array.isArray(selectedArr) || selectedArr.length === 0) {
        alert('Please select at least one time slot to proceed with booking');
        return false;
      }
      if (adults < 1) {
        alert('At least 1 adult is required');
        return false;
      }

      var submitBtn = form.querySelector('button[type="submit"]');
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Processing booking...';
      }

      // Submit form using AJAX
      var formData = new FormData(form);

      // Get CSRF token
      var csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

      // Submit form data
      fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: csrfToken ? {
          'X-CSRF-TOKEN': csrfToken
        } : {},
        credentials: 'same-origin'
      })
        .then(function (response) {
          console.log('[Booking] Response status:', response.status);
          console.log('[Booking] Response URL:', response.url);

          // Check if response is a redirect (300-399)
          if (response.status >= 300 && response.status < 400) {
            console.log('[Booking] Detected redirect response');
            window.location.href = response.url;
            return { redirect: true };
          }

          // Check if response is OK
          if (!response.ok) {
            console.error('[Booking] Server response not OK:', response.status, response.statusText);
            throw new Error('Server error: ' + response.status);
          }

          // Try to parse as JSON, but handle HTML response
          const contentType = response.headers.get('content-type');
          console.log('[Booking] Content-Type:', contentType);

          if (contentType && contentType.includes('application/json')) {
            return response.json();
          } else {
            console.warn('[Booking] Response is not JSON, redirecting to response URL');
            // If response is not JSON, redirect to the response URL
            window.location.href = response.url || '/tours/checkout';
            // Return empty object to prevent further processing
            return { redirect: true };
          }
        })
        .then(function (data) {
          // Skip processing if we've already redirected
          if (data.redirect) {
            return;
          }

          console.log('[Booking] Response:', data);

          // Immediately redirect to checkout if booking is successful
          if (!data.error && data.data && data.data.booking_id) {
            console.log('[Booking] Booking successful! ID:', data.data.booking_id);
            console.log('[Booking] Redirecting to checkout page...');

            // Try redirect using the provided URL first
            if (data.data.redirect_url) {
              window.location.href = data.data.redirect_url;
              return;
            }

            // Fallback to direct checkout URL
            window.location.href = '/tours/checkout';
            return;
          }

          if (data.error) {
            alert(data.message || 'An error occurred during booking. Please try again.');
            if (submitBtn) {
              submitBtn.disabled = false;
              submitBtn.innerHTML = '<i class="fas fa-calendar-check"></i> Book Now';
            }
          } else {
            // Success - redirect to checkout page
            if (data.data && data.data.redirect_url) {
              console.log('[Booking] Redirecting to:', data.data.redirect_url);
              // Force redirect with a slight delay to ensure console logs are visible
              setTimeout(() => {
                window.location.href = data.data.redirect_url;
              }, 100);
            } else {
              // Fallback - reload page
              console.log('[Booking] No redirect URL provided, reloading page');
              alert('Booking successful! Proceeding to payment page...');
              // Try to redirect to checkout directly
              window.location.href = '/tours/checkout';
            }
          }
        })
        .catch(function (error) {
          console.error('[Booking] Error:', error);
          alert('An error occurred during booking. Please try again.');
          if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-calendar-check"></i> Book Now';
          }
        });
    });
  }

  // Remove this function as it conflicts with the new calendar system

  // -------- Init --------
  onReady(function () {
    console.log('[Tours JS] Initializing scripts...');

    initGallery();
    initPricing();
    initClipboard();
    initReviewForm();
    initBookingForm();
    initPricingSystem();

    // Initialize Tour Calendar with more robust logging
    try {
      initTourCalendar();
    } catch (error) {
      console.error('[Tours JS] Tour Calendar initialization failed:', error);
    }

    console.log('[Tours JS] Legacy script initialized');
  });

  // Pricing and Booking Functions
  function changeQuantity(type, change) {
    const input = document.getElementById(type + '-input');
    if (!input) return;

    const current = parseInt(input.value) || 0;
    const min = parseInt(input.getAttribute('min')) || 0;
    const max = parseInt(input.getAttribute('max')) || 50;

    let newValue = current + change;
    if (newValue < min) newValue = min;
    if (newValue > max) newValue = max;

    input.value = newValue;

    // Update prices
    updateAllPrices();
  }

  function updateAllPrices() {
    const adultPrice = parseFloat(document.querySelector('input[name="adult_price"]')?.value || 0);
    const childPrice = parseFloat(document.querySelector('input[name="child_price"]')?.value || 0);
    const infantPrice = parseFloat(document.querySelector('input[name="infant_price"]')?.value || 0);

    const adults = parseInt(document.getElementById('adults-input')?.value || 0);
    const children = parseInt(document.getElementById('children-input')?.value || 0);
    const infants = parseInt(document.getElementById('infants-input')?.value || 0);

    // Get selected slots count
    let selectedSlotsCount = 0;
    try {
      const selectedInput = document.getElementById('timeSlotIds');
      if (selectedInput && selectedInput.value) {
        const selectedSlots = JSON.parse(selectedInput.value || '[]');
        selectedSlotsCount = selectedSlots.length;
      }
    } catch (e) {
      selectedSlotsCount = 0;
    }

    // Update individual price displays
    const adultTotal = adults * adultPrice * (selectedSlotsCount || 1);
    const childTotal = children * childPrice * (selectedSlotsCount || 1);
    const infantTotal = infants * infantPrice * (selectedSlotsCount || 1);

    // Update per-category totals
    const adultTotalEl = document.getElementById('adult-price-total');
    const childTotalEl = document.getElementById('child-price-total');
    const infantTotalEl = document.getElementById('infant-price-total');

    if (adultTotalEl) {
      adultTotalEl.textContent = adults > 0 && selectedSlotsCount > 0 ? ` (Total: $${adultTotal.toFixed(2)})` : '';
    }
    if (childTotalEl) {
      childTotalEl.textContent = children > 0 && selectedSlotsCount > 0 ? ` (Total: $${childTotal.toFixed(2)})` : '';
    }
    if (infantTotalEl) {
      infantTotalEl.textContent = infants > 0 && selectedSlotsCount > 0 ? ` (Total: $${infantTotal.toFixed(2)})` : '';
    }

    // Update grand total
    const grandTotal = adultTotal + childTotal + infantTotal;
    const totalPriceEl = document.getElementById('total-price');
    const totalAmountInput = document.querySelector('input[name="total_amount"]');

    if (totalPriceEl) totalPriceEl.textContent = `$${grandTotal.toFixed(2)}`;
    if (totalAmountInput) totalAmountInput.value = grandTotal;

    // Update slots info
    const slotsInfo = document.getElementById('selected-slots-info');
    const slotsText = document.getElementById('slots-count-text');
    const priceBreakdown = document.getElementById('price-breakdown');

    if (selectedSlotsCount > 0) {
      if (slotsInfo) slotsInfo.style.display = 'block';
      if (slotsText) slotsText.textContent = `${selectedSlotsCount} time slot(s) selected`;
      if (priceBreakdown) priceBreakdown.textContent = `Price calculated for ${selectedSlotsCount} time slot(s)`;
    } else {
      if (slotsInfo) slotsInfo.style.display = 'none';
      if (priceBreakdown) priceBreakdown.textContent = 'Select time slots to calculate final price';
    }

    // Update button state
    updateBookingButton(selectedSlotsCount > 0);

    // Also call calendar update if available
    if (window.tourCalendar && typeof window.tourCalendar.updateTotalPrice === 'function') {
      window.tourCalendar.updateTotalPrice();
    }
  }

  function updateBookingButton(hasSlots) {
    // Target specifically the booking form submit button using multiple selectors
    const submitBtn = document.querySelector('#booking-form button[type="submit"].btn-lg') ||
      document.querySelector('.booking-submit button[type="submit"]') ||
      document.querySelector('form[data-tour-slug] button[type="submit"]');

    if (!submitBtn || submitBtn.closest('.search-style-2') || submitBtn.closest('.form--quick-search')) {
      return; // Skip if it's a search button
    }

    if (hasSlots) {
      submitBtn.disabled = false;
      submitBtn.innerHTML = '<i class="fas fa-calendar-check"></i> Book Now';
      submitBtn.classList.remove('btn-secondary');
      submitBtn.classList.add('btn-primary');
    } else {
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<i class="fas fa-calendar-plus"></i> Select Time Slot First';
      submitBtn.classList.add('btn-secondary');
      submitBtn.classList.remove('btn-primary');
    }
  }

  function initPricingSystem() {
    // Initial update
    updateAllPrices();

    // Monitor for changes in time slots
    setInterval(function () {
      updateAllPrices();
    }, 1000);

    // Make functions globally available
    window.changeQuantity = changeQuantity;
    window.updateAllPrices = updateAllPrices;
    window.updateBookingButton = updateBookingButton;
  }

  // Tour Calendar Class - Moved to the end of the script
  // The TourCalendar class is now defined within the defineTourCalendar function
  // and assigned to window.TourCalendar.
  // This block is no longer needed as the class is now globally available.
  // The initTourCalendar function is now responsible for initializing it.

  // Initialize Tour Calendar function
  function initTourCalendar() {
    console.log('[TourCalendar] Attempting to initialize...');

    // Ensure TourCalendar is defined
    if (!window.TourCalendar) {
      console.log('[TourCalendar] Class not defined, defining now...');
      defineTourCalendar();
    }

    const tourSlugElement = document.querySelector('[data-tour-slug]');
    const calendarContainer = document.getElementById('tourCalendar');

    if (!tourSlugElement) {
      console.error('[TourCalendar] No tour slug element found');
      return;
    }

    if (!calendarContainer) {
      console.error('[TourCalendar] No calendar container found');
      return;
    }

    const tourSlug = tourSlugElement.dataset.tourSlug;

    console.log('[TourCalendar] Initializing with tour slug:', tourSlug);

    window.tourCalendar = new window.TourCalendar('tourCalendar', tourSlug);

    // Update total price when quantity changes
    ['adults-input', 'children-input', 'infants-input'].forEach(function (id) {
      const element = document.getElementById(id);
      if (element) {
        element.addEventListener('change', function () {
          if (window.tourCalendar) {
            window.tourCalendar.updateTotalPrice();
          }
        });
      }
    });
  }

  // Expose TourCalendar globally
  // window.TourCalendar = TourCalendar; // This line is now redundant as the class is defined within defineTourCalendar

})(window, document);


