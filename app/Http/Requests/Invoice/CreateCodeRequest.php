<?php

namespace App\Http\Requests\Invoice;

use App\Models\PosOrder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateCodeRequest extends FormRequest
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
            'code' => [
                'required',
                'string',
                'max:20',
                'unique:' . PosOrder::class
            ],
        ];
    }

    function messages()
    {
        return [
            'code.required' => "The invoice code is required",
            'code.unique' => 'This invoice code already exists',
        ];
    }
}
