<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

Class MoveRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nodeId' => 'required|ulid',
            'targetId' => 'required|ulid'
        ];
    }
}
