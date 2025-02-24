<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\TransientToken;

class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (! $request->user() || ! $request->user()->currentAccessToken()) {
            return response()->json(['message' => 'No autenticado'], 401);
        }

        $token = $request->user()->currentAccessToken();

        if ($token instanceof TransientToken) {
            return $next($request);
        }

        if ($token->created_at->addMinutes(config('sanctum.expiration'))->isPast()) {
            $token->delete();
            return response()->json(['message' => 'Token expirado'], 401);
        }

        if (!$request->user()->is_active) {
            $token->delete();
            return response()->json(['message' => 'Usuario desactivado'], 403);
        }

        return $next($request);
    }
}
