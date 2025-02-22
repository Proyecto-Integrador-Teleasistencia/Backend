<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        Log::info('AdminMiddleware ejecutado', ['user' => $user]);
    
        if (!$user || $user->role !== 'admin') {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'No autorizado. Solo administradores.'], 403);
            }
            return redirect()->route('dashboard')->with('error', 'No tienes permisos de administrador.');
        }
        return $next($request);
    }
}
