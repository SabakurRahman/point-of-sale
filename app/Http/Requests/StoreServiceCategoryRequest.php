<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceCategoryRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'status' => 'required', // Assuming status can only be 'active' or 'inactive'
          // Assuming duration should be a non-negative number
//            'store_id' => 'required|exists:stores,id', // Assuming store_id should exist in the 'stores' table's 'id' column
            // Add other fields you want to validate
        ];
    }
}
