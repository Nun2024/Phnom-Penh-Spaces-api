<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
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
            'space_id' => 'required|exists:spaces,id',
            'client_name' => 'required|string',
            'client_email' => 'required|email',
            'client_phone' => 'required|string',
            'booking_date' => 'required|date',
            'start_time' => 'required|string',
            'end_time' => 'required|string',
            'selected_slots' => 'required|array',
            'total_price' => 'required|numeric|min:0',
            'service_fee' => 'nullable|numeric|min:0',
            'payment_status' => 'nullable|string|in:unpaid,partial,paid',
            'staff_notes' => 'nullable|string',
        ];
    }
}
