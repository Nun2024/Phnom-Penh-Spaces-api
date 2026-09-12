<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingRequest extends FormRequest
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
            'payment_status' => 'sometimes|required|string|in:unpaid,partial,paid',
            'staff_notes' => 'nullable|string',
            'client_name' => 'sometimes|required|string',
            'client_email' => 'sometimes|required|email',
            'client_phone' => 'sometimes|required|string',
        ];
    }
}
