<?php

namespace App\Http\Requests;

use App\Models\TermCondition;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyTermConditionRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('term_condition_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:term_conditions,id',
        ];
    }
}
