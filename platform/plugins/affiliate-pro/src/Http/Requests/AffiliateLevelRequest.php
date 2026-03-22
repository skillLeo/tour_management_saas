<?php

namespace Botble\AffiliatePro\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class AffiliateLevelRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'min_commission' => ['required', 'numeric', 'min:0'],
            'max_commission' => ['nullable', 'numeric', 'min:0', 'gt:min_commission'],
            'commission_rate' => ['required', 'numeric', 'min:0'],
            'status' => Rule::in(BaseStatusEnum::values()),
            'order' => ['required', 'integer', 'min:0'],
        ];
    }
}
