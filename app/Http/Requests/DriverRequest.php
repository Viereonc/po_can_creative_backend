<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DriverRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'bus_id' => 'required|integer|exists:buses,bus_id',
            'license_number' => 'required|string|max:100|unique:drivers,license_number',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'The driver field is required.',
            'user_id.integer' => 'The driver must be a valid user ID.',
            'user_id.exists' => 'The selected driver does not exist.',

            'bus_id.required' => 'The bus field is required.',
            'bus_id.integer' => 'The bus must be a valid bus ID.',
            'bus_id.exists' => 'The selected bus does not exist.',

            'license_number.required' => 'The license number is required.',
            'license_number.string' => 'The license number must be a valid string.',
            'license_number.max' => 'The license number must not exceed 100 characters.',
            'license_number.unique' => 'The license number has already been taken.',
        ];
    }

    public function attributes(): array
    {
        return [
            'user_id' => 'Driver',
            'bus_id' => 'Bus',
            'license_number' => 'License Number',
        ];
    }
}
