<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Validator;

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
    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {

            if ($validator->errors()->has('esncard_code')) {
                return;
            }

            try {
                $response = Http::timeout(10)
                    ->withHeaders([
                        'x-bypass-cf-api' => config('services.esn.bypass_key'),
                    ])
                    ->get('https://esncard.org/services/1.0/card.json', [
                        'code' => $this->esncard_code,
                    ]);
            } catch (\Throwable $e) {
                $validator->errors()->add(
                    'esncard_code',
                    'Could not validate ESN card. Try again later.'
                );
                return;
            }

            if (!$response->successful()) {
                $validator->errors()->add(
                    'esncard_code',
                    'Could not validate ESN card. Try again later.'
                );
                return;
            }

            $data = $response->json();

            if (empty($data) || ($data[0]['status'] ?? '') !== 'active') {
                $validator->errors()->add(
                    'esncard_code',
                    'Invalid ESNcard code.'
                );
            }
        });
    }
}
