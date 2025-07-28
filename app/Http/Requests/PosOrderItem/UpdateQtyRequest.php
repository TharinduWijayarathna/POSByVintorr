<?php

namespace App\Http\Requests\PosOrderItem;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQtyRequest extends FormRequest
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
            'product_name' => 'required|string|max:255',
            'description' => 'nullable',
            'quantity' => 'required|numeric|min:1|max:10000',
            'unit_price' => 'required|numeric|min:0|max:100000000000',
        ];
    }

    public function messages()
    {
        return [
            'product_name.required' => 'Product name is required',
            'quantity.required' => 'Quantity field is required',
            'quantity.max' => 'Quantity must be less than 10,000',
            'unit_price.max' => 'Unit price must be less than 100,000,000,000',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $quantity = $this->input('quantity');
            $price = $this->input('unit_price');

            if (! is_numeric($quantity) || ! is_numeric($price)) {
                $validator->errors()->add('product_amount', 'The quantity and price must be numbers.');

                return;
            }

            if ($quantity * $price > 999999999999) {
                $validator->errors()->add('product_amount', 'The Line Total seems too large. Please reduce the quantity or the price.');
            }
        });
    }
}
