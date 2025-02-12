<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCallRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'datetime' => 'sometimes|required|date',
            'description' => 'nullable|string',
            'type' => 'sometimes|required|string|in:outgoing,incoming',
            'scheduled' => 'sometimes|required|boolean',
            'operator_id' => 'sometimes|required|exists:users,id',
            'patient_id' => 'sometimes|required|exists:patients,id',
            'category_id' => 'sometimes|required|exists:categories,id',
            'alert_id' => 'nullable|exists:alerts,id',
        ];
    }

    public function messages()
    {
        return [
            'datetime.required' => 'La fecha y hora son obligatorias',
            'datetime.date' => 'El formato de fecha y hora no es válido',
            'type.required' => 'El tipo de llamada es obligatorio',
            'type.in' => 'El tipo de llamada debe ser outgoing o incoming',
            'scheduled.required' => 'Debe especificar si la llamada está programada',
            'scheduled.boolean' => 'El campo programada debe ser verdadero o falso',
            'operator_id.required' => 'El operador es obligatorio',
            'operator_id.exists' => 'El operador seleccionado no existe',
            'patient_id.required' => 'El paciente es obligatorio',
            'patient_id.exists' => 'El paciente seleccionado no existe',
            'category_id.required' => 'La categoría es obligatoria',
            'category_id.exists' => 'La categoría seleccionada no existe',
            'alert_id.exists' => 'La alerta seleccionada no existe',
        ];
    }
}
