<?php

namespace App\Http\Requests;

use App\Models\Llamada;
use App\Models\Paciente;
use Illuminate\Foundation\Http\FormRequest;

class StoreCallRequest extends FormRequest
{
    public function authorize()
    {
        // Verificar si el usuario puede crear llamadas
        if (!$this->user()->can('create', Llamada::class)) {
            return false;
        }

        // Si es una llamada saliente, verificar permisos adicionales
        if ($this->input('tipo_llamada') === 'saliente') {
            $patient = Paciente::findOrFail($this->input('paciente_id'));
            return $this->user()->can('makeOutgoingCall', $patient);
        }

        return true;
    }

    public function rules()
    {
        return [
            'fecha_hora' => 'required|date_format:Y-m-d H:i:s',
            'tipo_llamada' => 'required|string|in:entrante,saliente',
            'duracion' => 'nullable|integer',
            'descripcion' => 'nullable|string',
            'operador_id' => 'required|exists:users,id',
            'paciente_id' => 'required|exists:pacientes,id',
            'categoria_id' => 'required|exists:categorias,id',
            'subcategoria_id' => 'nullable|exists:subcategorias,id',
        ];
    }

    public function messages()
    {
        return [
            'fecha_hora.required' => 'La fecha y hora son obligatorias',
            'fecha_hora.date_format' => 'El formato de fecha y hora no es válido',
            'tipo_llamada.required' => 'El tipo de llamada es obligatorio',
            'tipo_llamada.in' => 'El tipo de llamada debe ser outgoing o incoming',
            'duracion.integer' => 'La duración debe ser un número entero',
            'operador_id.required' => 'El operador es obligatorio',
            'operador_id.exists' => 'El operador seleccionado no existe',
            'paciente_id.required' => 'El paciente es obligatorio',
            'paciente_id.exists' => 'El paciente seleccionado no existe',
            'categoria_id.required' => 'La categoría es obligatoria',
            'categoria_id.exists' => 'La categoría seleccionada no existe',
            'subcategoria_id.exists' => 'La subcategoría seleccionada no existe',
        ];
    }
}
