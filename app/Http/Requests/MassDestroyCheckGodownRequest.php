<?php

namespace App\Http\Requests;

use App\Models\CheckGodown;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyCheckGodownRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('check_godown_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:check_godowns,id',
        ];
    }
}
