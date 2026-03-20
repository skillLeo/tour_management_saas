<?php

namespace Botble\Tours\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class TourRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:tours,slug,' . $this->route('tour'),
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'gallery' => 'nullable|array',
            'duration_days' => 'nullable|integer|min:0',
            'duration_hours' => 'nullable|integer|min:0',
            'duration_nights' => 'nullable|integer|min:0',
            'max_people' => 'required|integer|min:1',
            'min_people' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'children_price' => 'nullable|numeric|min:0',
            'infants_price' => 'nullable|numeric|min:0',
            'sale_percentage' => 'nullable|numeric|min:0|max:100',

            'location' => 'nullable|string|max:255',
            'departure_location' => 'nullable|string|max:255',
            'return_location' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'included_services' => 'nullable|string',
            'excluded_services' => 'nullable|string',
            'activities' => 'nullable|string',
            'tour_highlights' => 'nullable|string',
            'itinerary' => 'nullable|array',
            'is_featured' => 'nullable|boolean',
            'allow_booking' => 'nullable|boolean',
            'booking_advance_days' => 'nullable|integer|min:0',
            'category_id' => 'required|exists:tour_categories,id',
            'city_id' => 'nullable|exists:tour_cities,id',
            'status' => Rule::in(BaseStatusEnum::values()),
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string|max:255',
        ];
    }

    public function attributes(): array
    {
        return [
            'category_id' => trans('plugins/tours::tours.form.category'),
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => trans('plugins/tours::tours.validation.category_required'),
            'category_id.exists' => trans('plugins/tours::tours.validation.category_exists'),
        ];
    }
}