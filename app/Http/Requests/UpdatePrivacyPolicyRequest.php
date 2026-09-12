<?php

namespace App\Http\Requests;

use App\Models\PrivacyPolicy;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdatePrivacyPolicyRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('privacy_policy_edit');
    }

    public function rules()
    {
        return [
            'title' => [
                'string',
                'required',
            ],
            'content' => [
                'required',
            ],
            'effective_date' => [
                'required',
                'date_format:' . config('panel.date_format'),
            ],
            'version_number' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'banner_image' => [
                'array',
                'required',
            ],
            'banner_image.*' => [
                'required',
            ],
            'banner_title' => [
                'string',
                'nullable',
            ],
            'banner_subtitle' => [
                'string',
                'nullable',
            ],
        ];
    }
}
