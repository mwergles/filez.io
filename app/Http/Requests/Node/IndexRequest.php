<?php

namespace App\Http\Requests\Node;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

Class IndexRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'parentId' => 'nullable|ulid|exists:nodes,id,user_id,' . $this->user()->id,
        ];
    }

    public function validationData(): array
    {
        return array_merge($this->request->all(), [
            'id' => Route::input('id'),
        ]);
    }
}
