<?php

namespace App\Http\Requests\Payroll;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class CreatePayrollRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'supplier_id' => ['nullable', 'numeric'],
            'employee_id' => ['required', 'numeric'],
            'date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:500'],
            'currency_id' => ['required', 'numeric'],
            'amount' => ['required', 'numeric', 'min:1', 'max:100000000000'],
            'image' => ['nullable', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ];
    }
    function messages()
    {
        return [
            'employee_id.required' => 'Please select an employee',
            'currency_id.required' => 'Currency is required',
            'amount.required' => 'The amount is required',
            'amount.max' => 'The amount must be less than 100,000,000,000',
            'image.image' => 'The product image must be an image',
            'image.max' => 'The receipt must be less than 5MB',
        ];
    }
}
