<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'destination_id' => 'required|integer|exists:destinations,destination_id',
            'seat_number' => 'required|integer|min:1',
            'price' => 'required|integer|min:1000',
        ];
    }

    public function attributes(): array
    {
        return [
            'user_id' => 'User',
            'destination_id' => 'Destination',
            'seat_number' => 'Seat Number',
            'price' => 'Price',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.exists' => 'The selected user ID does not exist.',
            'destination_id.exists' => 'The selected destination ID does not exist.',
            'seat_number.min' => 'The seat number must be at least 1.',
            'price.min' => 'The price must be at least 1000.',
        ];
    }
}
