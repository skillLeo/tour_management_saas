<?php

namespace Botble\Tours\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;

class VendorTourRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'content' => 'nullable|string',
            'image' => 'nullable|string',
            'gallery' => 'nullable|array',
            'gallery.*' => 'string',
            
            // Duration
            'duration_days' => 'nullable|integer|min:0|max:365',
            'duration_nights' => 'nullable|integer|min:0|max:365',
            'duration_hours' => 'nullable|integer|min:0|max:24',
            
            // Capacity
            'max_people' => 'required|integer|min:1|max:1000',
            'min_people' => 'required|integer|min:1|max:1000',
            
            // Pricing
            'price' => 'required|numeric|min:0',
            'children_price' => 'nullable|numeric|min:0',
            'infants_price' => 'nullable|numeric|min:0',
            'sale_percentage' => 'nullable|numeric|min:0|max:100',
            'currency' => 'nullable|string|size:3',
            
            // Location
            'location' => 'nullable|string|max:255',
            'departure_location' => 'nullable|string|max:255',
            'return_location' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            
            // Features
            'included_services' => 'nullable|array',
            'included_services.*' => 'string|max:255',
            'excluded_services' => 'nullable|array',
            'excluded_services.*' => 'string|max:255',
            'activities' => 'nullable|array',
            'activities.*' => 'string|max:255',
            'tour_highlights' => 'nullable|array',
            'tour_highlights.*' => 'string|max:255',
            'itinerary' => 'nullable|array',
            
            // Settings
            'is_featured' => 'nullable|boolean',
            'allow_booking' => 'nullable|boolean',
            'booking_advance_days' => 'nullable|integer|min:0|max:365',
            
            // Relationships
            'category_id' => 'required|exists:tour_categories,id',
            'city_id' => 'required|exists:tour_cities,id',
            
            // SEO
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            
            // Related data
            'faqs' => 'nullable|array',
            'faqs.*.question' => 'required_with:faqs|string|max:500',
            'faqs.*.answer' => 'required_with:faqs|string',
            'faqs.*.order' => 'nullable|integer|min:0',
            
            'places' => 'nullable|array',
            'places.*.name' => 'required_with:places|string|max:255',
            'places.*.description' => 'nullable|string',
            'places.*.image' => 'nullable|string',
            'places.*.order' => 'nullable|integer|min:0',
            
            'schedules' => 'nullable|array',
            'schedules.*.title' => 'required_with:schedules|string|max:255',
            'schedules.*.description' => 'nullable|string',
            'schedules.*.duration' => 'nullable|string|max:100',
            'schedules.*.order' => 'nullable|integer|min:0',
            
            'time_slots' => 'nullable|array',
            'time_slots.*.date' => 'required_with:time_slots|date',
            'time_slots.*.start_time' => 'required_with:time_slots|date_format:H:i',
            'time_slots.*.end_time' => 'nullable|date_format:H:i|after:time_slots.*.start_time',
            'time_slots.*.max_participants' => 'nullable|integer|min:1',
            'time_slots.*.price' => 'nullable|numeric|min:0',
            'time_slots.*.is_available' => 'nullable|boolean',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => __('Tour Name'),
            'description' => __('Description'),
            'content' => __('Content'),
            'image' => __('Featured Image'),
            'gallery' => __('Gallery'),
            'duration_days' => __('Duration (Days)'),
            'duration_nights' => __('Duration (Nights)'),
            'duration_hours' => __('Duration (Hours)'),
            'max_people' => __('Maximum People'),
            'min_people' => __('Minimum People'),
            'price' => __('Price'),
            'children_price' => __('Children Price'),
            'infants_price' => __('Infants Price'),
            'sale_percentage' => __('Sale Percentage'),
            'currency' => __('Currency'),
            'location' => __('Location'),
            'departure_location' => __('Departure Location'),
            'return_location' => __('Return Location'),
            'latitude' => __('Latitude'),
            'longitude' => __('Longitude'),
            'included_services' => __('Included Services'),
            'excluded_services' => __('Excluded Services'),
            'activities' => __('Activities'),
            'tour_highlights' => __('Tour Highlights'),
            'itinerary' => __('Itinerary'),
            'is_featured' => __('Is Featured'),
            'allow_booking' => __('Allow Booking'),
            'booking_advance_days' => __('Booking Advance Days'),
            'category_id' => __('Category'),
            'city_id' => __('City'),
            'meta_title' => __('Meta Title'),
            'meta_description' => __('Meta Description'),
            'meta_keywords' => __('Meta Keywords'),
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => trans('plugins/tours::tours.validation.category_required'),
            'category_id.exists' => trans('plugins/tours::tours.validation.category_exists'),
            'min_people.max' => __('Minimum people cannot exceed :max.'),
            'max_people.min' => __('Maximum people must be at least 1.'),
            'price.min' => __('Price must be greater than or equal to 0.'),
            'sale_percentage.between' => __('Sale percentage must be between 0 and 100.'),
            'latitude.between' => __('Latitude must be between -90 and 90.'),
            'longitude.between' => __('Longitude must be between -180 and 180.'),
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_featured' => $this->boolean('is_featured'),
            'allow_booking' => $this->boolean('allow_booking'),
        ]);

        // Ensure min_people is not greater than max_people
        if ($this->filled(['min_people', 'max_people']) && $this->min_people > $this->max_people) {
            $this->merge(['min_people' => $this->max_people]);
        }
    }
}
