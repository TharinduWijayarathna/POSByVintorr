<?php

namespace App\Http\Requests\PosReceipt;

use Illuminate\Foundation\Http\FormRequest;

class CreateBillRequest extends FormRequest
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
            'balance' => ['required', 'numeric', 'min:1', 'max:100000000000'],
            'remark' => ['nullable', 'string', 'max:80'],
        ];
    }
    function messages()
    {
        return[
            'balance.required' => 'The amount is required',
            'balance.numeric' => 'The amount field must be a number',
            'balance.min' => 'The amount must be greater than 1',
            'balance.max' => 'The amount must be less than 100,000,000,000',
        ];
    }
}
