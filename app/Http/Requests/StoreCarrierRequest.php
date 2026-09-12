<?php

namespace App\Http\Requests;

use App\Models\Carrier;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreCarrierRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('carrier_create');
    }

    public function rules()
    {
        return [
            'carrier_name' => [
                'string',
                'required',
            ],
            'status' => [
                'required',
            ],
            'tracking_url' => [
                'string',
                'required',
                'unique:carriers',
            ],
            'phone' => [
                'string',
                'min:10',
                'max:10',
                'required',
                'unique:carriers',
            ],
            'email' => [
                'required',
            ],
            'brand_logo' => [
                'array',
            ],
        ];
    }
}
