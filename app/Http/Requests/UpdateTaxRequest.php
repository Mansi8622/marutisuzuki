<?php

namespace App\Http\Requests;

use App\Models\Tax;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateTaxRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('tax_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'tax_rate' => [
                'string',
                'required',
            ],
            'status' => [
                'required',
            ],
        ];
    }
}
