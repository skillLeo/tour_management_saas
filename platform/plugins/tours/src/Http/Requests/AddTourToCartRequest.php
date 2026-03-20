<?php

namespace Botble\Tours\Http\Requests;

use Botble\Support\Http\Requests\Request;

class AddTourToCartRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|exists:tours,id',
            'qty' => 'required|integer|min:1',
            'tour_date' => 'required|date|after_or_equal:today',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'infants' => 'nullable|integer|min:0',
        ];
    }
}