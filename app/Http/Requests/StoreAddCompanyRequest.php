<?php

namespace App\Http\Requests;

use App\Models\AddCompany;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreAddCompanyRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('add_company_create');
    }

    public function rules()
    {
        return [
            'company_name' => [
                'string',
                'required',
            ],
            'company_logo' => [
                'array',
            ],
        ];
    }
}
