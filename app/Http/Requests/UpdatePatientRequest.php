<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePatientRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nombre' => 'required|string|max:100',
            'fecha_nacimiento' => 'required|date',
            'direccion' => 'required|string',
            'ciudad' => 'nullable|string',
            'codigo_postal' => 'nullable|string|max:10',
            'dni' => [
                'required',
                'string',
                Rule::unique('pacientes')->ignore($this->route('paciente')),
                'max:20'
            ],
            'tarjeta_sanitaria' => [
                'required',
                'string',
                Rule::unique('pacientes')->ignore($this->route('paciente')),
                'max:50'
            ],
            'telefono' => 'required|string|max:20',
            'email' => [
                'nullable',
                'string',
                'email',
                'max:100',
                Rule::unique('pacientes')->ignore($this->route('paciente'))
            ],
            'zona_id' => [
                'required',
                'exists:zonas,id',
                function ($attribute, $value, $fail) {
                    if ($value != auth()->user()->zona_id) {
                        $fail('La zona del paciente debe ser la misma que la del usuario que lo crea.');
                    }
                },
            ],
            'situacion_personal' => 'nullable|string',
            'estado_salud' => 'nullable|string',
            'condicion_vivienda' => 'nullable|string',
            'nivel_autonomia' => 'nullable|string',
            'situacion_economica' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'nombre.required' => 'El nombre es obligatorio',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria',
            'fecha_nacimiento.date' => 'La fecha de nacimiento debe ser una fecha válida',
            'direccion.required' => 'La dirección es obligatoria',
            'dni.required' => 'El DNI es obligatorio',
            'dni.unique' => 'Este DNI ya está registrado',
            'tarjeta_sanitaria.required' => 'La tarjeta sanitaria es obligatoria',
            'tarjeta_sanitaria.unique' => 'Esta tarjeta sanitaria ya está registrada',
            'telefono.required' => 'El teléfono es obligatorio',
            'telefono.max' => 'El teléfono no puede tener más de 20 caracteres',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'El correo electrónico debe ser válido',
            'email.max' => 'El correo electrónico no puede tener más de 100 caracteres',
            'zona_id.required' => 'La zona es obligatoria',
            'zona_id.exists' => 'La zona seleccionada no existe',
            'zona_id.same' => 'La zona del paciente no puede ser la misma que la del usuario que lo crea.',
            'situacion_personal' => 'nullable|string',
            'estado_salud' => 'nullable|string',
            'condicion_vivienda' => 'nullable|string',
            'nivel_autonomia' => 'nullable|string',
            'situacion_economica' => 'nullable|string',
        ];
    }
}
