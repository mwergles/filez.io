<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

Class FileUploadRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'targetId' => 'nullable|ulid',
            'file' => 'required|file'
        ];
    }
}
