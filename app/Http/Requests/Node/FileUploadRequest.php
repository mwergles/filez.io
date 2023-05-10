<?php

namespace App\Http\Requests\Node;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

Class FileUploadRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'targetId' => 'nullable|ulid|exists:nodes,id,user_id,' . $this->user()->id,
            'file' => [
                'required',
                // accepted file types: plain text, pdf and microsoft office files
                File::types([
                    'txt',
                    'pdf',
                    'doc',
                    'docx',
                    'xls',
                    'xlsx',
                    'ppt',
                    'pptx',
                ]),
            ]
        ];
    }
}
