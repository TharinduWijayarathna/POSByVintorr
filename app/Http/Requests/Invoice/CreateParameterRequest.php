<?php

namespace App\Http\Requests\Invoice;

use App\Models\InvoiceItemParameter;
use Illuminate\Foundation\Http\FormRequest;

class CreateParameterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'title' => ['required', 'string', 'max:40'],
        ];
    }

    function messages()
    {
        return [
            'title.required' => "The custom title is required.",
        ];
    }
}
