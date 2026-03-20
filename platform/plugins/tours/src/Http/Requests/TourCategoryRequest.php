<?php

namespace Botble\Tours\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class TourCategoryRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:120',
            'slug' => 'required|string|max:120|unique:tour_categories,slug,' . $this->route('tour_category'),
            'description' => 'nullable|string|max:400',
            'image' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:60',
            'order' => 'nullable|integer|min:0',
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
} 