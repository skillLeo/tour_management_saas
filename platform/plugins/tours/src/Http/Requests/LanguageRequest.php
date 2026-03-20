<?php

namespace Botble\Tours\Http\Requests;

use Botble\Base\Rules\OnOffRule;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class LanguageRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required',
                'string',
                'max:10',
                Rule::unique('languages', 'code')->ignore($this->route('language')),
            ],
            'flag' => ['nullable', 'string'],
            'order' => ['required', 'integer', 'min:0'],
            'status' => ['required', new OnOffRule()],
        ];
    }
}
