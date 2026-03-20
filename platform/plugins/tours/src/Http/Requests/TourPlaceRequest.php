<?php

namespace Botble\Tours\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class TourPlaceRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'image' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
            'status' => ['nullable', Rule::in(BaseStatusEnum::values())],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => __('plugins/tours::tours.form.place_name'),
            'image' => __('plugins/tours::tours.form.place_image'),
            'order' => __('plugins/tours::tours.form.order'),
            'status' => __('plugins/tours::tours.form.status'),
        ];
    }
}