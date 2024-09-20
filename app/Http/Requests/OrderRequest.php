<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'ticket_id' => 'required|integer|exists:tickets,ticket_id',
            'total_price' => 'required|integer|min:1000',
            'status' => 'required|in:pending,success,cancel',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'The user field is required.',
            'user_id.integer' => 'The user field must be an integer.',
            'user_id.exists' => 'The selected user does not exist.',

            'ticket_id.required' => 'The ticket field is required.',
            'ticket_id.integer' => 'The ticket field must be an integer.',
            'ticket_id.exists' => 'The selected ticket does not exist.',

            'total_price.required' => 'The total price is required.',
            'total_price.integer' => 'The total price must be a valid number.',
            'total_price.min' => 'The total price must be at least 1000.',

            'status.required' => 'The status field is required.',
            'status.in' => 'The status must be one of the following: pending, success, or cancel.',
        ];
    }

    public function attributes(): array
    {
        return [
            'user_id' => 'User',
            'ticket_id' => 'Ticket',
            'total_price' => 'Total Price',
            'status' => 'Status',
        ];
    }
}
