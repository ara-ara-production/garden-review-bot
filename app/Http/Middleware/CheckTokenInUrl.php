<?php

namespace App\Http\Middleware;

use App\Services\ReviewService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTokenInUrl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tokenInUrl = $request->route()->parameter('token');

        $rightToken = app(ReviewService::class)->getUrlToken();

        if ($rightToken !== $tokenInUrl) {
            abort(Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
