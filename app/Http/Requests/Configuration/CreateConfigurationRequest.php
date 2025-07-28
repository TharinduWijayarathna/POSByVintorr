<?php

namespace App\Http\Requests\Configuration;

use Illuminate\Foundation\Http\FormRequest;

class CreateConfigurationRequest extends FormRequest
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
            'image' => ['nullable', 'file', 'mimes:jpeg,png,jpg,gif,svg', 'max:5120'],
            'banner_image' => ['nullable','mimes:jpeg,png,jpg,gif,svg', 'max:5120'],
            'name' => ['required', 'string', 'max:120'],
            'address' => ['nullable', 'string', 'max:255'],
            'mobile' =>  ['nullable'],
            'email' =>  ['nullable', 'email', 'max:255'],
            'currency_id' => ['required', 'numeric'],
            'footer' => ['nullable', 'string', 'max:60'],
            'color_code' => ['nullable'],
            'account_api_key' => ['nullable'],
        ];
    }
    function messages()
    {
        return[
            'name.required' => "The business name is required.",
            'currency_id.required' => "Currency is required.",
            'image.image' => ' The logo image must be an image',
            'image.max' => ' The logo image must be less than 5MB',
            'image.mimes' => 'The logo image must be a valid image file of type: jpeg, png, jpg, gif, svg.',
        ];
    }
}
