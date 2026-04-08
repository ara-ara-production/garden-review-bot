<?php

namespace App\Http\Requests;

use App\Enums\UserRoleEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BotInviteRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'driver' => ['required', Rule::in(['telegram', 'vk'])],
            'bot' => ['required', 'string', 'max:255'],
            'user_id' => ['nullable', 'string'],
            'role' => ['required', Rule::in(UserRoleEnum::toArray()->pluck('name')->all())],
            'brunch_id' => ['nullable', 'integer', 'exists:brunches,id'],
            'assignment' => ['nullable', Rule::in(['user_id', 'pupr_user_id'])],
            'name_hint' => ['nullable', 'string', 'max:255'],
            'max_uses' => ['required', 'integer', 'min:1', 'max:100'],
            'expires_at' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'driver.required' => 'Нужно выбрать платформу бота.',
            'bot.required' => 'Нужно выбрать конкретного бота.',
            'user_id.string' => 'Неверное значение пользователя.',
            'role.required' => 'Нужно выбрать роль.',
            'brunch_id.exists' => 'Выбранный филиал не найден.',
            'assignment.in' => 'Неверный тип назначения в филиал.',
            'max_uses.required' => 'Нужно указать количество активаций.',
            'max_uses.integer' => 'Количество активаций должно быть числом.',
            'max_uses.min' => 'Количество активаций должно быть не меньше 1.',
            'expires_at.date' => 'Дата истечения должна быть корректной.',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
