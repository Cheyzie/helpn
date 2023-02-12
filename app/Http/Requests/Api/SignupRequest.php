<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class SignupRequest extends FormRequest
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
     * @return array<string, array<int, string|Rule>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password'=> ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()]
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please say your name',
            'email.required' => 'Email is required',
            'email.unique' => 'This email is used, try to login',
            'password.confirmed' => 'A password confirmation doesnt match',
        ];
    }
}
