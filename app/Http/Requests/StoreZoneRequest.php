<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreZoneRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:zones',
            'description' => 'nullable|string',
            'active' => 'boolean',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre es obligatorio',
            'name.max' => 'El nombre no puede tener más de 255 caracteres',
            'name.unique' => 'Ya existe una zona con este nombre',
            'active.boolean' => 'El campo activo debe ser verdadero o falso',
        ];
    }
}
