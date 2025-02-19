<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreZoneRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nombre' => 'required|string|max:255|unique:zonas',
            'codigo' => 'required|string|max:50|unique:zonas',
            'activa' => 'boolean',
            'estado_texto' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.max' => 'El nombre no puede tener más de 255 caracteres',
            'nombre.unique' => 'Ya existe una zona con este nombre',
            'codigo.required' => 'El código es obligatorio',
            'codigo.max' => 'El código no puede tener más de 50 caracteres',
            'codigo.unique' => 'Ya existe una zona con este código',
            'activa.boolean' => 'El campo activo debe ser verdadero o falso',
        ];
    }
}
