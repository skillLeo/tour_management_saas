<?php

namespace Botble\Tours\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class TourCityRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:400',
            'order' => 'required|integer|min:0',
            'status' => 'required|' . Rule::in(BaseStatusEnum::values()),
        ];
    }
}
