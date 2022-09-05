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
            'vk_group_link' => 'Укажите ссылку на группу',
        ];
    }

    public function rules(): array
    {
        return [
            'vk_group_link' => 'required|string'
        ];
    }
}
