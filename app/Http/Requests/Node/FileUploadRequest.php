<?php

namespace App\Http\Requests\Node;

use Illuminate\Foundation\Http\FormRequest;

Class FileUploadRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'targetId' => 'nullable|ulid|exists:nodes,id,user_id,' . $this->user()->id,
            'file' => 'required|file'
        ];
    }
}
