<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientVisitStoreRequest extends FormRequest
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
        $rules = [
            'patient_id' => 'required',
            // 'invoice_id' => 'required',
            // 'diagnosis' => 'required',
            'requesting_physician' => 'string',
            'visit_date' => 'required',
            'patient_age' => 'required|numeric',
            'patient_status' => 'required|string',
        ];

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $itemId = $this->route('patient-visits');
            $rules['name'] = 'required|string|max:255|unique:inventory_items,name,' . $itemId;
        }

        return $rules;
    }
}
