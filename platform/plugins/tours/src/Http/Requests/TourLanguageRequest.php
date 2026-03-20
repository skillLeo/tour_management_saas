<?php

namespace Botble\Tours\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class TourLanguageRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => [
                'required',
                'string',
                'max:10',
                Rule::unique('tour_languages')->ignore($this->route('tour_language')),
            ],
            'flag' => 'nullable|string|max:255',
            'order' => 'nullable|integer|min:0',
            'status' => 'required|' . Rule::in(BaseStatusEnum::values()),
        ];
    }
}
