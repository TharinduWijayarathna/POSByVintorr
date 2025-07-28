<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class CreateEmployeeRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'email2' => ['nullable', 'email', 'max:255'],
            'email3' => ['nullable', 'email', 'max:255'],
            'contact' => ['nullable', 'numeric'],
            'contact2' => ['nullable', 'numeric'],
            'contact3' => ['nullable', 'numeric'],
            'website' => ['nullable', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name field is required',
            'contact.required' => 'The contact field is required',

            'email.email' => 'The email must be a valid email address',
            'contact.numeric' => 'The contact must be numeric',
        ];
    }
}
