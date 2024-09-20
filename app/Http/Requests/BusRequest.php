<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bus_name' => 'required|string|max:255|unique:buses,bus_name',
            'seat_capacity' => 'required|integer|min:1|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'bus_name.required' => 'Bus name is required',
            'bus_name.string' => 'Bus name must be a string',
            'bus_name.max' => 'Bus name must not be greater than 255 characters',
            'bus_name.unique' => 'Bus name has already been taken',
            
            'seat_capacity.required' => 'Seat capacity is required',
            'seat_capacity.integer' => 'Seat capacity must be an integer',
            'seat_capacity.min' => 'Seat capacity must be at least 1',
            'seat_capacity.max' => 'Seat capacity must not be greater than 100',
        ];
    }

    public function attributes(): array
    {
        return [
            'bus_name' => 'Bus Name',
            'seat_capacity' => 'Seat Capacity',
        ];
    }
}
