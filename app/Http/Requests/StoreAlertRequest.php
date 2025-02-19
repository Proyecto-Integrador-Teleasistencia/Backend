<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAlertRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => 'nullable|integer|exists:avisos,id',
            'tipo' => 'required|string|in:puntual,periodico',
            'fecha_hora' => 'required|date',
            'descripcion' => 'required|string',
            'completado' => 'required|boolean',
            'fecha_completado' => 'nullable|date',
            'categoria_id' => 'required|exists:categorias,id',
            'paciente_id' => 'required|exists:pacientes,id',
            'operador_id' => 'required|exists:users,id',
        ];
    }

    public function messages()
    {
        return [
            'id.integer' => 'El id debe ser un número',
            'id.exists' => 'El id no existe',
            'tipo.required' => 'El tipo es obligatorio',
            'tipo.in' => 'El tipo debe ser puntual o periodico',
            'fecha_hora.required' => 'La fecha y hora son obligatorias',
            'fecha_hora.date' => 'El formato de fecha y hora no es válido',
            'descripcion.required' => 'La descripción es obligatoria',
            'completado.required' => 'La completado es obligatorio',
            'completado.boolean' => 'La completado debe ser true o false',
            'fecha_completado.date' => 'El formato de fecha y hora no es válido',
            'categoria_id.required' => 'La categoría es obligatoria',
            'categoria_id.exists' => 'La categoría seleccionada no existe',
            'paciente_id.required' => 'El paciente es obligatorio',
            'paciente_id.exists' => 'El paciente seleccionado no existe',
            'operador_id.required' => 'El operador es obligatorio',
            'operador_id.exists' => 'El operador seleccionado no existe',
        ];
    }
}
