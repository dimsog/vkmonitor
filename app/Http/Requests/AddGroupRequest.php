<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddGroupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function messages(): array
    {
        return [
            'vk_group_id.required' => 'Укажите идентификатор группы',
            'vk_group_id.integer' => 'Идентификатор группы должен быть числом',
            'vk_client_id.required' => 'Укажите ID приложения',
            'vk_client_id.integer' => 'ID приложения не является числом',
            'vk_access_token.required' => 'Укажите access token',
            'vk_access_token.string' => 'Access token не является строкой',
        ];
    }

    public function rules(): array
    {
        return [
            'vk_group_id' => 'required|integer',
            'vk_client_id' => 'required|integer',
            'vk_access_token' => 'required|string'
        ];
    }
}
