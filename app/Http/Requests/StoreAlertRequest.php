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
            'periocidad' => 'required|string|in:puntual,periódico',
            'fecha_hora' => 'required|date',
            'categoria_id' => 'required|exists:categories,id',
        ];
    }

    public function messages()
    {
        return [
            'periocidad.required' => 'La periodicidad es obligatoria',
            'periocidad.in' => 'La periodicidad debe ser puntual o periódico',
            'fecha_hora.required' => 'La fecha y hora son obligatorias',
            'fecha_hora.date' => 'El formato de fecha y hora no es válido',
            'categoria_id.required' => 'La categoría es obligatoria',
            'categoria_id.exists' => 'La categoría seleccionada no existe',
        ];
    }
}
