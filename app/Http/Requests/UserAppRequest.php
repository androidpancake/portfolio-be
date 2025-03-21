<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserAppRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|unique:users,user_id',
            'name' => 'nullable',
            'username' => 'required',
            'password' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Username Required',
            'password.required' => 'Password Required'
        ];
    }
}
