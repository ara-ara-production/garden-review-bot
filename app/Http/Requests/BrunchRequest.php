<?php

namespace App\Http\Requests;

use App\Enums\UserRoleEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class BrunchRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'user_id' => ['required', 'integer', Rule::exists('users', 'id')->where('role', UserRoleEnum::Control->name)],
            'pupr_user_id' => ['required', 'integer', Rule::exists('users', 'id')->where('role', UserRoleEnum::Control->name)],
            'two_gis_id' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Поле "Имя" обязательно для заполнения.',
            'name.string' => 'Поле "Имя" должно быть строкой.',
            'name.max' => 'Поле "Имя" не должно превышать 255 символов.',

            'user_id.required' => 'Поле "Пользователь" обязательно для заполнения.',
            'user_id.integer' => 'Поле "Пользователь" должно быть числом.',
            'user_id.exists' => 'Выбранный пользователь не существует или не имеет роль "Control".',

            'pupr_user_id.required' => 'Поле "Пользователь" обязательно для заполнения.',
            'pupr_user_id.integer' => 'Поле "Пользователь" должно быть числом.',
            'pupr_user_id.exists' => 'Выбранный пользователь не существует или не имеет роль "Control".',

            'two_gis_id.string' => 'Поле "2ГИС ID" должно быть строкой.',
            'two_gis_id.max' => 'Поле "2ГИС ID" не должно превышать 255 символов.',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
