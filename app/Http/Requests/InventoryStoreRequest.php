<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InventoryStoreRequest extends FormRequest
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
            'name' => 'required|string|max:100|unique:inventory_items',
            'category_id' => 'required',
            'unit' => 'required',
            'days_before_expiration_limit' => 'numeric',
            'low_stock_limit' => 'numeric',
        ];

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $itemId = $this->route('inventory');
            $rules['name'] = 'required|string|max:255|unique:inventory_items,name,' . $itemId;
        }

        return $rules;
    }
}
