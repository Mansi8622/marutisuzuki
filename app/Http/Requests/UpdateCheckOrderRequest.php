<?php

namespace App\Http\Requests;

use App\Models\CheckOrder;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateCheckOrderRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('check_order_edit');
    }

    public function rules()
    {
        return [
            'select_products.*' => [
                'integer',
            ],
            'select_products' => [
                'array',
            ],
            'placed_at' => [
                'required',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
            ],
            'order_status' => [
                'required',
            ],
            'attachment' => [
                'array',
            ],
            'confirm_qty' => [
                'required',
            ],
            'carrier_id' =>[
                'required',
            ]
        ];
    }
}
