<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class CreateTargetsRequest extends FormRequest
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
            'weekly_target' => 'nullable|numeric|min:0|max:1000000000',
            'monthly_target' => 'nullable|numeric|min:0',
            'yearly_target' => 'nullable|numeric|min:0',
        ];
    }
}
