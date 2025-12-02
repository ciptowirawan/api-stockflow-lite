<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $userId = $this->route('user') ?? null;

        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'password' => $this->isMethod('post')
                ? 'required|string|min:8|max:255'
                : 'sometimes|string|min:8|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama user wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah digunakan.',
            'password.required' => 'Password wajib diisi saat membuat user.',
        ];
    }
}
