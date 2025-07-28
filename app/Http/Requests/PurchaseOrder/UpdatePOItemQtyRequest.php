<?php

namespace App\Http\Requests\PurchaseOrder;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePOItemQtyRequest extends FormRequest
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
            'product_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable'],
            'quantity' => ['required', 'numeric', 'min:1', 'max:10000'],
            'cost' => ['required', 'numeric', 'min:0', 'max:100000000000'],
        ];
    }

    public function messages()
    {
        return [
            'product_name.required' => 'Product name is required',
            'quantity.required' => 'Quantity field is required',
            'quantity.max' => 'Quantity must be less than 10,000',
            'cost.max' => 'Cost must be less than 100,000,000,000',

            'quantity.numeric' => 'Quantity must be a number',
            'cost.numeric' => 'Cost must be a number',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $quantity = $this->input('quantity');
            $price = $this->input('cost');

            if (! is_numeric($quantity) || ! is_numeric($price)) {
                $validator->errors()->add('product_amount', 'The quantity and cost must be numbers.');

                return;
            }

            if ($quantity * $price > 999999999999) {
                $validator->errors()->add('product_amount', 'The Line Total seems too large. Please reduce the quantity or the cost.');
            }
        });
    }
}
