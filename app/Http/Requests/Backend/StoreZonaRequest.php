<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class StoreZonaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->is_admin;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:10|unique:zonas,codigo',
            'activa' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres',
            'codigo.required' => 'El código es obligatorio',
            'codigo.max' => 'El código no puede tener más de 10 caracteres',
            'codigo.unique' => 'El código ya está en uso',
            'activa.boolean' => 'El estado debe ser activo o inactivo',
        ];
    }
}
