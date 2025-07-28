<?php

namespace App\Http\Requests\ReturnItems;

use Illuminate\Foundation\Http\FormRequest;

class AddItemsRequest extends FormRequest
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
            'product_id' => 'required|exists:products,id',
            'unit_price' => 'required|numeric|min:0|max:100000000000',
            'quantity' => 'required|numeric|min:1|max:10000',
            'customer_id' => 'nullable',

        ];
    }

    public function messages()
    {
        return [
            'product_id.required' => 'Product is required',
            'unit_price.required' => 'Price is required',
            'unit_price.numeric' => 'Price must be a number',
            'unit_price.min' => 'Price must be valid number',
            'unit_price.max' => 'Price must be less than 100,000,000,000',
            // 'quantity.numeric' => 'Quantity must be a number',
            'quantity.max' => 'Quantity must be less than 10,000',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $quantity = $this->input('quantity');
            $price = $this->input('unit_price');

            if (is_numeric($quantity) && is_numeric($price) && ($quantity * $price > 999999999999)) {
                $validator->errors()->add('product_amount', 'The Line Total seems too large. Please reduce the quantity or the price.');
            }
        });
    }
}
