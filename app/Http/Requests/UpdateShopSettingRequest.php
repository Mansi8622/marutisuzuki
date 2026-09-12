<?php

namespace App\Http\Requests;

use App\Models\ShopSetting;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateShopSettingRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('shop_setting_edit');
    }

    public function rules()
    {
        return [
            'shop_name' => [
                'string',
                'required',
            ],
            'legal_name' => [
                'string',
                'required',
            ],
            'email' => [
                'required',
            ],
            'logo' => [
                'array',
            ],
        ];
    }
}
