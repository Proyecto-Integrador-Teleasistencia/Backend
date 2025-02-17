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
            'name' => 'sometimes|required|string|max:255',
            'birth_date' => 'sometimes|required|date',
            'address' => 'sometimes|required|string',
            'dni' => ['sometimes', 'required', 'string', Rule::unique('patients')->ignore($this->patient)],
            'health_card' => ['sometimes', 'required', 'string', Rule::unique('patients')->ignore($this->patient)],
            'phone' => 'sometimes|required|string|max:20',
            'email' => ['sometimes', 'required', 'email', Rule::unique('patients')->ignore($this->patient)],
            'zone_id' => 'sometimes|required|exists:zones,id',
            'personal_situation' => 'nullable|string',
            'health_condition' => 'nullable|string',
            'home_condition' => 'nullable|string',
            'autonomy_level' => 'nullable|string',
            'economic_situation' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre es obligatorio',
            'birth_date.required' => 'La fecha de nacimiento es obligatoria',
            'birth_date.date' => 'La fecha de nacimiento debe ser una fecha válida',
            'address.required' => 'La dirección es obligatoria',
            'dni.required' => 'El DNI es obligatorio',
            'dni.unique' => 'Este DNI ya está registrado',
            'health_card.required' => 'La tarjeta sanitaria es obligatoria',
            'health_card.unique' => 'Esta tarjeta sanitaria ya está registrada',
            'phone.required' => 'El teléfono es obligatorio',
            'phone.max' => 'El teléfono no puede tener más de 20 caracteres',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'El correo electrónico debe ser válido',
            'email.unique' => 'Este correo electrónico ya está registrado',
            'zone_id.required' => 'La zona es obligatoria',
            'zone_id.exists' => 'La zona seleccionada no existe',
        ];
    }
}
