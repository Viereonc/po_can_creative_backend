<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DestinationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bus_id' => 'required|integer|exists:buses,bus_id',
            'start_location' => 'required|string|max:155',
            'end_location' => 'required|string|max:155',
            'departure_time' => 'required|date_format:Y-m-d H:i:s|date|after_or_equal:now',
        ];
    }

    public function messages(): array
    {
        return [
            'bus_id.required' => 'Bus ID is required.',
            'bus_id.integer' => 'Bus ID must be an integer.',
            'bus_id.exists' => 'The selected bus ID does not exist.',

            'start_location.required' => 'Start location is required.',
            'start_location.string' => 'Start location must be a string.',
            'start_location.max' => 'Start location must not exceed 155 characters.',

            'end_location.required' => 'End location is required.',
            'end_location.string' => 'End location must be a string.',
            'end_location.max' => 'End location must not exceed 155 characters.',

            'departure_time.required' => 'Departure time is required.',
            'departure_time.date_format' => 'Departure time must be in the format Y-m-d H:i:s.',
            'departure_time.date' => 'Departure time must be a valid date.',
            'departure_time.after_or_equal' => 'Departure time must be equal to or after the current time.',
        ];
    }

    public function attributes(): array
    {
        return [
            'bus_id' => 'Bus',
            'start_location' => 'Start Location',
            'end_location' => 'End Location',
            'departure_time' => 'Departure Time',
        ];
    }
}
