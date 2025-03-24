<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientLoanStoreRequest extends FormRequest
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
            'amount' => 'required|numeric',
            'interest_rate' => 'required|numeric',
            'purpose' => 'nullable|string',
            'duration_months' => 'required|numeric',
            'start_date' => 'required',
            'end_date' => 'required',
            'date_released' => 'required',
            'check_no' => 'nullable|string',
            'service_fee' => 'required|string',
            'capital_build_up' => 'required|string',
            'net_amount_released' => 'required|numeric',
            'patient_id' => 'required'
        ];
    }
}
