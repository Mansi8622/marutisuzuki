<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MassDestroyCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Adjust based on user permissions or authorization checks
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'ids' => 'required|array', // Ensure 'ids' is an array
            'ids.*' => 'exists:customers,id', // Ensure each ID exists in the customers table
        ];
    }

    /**
     * Get the custom attributes for the validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'ids' => 'customer IDs', // Custom attribute name
        ];
    }
}
