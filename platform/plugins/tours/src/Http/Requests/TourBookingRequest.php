<?php

namespace Botble\Tours\Http\Requests;

use Botble\Base\Rules\OnOffRule;
use Botble\Support\Http\Requests\Request;

class TourBookingRequest extends Request
{
    public function rules(): array
    {
        return [
            'tour_id' => ['required', 'integer', 'exists:tours,id'],
            'time_slot_id' => ['nullable', 'integer', 'exists:tour_time_slots,id'],
            'time_slot_ids' => ['nullable', 'json'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:20'],
            'customer_nationality' => ['nullable', 'string', 'max:100'],
            'customer_address' => ['nullable', 'string'],
            'adults' => ['required', 'integer', 'min:1'],
            'children' => ['nullable', 'integer', 'min:0'],
            'infants' => ['nullable', 'integer', 'min:0'],
            'tour_date' => ['nullable', 'date', 'after_or_equal:today'],
            'total_amount' => ['nullable', 'numeric', 'min:0'],

            'payment_status' => ['nullable', 'string', 'in:pending,paid,failed,refunded'],
            'status' => ['nullable', 'string', 'in:pending,confirmed,cancelled,completed'],
            'special_requirements' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'tour_id.required' => trans('plugins/tours::tour-bookings.validation.tour_required'),
            'tour_id.exists' => trans('plugins/tours::tour-bookings.validation.tour_exists'),
            'customer_name.required' => trans('plugins/tours::tour-bookings.validation.customer_name_required'),
            'customer_email.required' => trans('plugins/tours::tour-bookings.validation.customer_email_required'),
            'customer_email.email' => trans('plugins/tours::tour-bookings.validation.customer_email_email'),
            'adults.required' => trans('plugins/tours::tour-bookings.validation.adults_required'),
            'adults.min' => trans('plugins/tours::tour-bookings.validation.adults_min'),
            'children.min' => trans('plugins/tours::tour-bookings.validation.children_min'),
            'infants.min' => trans('plugins/tours::tour-bookings.validation.infants_min'),
            'tour_date.required' => trans('plugins/tours::tour-bookings.validation.booking_date_required'),
            'tour_date.after_or_equal' => trans('plugins/tours::tour-bookings.validation.booking_date_after'),
            'total_amount.min' => trans('plugins/tours::tour-bookings.validation.total_amount_min'),
            'payment_status.in' => trans('plugins/tours::tour-bookings.validation.payment_status_in'),
            'status.in' => trans('plugins/tours::tour-bookings.validation.booking_status_in'),
        ];
    }
}