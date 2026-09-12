<?php

namespace App\Http\Requests;

use App\Models\Dispute;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateDisputeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('dispute_edit');
    }

    public function rules()
    {
        return [];
    }
}
