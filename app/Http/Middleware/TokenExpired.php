<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TokenExpired
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guard('api')->check()) {
            $token = Auth::guard('api')->user()->token();

            if (Carbon::now()->greaterThan($token->expires_at)) {
                $token->revoke();
                return response()->json(['message' => 'Token expired. Please log in again.'], 401);
            }
        }

        return $next($request);
    }
}
