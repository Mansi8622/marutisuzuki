<?php

namespace App\Http\Requests;

use App\Models\TermCondition;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreTermConditionRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('term_condition_create');
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
                'date_format:' . config('panel.date_format'),
                'nullable',
            ],
            'version_number' => [
                'string',
                'nullable',
            ],
            'banner_title' => [
                'string',
                'nullable',
            ],
            'banner_subtitle' => [
                'string',
                'nullable',
            ],
            'status' => [
                'required',
            ],
        ];
    }
}
