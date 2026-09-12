<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSpaceRequest extends FormRequest
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
            'name' => 'required|string',
            'location' => 'required|string',
            'space_type_id' => 'required|exists:space_types,id',
            'price_per_hour' => 'required|numeric|min:0',
            'capacity' => 'required|integer|min:1',
            'description' => 'required|string',
            'status' => 'nullable|string|in:Active,Maintenance',
            'images' => 'nullable|array',
            'wifi' => 'nullable|boolean',
            'whiteboard' => 'nullable|boolean',
            'ac' => 'nullable|boolean',
            'soundproofing' => 'nullable|boolean',
            'natural_light' => 'nullable|boolean',
            'refreshments' => 'nullable|boolean',
        ];
    }
}
