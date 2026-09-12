<?php

namespace App\Http\Requests;

use App\Models\CheckGodown;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreCheckGodownRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('check_godown_create');
    }

    public function rules()
    {
        return [
            'select_product_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
