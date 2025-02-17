<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOperatorRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|max:20',
            'shift' => 'required|string|in:morning,afternoon,night',
            'status' => 'required|string|in:active,inactive,on_leave',
            'hire_date' => 'required|date',
            'termination_date' => 'nullable|date|after:hire_date',
            'zone_id' => 'nullable|exists:zones,id',
            'zones' => 'required|array',
            'zones.*' => 'required|exists:zones,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre es obligatorio',
            'name.max' => 'El nombre no puede tener más de 255 caracteres',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'El correo electrónico debe ser válido',
            'email.unique' => 'Este correo electrónico ya está registrado',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'phone.required' => 'El teléfono es obligatorio',
            'phone.max' => 'El teléfono no puede tener más de 20 caracteres',
            'shift.required' => 'El turno es obligatorio',
            'shift.in' => 'El turno debe ser morning, afternoon o night',
            'status.required' => 'El estado es obligatorio',
            'status.in' => 'El estado debe ser active, inactive o on_leave',
            'hire_date.required' => 'La fecha de contratación es obligatoria',
            'hire_date.date' => 'La fecha de contratación debe ser una fecha válida',
            'termination_date.date' => 'La fecha de terminación debe ser una fecha válida',
            'termination_date.after' => 'La fecha de terminación debe ser posterior a la fecha de contratación',
            'zones.required' => 'Debe asignar al menos una zona',
            'zones.array' => 'Las zonas deben ser un array',
            'zones.*.exists' => 'Una o más zonas seleccionadas no existen',
        ];
    }
}
