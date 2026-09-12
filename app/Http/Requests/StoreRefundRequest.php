<?php

namespace App\Http\Requests;

use App\Models\Refund;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreRefundRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('refund_create');
    }

    public function rules()
    {
        return [
            'status' => [
                'required',
            ],
            'attachment' => [
                'array',
            ],
        ];
    }
}
