<?php

namespace App\Http\Requests;

use App\Models\Configuration;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateConfigurationRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('configuration_edit');
    }

    public function rules()
    {
        return [
            'alert_quantity' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'order_handling' => [
                'required',
            ],
            'due_date' => [
                'required',
            ],
        ];
    }
}
