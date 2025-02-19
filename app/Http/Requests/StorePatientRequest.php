<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePatientRequest extends FormRequest
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
            'dni' => 'required|string|unique:pacientes|max:20',
            'tarjeta_sanitaria' => 'required|string|unique:pacientes|max:50',
            'telefono' => 'required|string|max:20',
            'email' => 'nullable|string|email|max:100|unique:pacientes',
            'zona_id' => 'required|exists:zonas,id',
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
            'email.unique' => 'Este correo electrónico ya está registrado',
            'email.max' => 'El correo electrónico no puede tener más de 100 caracteres',
            'zona_id.required' => 'La zona es obligatoria',
            'zona_id.exists' => 'La zona seleccionada no existe',
        ];
    }
}
