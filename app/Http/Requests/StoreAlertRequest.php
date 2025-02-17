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
            'periodicity' => 'required|string|in:one-time,periodic',
            'datetime' => 'required|date',
            'category_id' => 'required|exists:categories,id',
        ];
    }

    public function messages()
    {
        return [
            'periodicity.required' => 'La periodicidad es obligatoria',
            'periodicity.in' => 'La periodicidad debe ser one-time o periodic',
            'datetime.required' => 'La fecha y hora son obligatorias',
            'datetime.date' => 'El formato de fecha y hora no es válido',
            'category_id.required' => 'La categoría es obligatoria',
            'category_id.exists' => 'La categoría seleccionada no existe',
        ];
    }
}
