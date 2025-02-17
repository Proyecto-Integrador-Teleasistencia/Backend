<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateZoneRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('zones')->ignore($this->zone)],
            'description' => 'nullable|string',
            'active' => 'sometimes|boolean',
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
