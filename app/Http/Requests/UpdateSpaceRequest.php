<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSpaceRequest extends FormRequest
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
            'name' => 'sometimes|required|string',
            'location' => 'sometimes|required|string',
            'space_type_id' => 'sometimes|required|exists:space_types,id',
            'price_per_hour' => 'sometimes|required|numeric|min:0',
            'capacity' => 'sometimes|required|integer|min:1',
            'description' => 'sometimes|required|string',
            'status' => 'sometimes|required|string|in:Active,Maintenance',
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
