<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InventoryTransactionStoreRequest extends FormRequest
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
        $type = $this->input('transaction_type');

        if ($type === 'INCREASE') {
            $rules = [
                'lot_number' => 'required|string|max:50',
                'date_received' => 'required',
                'expiration_date' => 'nullable',
                'quantity' => 'required|string',
                'date_opened' => 'required',
                'transaction_type' => 'required',
                'notes' => 'nullable|string|max:250',
                'inventory_item_id' => 'required',
            ];
        } else {
            $rules = [
                'quantity' => 'required|string',
                'transaction_type' => 'required',
                'notes' => 'nullable|string|max:250',
                'inventory_item_id' => 'required',
                'lot_number' => 'required|string'
            ];
        }

        return $rules;
    }
}
