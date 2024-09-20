<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:15',
            'photo' => 'nullable|string',
            'email' => 'nullable|string|email|max:255|unique:users,email,' . $this->user()->id,
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'The email address is already taken. Please choose another one.',
            'email.email' => 'The email format is invalid.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Full Name',
            'phone_number' => 'Phone Number',
            'photo' => 'Profile Photo',
            'email' => 'Email Address',
        ];
    }
}
