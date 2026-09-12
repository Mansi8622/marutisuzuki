<?php

namespace App\Http\Requests;

use App\Models\CheckOrder;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyCheckOrderRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('check_order_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:check_orders,id',
        ];
    }
}
