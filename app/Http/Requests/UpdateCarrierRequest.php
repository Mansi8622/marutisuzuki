<?php

namespace App\Http\Requests;

use App\Models\Carrier;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateCarrierRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('carrier_edit');
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
                'unique:carriers,tracking_url,' . request()->route('carrier')->id,
            ],
            'phone' => [
                'string',
                'min:10',
                'max:10',
                'required',
                'unique:carriers,phone,' . request()->route('carrier')->id,
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
