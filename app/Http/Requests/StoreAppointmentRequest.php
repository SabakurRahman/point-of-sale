<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
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
            //
                 'category_id' => 'required|integer|exists:categories,id',
                 'product_id' =>'required|integer',
                 'date_time' => 'required|date',
                 'name' => 'required|string|max:255',
                 'email' => 'nullable|email|max:255',
                 'phone' => 'required|string|digits:11',

             ];

    }
}
