<?php

namespace App\Http\Requests\Node;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;

Class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => 'required|ulid|exists:nodes,id,user_id,' . $this->user()->id,
            'name' => 'required|string|max:80',
        ];
    }

    public function validationData(): array
    {
        return array_merge($this->request->all(), [
            'id' => Route::input('id'),
        ]);
    }
}
