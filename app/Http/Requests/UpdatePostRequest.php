<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
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
            'post_category_id'   => 'required|integer|exists:post_categories,id',
            'title'              => 'required',
            'description'        =>'required',
            'slug'               => 'required',
            'meta_title'         =>'required',
            'meta_keyword'       =>'required',
            'meta_description'   =>'required'
        ];
    }
}
