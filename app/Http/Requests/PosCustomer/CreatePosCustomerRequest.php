<?php

namespace App\Http\Requests\PosCustomer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreatePosCustomerRequest extends FormRequest
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
            'contact' =>  ['required','numeric'],
            'name' =>  ['required','string','max:255'],
            'email' =>  ['nullable','email','max:255'],
            'address' =>  ['nullable','string'],
        ];
    }

    public function messages()
    {
        return [
            'contact.required' => "The contact field is required.",
            // 'contact.digits' => "The contact must be 10 digits.",
            'name.required' => "The name field is required."
        ];
    }
}
