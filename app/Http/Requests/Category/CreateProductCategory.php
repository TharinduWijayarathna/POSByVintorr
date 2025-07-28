<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateProductCategory extends FormRequest
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
        $categoryId = $this->route('category_id');

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('product_categories')->where(function ($query) {
                return $query->whereNull('deleted_at');
            })->ignore($categoryId)],
            'remarks' => ['nullable', 'string', 'max:255'],

        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The category name is required.',

            'name.max' => 'The category name must not exceed 255 characters.',
            'remarks.max' => 'The remarks must not exceed 255 characters.',
        ];
    }
}
