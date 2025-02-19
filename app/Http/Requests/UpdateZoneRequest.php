<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateZoneRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nombre' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('zonas')->ignore($this->zona)],
            'codigo' => ['sometimes', 'required', 'string', 'max:50', Rule::unique('zonas')->ignore($this->zona)],
            'activa' => 'sometimes|boolean',
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
