<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'paciente_id' => 'required|exists:pacientes,id',
            'nombre' => 'required|string|max:100',
            'telefono' => 'required|string|max:20',
            'relacion' => 'required|string|max:50',
        ];
    }

    public function messages()
    {
        return [
            'paciente_id.required' => 'El paciente es obligatorio',
            'paciente_id.exists' => 'El paciente seleccionado no existe',
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres',
            'relacion.required' => 'La relación es obligatoria',
            'relacion.max' => 'La relación no puede tener más de 50 caracteres',
            'telefono.required' => 'El teléfono es obligatorio',
            'telefono.max' => 'El teléfono no puede tener más de 20 caracteres',
        ];
    }
}
