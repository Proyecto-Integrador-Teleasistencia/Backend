<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        // Verificar si la cuenta está activa
        if (!$user->is_active) {
            throw ValidationException::withMessages([
                'email' => ['Esta cuenta ha sido desactivada.'],
            ]);
        }

        // Limitar el número de tokens activos por usuario
        if ($user->tokens()->count() >= 5) {
            $user->tokens()->orderBy('created_at')->first()->delete();
        }

        // Crear token con habilidades basadas en el rol
        $abilities = $this->getAbilitiesForRole($user->role);
        $token = $user->createToken($request->device_name, $abilities);

        return response()->json([
            'token' => $token->plainTextToken,
            'user' => new UserResource($user)
        ]);
    }

    public function logout(Request $request)
    {
        // Revocar el token actual
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }

    public function logoutAll(Request $request)
    {
        // Revocar todos los tokens del usuario
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Se han cerrado todas las sesiones']);
    }

    private function getAbilitiesForRole(string $role): array
    {
        return match ($role) {
            'admin' => ['*'], // Acceso total
            'operator' => [
                'patients:view',
                'patients:update',
                'calls:create',
                'calls:view',
                'calls:update',
                'alerts:view',
                'alerts:update',
            ],
            default => [],
        };
    }

    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user()->load('zones')
        ]);
    }
}
