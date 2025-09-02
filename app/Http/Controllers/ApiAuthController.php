<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiAuthController extends Controller
{
    public function apiLogin(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (Auth::attempt($credentials)) {
            return response([
                'token' => Auth::user()->createToken('authToken')->plainTextToken
            ]);
        } else {
            $messages = null;

            if (!key_exists('email', $credentials)) {
                $messages[] = 'Почта не получена';
            }

            if (!key_exists('password', $credentials)) {
                $messages[] = 'Пароль не получен';
            }

            return response([
                'error' => 'Unauthorized',
                'messages' => $messages ?? 'Пароль и/или почта не найдены!'
            ], 422);
        }
    }
}
