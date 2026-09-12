<?php

namespace App\Http\Requests;

use App\Models\Configuration;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyConfigurationRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('configuration_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:configurations,id',
        ];
    }
}
