<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="LoginRequest",
 *     description="Esquema de validación para el inicio de sesión",
 *     required={"email", "password", "device_name"},
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         description="Correo electrónico del usuario",
 *         example="usuario@example.com"
 *     ),
 *     @OA\Property(
 *         property="password",
 *         type="string",
 *         format="password",
 *         description="Contraseña del usuario",
 *         example="MiContraseñaSegura123"
 *     ),
 *     @OA\Property(
 *         property="device_name",
 *         type="string",
 *         description="Nombre del dispositivo desde el que se realiza el inicio de sesión",
 *         example="iPhone 12"
 *     )
 * )
 */

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'El correo electrónico debe ser válido',
            'password.required' => 'La contraseña es obligatoria',
            'device_name.required' => 'El nombre del dispositivo es obligatorio',
        ];
    }
}
