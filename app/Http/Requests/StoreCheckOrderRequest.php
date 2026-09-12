<?php

namespace App\Http\Requests;

use App\Models\CheckOrder;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreCheckOrderRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('check_order_create');
    }

    public function rules()
    {
        return [
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
        ];
    }
}
