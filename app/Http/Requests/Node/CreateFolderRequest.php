<?php

namespace App\Http\Requests\Node;

use Illuminate\Foundation\Http\FormRequest;

Class CreateFolderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:80',
            'targetId' => 'nullable|ulid|exists:nodes,id,user_id,' . $this->user()->id,
        ];
    }
}
