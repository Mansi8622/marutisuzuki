<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Adjust based on user permissions
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone' => 'required|unique:customers,phone',
            'password' => 'required|string|min:6|confirmed',
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
            'name' => 'customer name',
            'email' => 'customer email',
            'phone' => 'customer phone',
            'password' => 'customer password',
        ];
    }
}
