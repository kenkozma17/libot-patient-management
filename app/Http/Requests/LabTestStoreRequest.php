<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LabTestStoreRequest extends FormRequest
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
            'name' => 'required|string|max:100|unique:lab_tests',
            'category_id' => 'required',
            'price' => 'required',
            // 'senior_price' => 'required'
        ];

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $itemId = $this->route('lab_test');
            $rules['name'] = 'required|string|max:255|unique:lab_tests,name,' . $itemId;
        }

        return $rules;
    }
}
