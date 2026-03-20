<?php

namespace Botble\Tours\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;

class TourReviewRequest extends Request
{
    public function rules(): array
    {
        return [
            'tour_id' => 'required|exists:tours,id',
            'rating' => 'required|numeric|min:0|max:5',
            'review' => 'nullable|string|max:2000',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'is_approved' => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'tour_id.required' => trans('plugins/tours::tour-reviews.validation.tour_required'),
            'tour_id.exists' => trans('plugins/tours::tour-reviews.validation.tour_not_found'),
            'rating.required' => trans('plugins/tours::tour-reviews.validation.rating_required'),
            'rating.numeric' => trans('plugins/tours::tour-reviews.validation.rating_numeric'),
            'rating.min' => trans('plugins/tours::tour-reviews.validation.rating_min'),
            'rating.max' => trans('plugins/tours::tour-reviews.validation.rating_max'),
            'review.max' => trans('plugins/tours::tour-reviews.validation.review_max'),
            'customer_name.required' => trans('plugins/tours::tour-reviews.validation.customer_name_required'),
            'customer_name.max' => trans('plugins/tours::tour-reviews.validation.customer_name_max'),
            'customer_email.required' => trans('plugins/tours::tour-reviews.validation.customer_email_required'),
            'customer_email.email' => trans('plugins/tours::tour-reviews.validation.customer_email_valid'),
            'customer_email.max' => trans('plugins/tours::tour-reviews.validation.customer_email_max'),
        ];
    }

    public function attributes(): array
    {
        return [
            'tour_id' => trans('plugins/tours::tour-reviews.form.tour'),
            'rating' => trans('plugins/tours::tour-reviews.form.rating'),
            'review' => trans('plugins/tours::tour-reviews.form.review'),
            'customer_name' => trans('plugins/tours::tour-reviews.form.customer_name'),
            'customer_email' => trans('plugins/tours::tour-reviews.form.customer_email'),
            'is_approved' => trans('plugins/tours::tour-reviews.form.is_approved'),
        ];
    }
} 