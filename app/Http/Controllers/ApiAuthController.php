<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiAuthRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiAuthController extends Controller
{
    public function apiLogin(Request $request)
    {
        if (Auth::attempt($request->only(['email', 'password']))) {
            return Auth::user()->createToken('authToken')->plainTextToken;
        }
    }
}
