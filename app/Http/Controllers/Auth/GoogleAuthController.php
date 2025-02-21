<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;

class GoogleAuthController extends Controller
{
    // Redirige a Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    // Manejar la respuesta de Google
    public function handleGoogleCallback(Request $request)
    {
        try {
            if (!$request->token) {
                return response()->json(['error' => 'Token no proporcionado'], 400);
            }

            $googleUser = Socialite::driver('google')
                ->stateless()
                ->userFromToken($request->token);

            // Buscar si existe el usuario con ese email
            $user = User::where('email', $googleUser->getEmail())->first();

            // Si no existe el usuario, devolver error
            if (!$user) {
                return response()->json([
                    'error' => 'Unauthorized. Only existing users can login with Google.'
                ], 403);
            }

            // Actualizar el google_id si no lo tiene
            if (empty($user->google_id)) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'name' => $googleUser->getName() // Actualizamos el nombre por si ha cambiado en Google
                ]);
            }

            // Crear token con Sanctum
            $token = $user->createToken('google-token')->plainTextToken;

            return response()->json([
                'token' => $token,
                'user' => $user,
                'message' => 'Successfully logged in with Google'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Authentication failed: ' . $e->getMessage()
            ], 500);
        }
    }

    // Cerrar sesión
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

    // Obtener información del usuario autenticado
    public function getUser(Request $request)
    {
        return response()->json($request->user());
    }
}
