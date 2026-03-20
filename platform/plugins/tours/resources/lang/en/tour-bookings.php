<?php

return [
    'name' => 'Tour Bookings',
    'edit' => 'Edit Tour Booking',
    'create' => 'Create Tour Booking',
    'created_at' => 'Created at',
    'updated_at' => 'Updated at',

    'form' => [
        'booking_code' => 'Booking Code',
        'tour' => 'Tour',
        'time_slot' => 'Time Slot',
        'time_slots' => 'Time Slots',
        'select_time_slot' => 'Select Time Slot',
        'time_slot_help' => 'Choose a specific time slot for this booking (optional)',
        'customer_name' => 'Customer Name',
        'customer_email' => 'Customer Email',
        'customer_phone' => 'Customer Phone',
        'customer_address' => 'Customer Address',
        'adults' => 'Adults',
        'children' => 'Children',
        'infants' => 'Infants',
        'booking_date' => 'Booking Date',
        'total_amount' => 'Total Amount',
        'currency' => 'Currency',
        'payment_status' => 'Payment Status',
        'booking_status' => 'Booking Status',
        'special_requests' => 'Special Requests',
        'notes' => 'Notes',
    ],

    'payment_status' => [
        'pending' => 'Pending',
        'paid' => 'Paid',
        'failed' => 'Failed',
        'refunded' => 'Refunded',
    ],

    'booking_status' => [
        'pending' => 'Pending',
        'confirmed' => 'Confirmed',
        'cancelled' => 'Cancelled',
        'completed' => 'Completed',
    ],

    'validation' => [
        'tour_required' => 'Tour is required',
        'tour_exists' => 'Selected tour does not exist',
        'customer_name_required' => 'Customer name is required',
        'customer_email_required' => 'Customer email is required',
        'customer_email_email' => 'Please provide a valid email address',
        'adults_required' => 'Number of adults is required',
        'adults_min' => 'At least 1 adult is required',
        'children_min' => 'Number of children cannot be negative',
        'infants_min' => 'Number of infants cannot be negative',
        'booking_date_required' => 'Booking date is required',
        'booking_date_after' => 'Booking date must be today or in the future',
        'total_amount_min' => 'Total amount must be a positive number',
        'payment_status_in' => 'Invalid payment status',
        'booking_status_in' => 'Invalid booking status',
    ],
];