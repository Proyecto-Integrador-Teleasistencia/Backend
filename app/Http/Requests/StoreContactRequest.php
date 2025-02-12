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
            'patient_id' => 'required|exists:patients,id',
            'name' => 'required|string|max:255',
            'relationship' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'availability' => 'required|string',
            'has_keys' => 'required|boolean',
            'priority_level' => 'required|integer|min:1|max:5',
        ];
    }

    public function messages()
    {
        return [
            'patient_id.required' => 'El paciente es obligatorio',
            'patient_id.exists' => 'El paciente seleccionado no existe',
            'name.required' => 'El nombre es obligatorio',
            'name.max' => 'El nombre no puede tener más de 255 caracteres',
            'relationship.required' => 'La relación es obligatoria',
            'relationship.max' => 'La relación no puede tener más de 100 caracteres',
            'phone.required' => 'El teléfono es obligatorio',
            'phone.max' => 'El teléfono no puede tener más de 20 caracteres',
            'address.required' => 'La dirección es obligatoria',
            'availability.required' => 'La disponibilidad es obligatoria',
            'has_keys.required' => 'Debe especificar si tiene llaves',
            'priority_level.required' => 'El nivel de prioridad es obligatorio',
            'priority_level.min' => 'El nivel de prioridad debe ser al menos 1',
            'priority_level.max' => 'El nivel de prioridad no puede ser mayor que 5',
        ];
    }
}
