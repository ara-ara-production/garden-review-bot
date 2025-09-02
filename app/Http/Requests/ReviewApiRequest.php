<?php

namespace App\Http\Requests;

use App\Enums\UserRoleEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class ReviewApiRequest extends FormRequest
{
    public function wantsJson(): bool { return true; }
    public function expectsJson(): bool { return true; }

    protected function failedValidation($validator)
    {
        throw new HttpResponseException(
            response()->json([
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422)
        );
    }

    public function rules(): array
    {
        return [
            'inner_id' => 'required|integer',
            'posted_at' => 'required|date',
            'brunch' => 'required|string|exists:brunches,address',
            'score' => 'required|integer',
            'text' => 'nullable|string',
            'sender' => 'nullable|string',
        ];
    }
}
