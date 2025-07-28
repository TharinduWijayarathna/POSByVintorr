<?php

namespace App\Http\Requests\PosOrderItem;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderProductRequest extends FormRequest
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
            'unit_price' => 'required|numeric|min:1|max:100000000000',
            'quantity' => 'required|integer|min:1|max:10000',
        ];
    }
    function messages()
    {
        return [
            'unit_price.required' => 'Unit price is required',
            'unit_price.max' => 'Unit price must be less than 100,000,000,000',
            'quantity.required' => 'Quantity is required',
            'quantity.max' => 'Quantity must be less than 10,000',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $quantity = $this->input('quantity');
            $price = $this->input('unit_price');

            if (!is_numeric($quantity) || !is_numeric($price)) {
                $validator->errors()->add('product_amount', 'The quantity and price must be numbers.');
                return;
            }

            if ($quantity * $price > 999999999999) {
                $validator->errors()->add('product_amount', 'The Line Total seems too large. Please reduce the quantity or the price.');
            }
        });
    }
}
