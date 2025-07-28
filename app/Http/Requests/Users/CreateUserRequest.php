<?php

namespace App\Http\Requests\Users;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:' . User::class],
            'password' => ['required', 'min:8'],
            'con_password' => ['required', 'min:8', 'same:password'],
            'user_role_id' => ['required', 'numeric'],
        ];
    }

    /**
     * messages
     *
     * @return void
     */
    public function messages()
    {
        return [
            'user_role_id.required' => 'User role is required',
            'user_role_id.numeric' => 'Please select a user type',
            'con_password.required' => 'Confirmation password is required',
            'con_password.same' => 'The confirmation password must match the password',
        ];
    }
}
