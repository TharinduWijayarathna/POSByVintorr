<?php

namespace App\Http\Requests\PurchaseOrder;

use Illuminate\Foundation\Http\FormRequest;

class CreateParameterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function rules()
    {
        return [
            'title' => ['required', 'string', 'max:40'],
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'The custom title is required',
        ];
    }
}
