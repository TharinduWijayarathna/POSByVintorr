<?php

namespace App\Http\Requests\PosReceipt;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBillRequest extends FormRequest
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
            'accepted_amount' => ['required', 'numeric', 'min:1', 'max:100000000000'],
            'remark' => ['nullable', 'string', 'max:120'],
        ];
    }
    function messages()
    {
        return [
            'accepted_amount.required' => ' The paid amount is required',
            'accepted_amount.max' => 'The paid amount must be less than 100,000,000,000',
        ];
    }
}
