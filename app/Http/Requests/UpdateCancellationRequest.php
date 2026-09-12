<?php

namespace App\Http\Requests;

use App\Models\Cancellation;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateCancellationRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('cancellation_edit');
    }

    public function rules()
    {
        return [];
    }
}
