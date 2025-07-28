<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'barcode' => ['nullable', 'string', 'max:255'],
            'product_category_id' => ['nullable', 'numeric'],
            'cost' => ['nullable', 'numeric', 'min:0', 'max:100000000000'],
            'selling' => ['required', 'numeric', 'min:0', 'max:100000000000'],
            'stock_quantity' => ['nullable', 'numeric', 'min:0', 'max:10000'],
            'rol' => ['nullable', 'numeric', 'min:0', 'max:100000000000'],
            'unit' => ['nullable', 'numeric'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:5100'],
            'description' => ['nullable', 'string', 'max:120'],
            'status' => ['nullable', 'boolean'],
            'order_no' => ['nullable', 'numeric', 'min:1'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => ' The product name is required',
            'selling.required' => ' The product selling price is required',
            'cost.max' => ' The product cost must be less than 100,000,000,000',
            'selling.max' => ' The product selling price must be less than 100,000,000,000',
            'image.image' => ' The product image must be an image',
            'image.max' => ' The product image must be less than 5MB',

            'cost.numeric' => ' The product cost must be a number',
            'selling.numeric' => ' The product selling price must be a number',
            'stock_quantity.numeric' => ' The product quantity must be a number',
            'rol.numeric' => ' The product rol must be a number',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $quantity = $this->input('stock_quantity');
            $price = $this->input('selling');

            // if (!is_numeric($quantity) || !is_numeric($price)) {
            //     $validator->errors()->add('product_amount', 'The quantity and price must be numbers.');
            //     return;
            // }

            // if ($quantity * $price > 999999999999) {
            //     $validator->errors()->add('product_amount', 'The Line Total seems too large. Please reduce the quantity or the price.');
            // }

            if ($quantity !== null && $price !== null && is_numeric($quantity) && is_numeric($price)) {
                if ($quantity * $price > 999999999999) {
                    $validator->errors()->add('product_amount', 'The Line Total seems too large. Please reduce the quantity or the price.');
                }
            } else {
                return;
            }
        });
    }
}
