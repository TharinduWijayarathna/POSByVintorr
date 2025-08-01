<?php

namespace App\Http\Requests\ReturnItems;

use Illuminate\Foundation\Http\FormRequest;

class AddReturnRequest extends FormRequest
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
            'remark' => ['nullable', 'max:250'],
        ];
    }

    public function messages()
    {
        return [
            'remark.max' => 'Remark must be less than 250 characters',
        ];
    }
}
