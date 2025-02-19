<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContactRequest extends FormRequest
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
            'apellido' => 'required|string|max:100',
            'telefono' => 'required|string|max:20',
            'relacion' => 'required|string|max:50',
            'direccion' => 'required|string',
            'nivel_prioridad' => 'required|integer|min:1|max:5',
            'disponibilidad' => 'required|string',
            'tiene_llaves' => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'paciente_id.required' => 'El paciente es obligatorio',
            'paciente_id.exists' => 'El paciente seleccionado no existe',
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres',
            'apellido.required' => 'El apellido es obligatorio',
            'apellido.max' => 'El apellido no puede tener más de 100 caracteres',
            'relacion.required' => 'La relación es obligatoria',
            'relacion.max' => 'La relación no puede tener más de 50 caracteres',
            'telefono.required' => 'El teléfono es obligatorio',
            'telefono.max' => 'El teléfono no puede tener más de 20 caracteres',
            'direccion.required' => 'La dirección es obligatoria',
            'nivel_prioridad.required' => 'El nivel de prioridad es obligatorio',
            'nivel_prioridad.min' => 'El nivel de prioridad debe ser al menos 1',
            'nivel_prioridad.max' => 'El nivel de prioridad no puede ser mayor que 5',
            'disponibilidad.required' => 'La disponibilidad es obligatoria',
            'tiene_llaves.required' => 'Debe especificar si tiene llaves',
        ];
    }
}
