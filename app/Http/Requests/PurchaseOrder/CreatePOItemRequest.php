<?php

namespace App\Http\Requests\PurchaseOrder;

use Illuminate\Foundation\Http\FormRequest;

class CreatePOItemRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'product_id' => ['required'],
            'quantity' => ['required', 'numeric', 'min:1', 'max:10000'],
            'cost' => ['required', 'numeric', 'min:0', 'max:100000000000'],
        ];
    }

    public function messages()
    {
        return [
            'product_id.required' => 'Please select a product',
            'quantity.max' => 'Quantity must be less than 10,000',
            'cost.max' => 'Cost must be less than 100,000,000,000',

            'quantity.numeric' => 'Quantity must be a number',
            'cost.numeric' => 'Cost must be a number',
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
            $price = $this->input('cost');

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
