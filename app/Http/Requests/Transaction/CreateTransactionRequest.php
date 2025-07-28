<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class CreateTransactionRequest extends FormRequest
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
            'sign' => ['required', 'numeric'],
            'date' => ['required', 'date'],
            'currency_id' => ['required', 'numeric'],
            'amount' => ['required', 'numeric', 'min:1', 'max:100000000000'],
            'remark' =>  ['nullable', 'string', 'max:120'],
        ];
    }
    function messages()
    {
        return [
            'currency_id.required' => "Currency is required.",
            'amount.max' => "Amount must be less than 100,000,000,000",
        ];
    }
}
