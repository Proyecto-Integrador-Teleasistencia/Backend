<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class UpdateOperatorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->operator);
    }

    public function rules(): array
    {
        $rules = [
            'nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $this->operator->id,
            'telefono' => 'nullable|string|max:20',
            'zona_id' => 'nullable|exists:zonas,id',
            'password' => 'nullable|string|min:8|confirmed',
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio',
            'email.required' => 'El email es obligatorio',
            'email.email' => 'El email debe ser válido',
            'email.unique' => 'Este email ya está registrado',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'zona_id.exists' => 'La zona seleccionada no existe',
            'role.required' => 'El rol es obligatorio',
            'role.in' => 'El rol debe ser operador o administrador'
        ];
    }
}

