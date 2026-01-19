<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;


class RegisterRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['required', 'string', 'max:100'],
            'email'      => ['required', 'email', 'unique:users,email'],
            'password'  => ['required', 'confirmed', Password:: min(8) -> mixedCase()],
            'esncard_code' => ['required', 'string', 'unique:users,esncard_code'],
            'terms'          => ['accepted']
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'Please enter your First Name.',
            'last_name.required' => 'Please enter your Last Name.',
            'email.required' => 'Please enter your email.',
            'esncard_code.unique' => 'The provided ESNcard code is invalid.',
            'password.required' => 'Please enter a password.',
            'esncard_code.required' => 'Please enter your ESNcard code.',
            'terms.required' => 'You must accept the Terms and Conditions.',
        ];
    }
}
