<?php

namespace App\Http\Requests;

use App\Enums\UserRoleEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users,email'.($this->user ? ",{$this->user?->id}" : ''),
            'password' => 'nullable|string|min:8|confirmed',
            'telegram_username' => 'nullable|string|max:255',
            'vk_user_id' => 'nullable|string|max:255',
            'role' => ['nullable', Rule::in(UserRoleEnum::toArray()->pluck('name'))],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Поле имени обязательно для заполнения.',
            'name.string' => 'Имя должно быть строкой.',
            'name.max' => 'Имя не должно превышать 255 символов.',

            'email.string' => 'Почта должна быть строкой.',
            'email.email' => 'Почта должна быть действительным email адресом.',
            'email.max' => 'Почта не должна превышать 255 символов.',
            'email.unique' => 'Пользователь с такой почтой уже существует.',

            'password.string' => 'Пароль должен быть строкой.',
            'password.min' => 'Пароль должен содержать минимум 8 символов.',
            'password.confirmed' => 'Пароль и его подтверждение не совпадают.',

            'telegram_username.string' => 'Telegram username должен быть строкой.',
            'telegram_username.max' => 'Telegram username не должен превышать 255 символов.',
            'vk_user_id.string' => 'VK user id должен быть строкой.',
            'vk_user_id.max' => 'VK user id не должен превышать 255 символов.',
        ];

    }

    public function authorize(): bool
    {
        return true;
    }
}
