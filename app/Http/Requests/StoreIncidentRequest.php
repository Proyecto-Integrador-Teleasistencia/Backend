<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIncidentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'paciente_id' => 'required|exists:pacientes,id',
            'descripcion' => 'nullable|string',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'paciente_id.required' => 'El ID del paciente es obligatorio',
            'paciente_id.exists' => 'El paciente seleccionado no existe',
            'descripcion.string' => 'La descripci n debe ser una cadena',
        ];
    }

    /**
     * Get the proper failed validation response for the request.
     *
     * @param  array<string, string>  $errors
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors): \Symfony\Component\HttpFoundation\Response
    {
        return response()->json(['success' => false, 'message' => 'Error creant la incidència'], 422);
    }
}
