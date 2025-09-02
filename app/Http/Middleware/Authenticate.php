<?php

namespace App\Http\Middleware;

use Illuminate\Routing\Controllers\Middleware;

class Authenticate extends Middleware
{
    protected function redirectTo($request)
    {
        if ($request->is('api/*')) {
            abort(response()->json(['message' => 'Unauthenticated.'], 401));
        }

        return route('login');
    }

}
