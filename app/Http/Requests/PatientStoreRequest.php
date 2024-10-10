<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientStoreRequest extends FormRequest
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
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string',
            'middle_name' => 'nullable|string',
            'gender' => 'required|string',
            'birthdate' => 'required',
            'street_address' => 'required|string',
            'region' => 'required|string',
            'province' => 'required|string',
            'municipality' => 'required|string',
            'barangay' => 'required|string',
            'postal_code' => 'required|string',
            'phone' => 'required',
            'email' => 'nullable|email',
            'country' => 'nullable|string'
        ];
    }
}
