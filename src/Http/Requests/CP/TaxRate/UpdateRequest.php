<?php

namespace DoubleThreeDigital\SimpleCommerce\Http\Requests\CP\TaxRate;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('edit tax rates');
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string'],
            'rate' => ['required', 'numeric'],
            'category' => ['required', 'string'], // TODO
            'zone' => ['required', 'string'], // TODO
        ];
    }
}
