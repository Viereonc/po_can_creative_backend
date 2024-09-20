<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone_number' => 'nullable|string|max:15',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'role' => 'required|string|in:admin,sopir,user',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required.',
            'name.string' => 'Name must be a valid string.',
            'name.max' => 'Name must not be greater than 255 characters.',

            'email.required' => 'Email is required.',
            'email.string' => 'Email must be a valid string.',
            'email.email' => 'Email must be a valid email address.',
            'email.unique' => 'Email has already been taken.',
            'email.max' => 'Email must not be greater than 255 characters.',

            'password.required' => 'Password is required.',
            'password.confirmed' => 'Passwords do not match.',
            'password.string' => 'Password must be a valid string.',
            'password.min' => 'Password must be at least 8 characters.',

            'phone_number.required' => 'Phone number is required.',
            'photo.image' => 'Profile photo must be an image.',
            'photo.mimes' => 'Profile photo must be of type: jpeg, png, jpg, gif.',
            'photo.max' => 'Profile photo must not be larger than 2MB.',

            'role.required' => 'Role is required.',
            'role.string' => 'Role must be a valid string.',
            'role.in' => 'Role must be one of: admin, driver, passenger.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Name',
            'email' => 'Email Address',
            'password' => 'Password',
            'phone_number' => 'Phone Number',
            'photo' => 'Profile Photo',
            'role' => 'Role'
        ];
    }
}
