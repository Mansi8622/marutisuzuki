<?php

namespace App\Http\Requests;

use App\Models\User;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('user_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'email' => [
                'required',
                'unique:users,email,' . request()->route('user')->id,
            ],
            'phone' => [
                'string',
                'min:10',
                'max:10',
                'required',
                'unique:users,phone,' . request()->route('user')->id,
            ],
            'business_name' => [
                'string',
                'required',
            ],
            'business_type' => [
                'required',
            ],
            'gst_number' => [
                'string',
                'min:15',
                'max:15',
                'nullable',
            ],
            'pan_number' => [
                'string',
                'nullable',
            ],
            'business_address' => [
                'required',
            ],
            'bank_name' => [
                'string',
                'nullable',
            ],
            'account_number' => [
                'string',
                'nullable',
            ],
            'ifsc_code' => [
                'string',
                'nullable',
            ],
            'account_holder_name' => [
                'string',
                'nullable',
            ],
            'kyc_documents_front' => [
                'array',
            ],
            'business_registration_certificate' => [
                'array',
            ],
            'license_details' => [
                'string',
                'nullable',
            ],
            'status' => [
                'required',
            ],
            'vendor' => [
                'string',
                'required',
                'unique:users,vendor,' . request()->route('user')->id,
            ],
            'roles.*' => [
                'integer',
            ],
            'roles' => [
                'required',
                'array',
            ],
        ];
    }
}
