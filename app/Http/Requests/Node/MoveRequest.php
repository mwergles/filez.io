<?php

namespace App\Http\Requests\Node;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

Class MoveRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'nodeId' => 'required|ulid|exists:nodes,id,user_id,' . $this->user()->id,
            'targetId' => 'required|ulid'
        ];
    }
}
