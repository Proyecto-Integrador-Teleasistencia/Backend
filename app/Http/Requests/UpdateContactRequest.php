<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UpdateContactRequest",
 *     description="Validación para la actualización de contactos de pacientes",
 *     required={"paciente_id", "nombre", "telefono", "relacion"},
 *     @OA\Property(
 *         property="paciente_id",
 *         type="integer",
 *         description="ID del paciente asociado al contacto",
 *         example=5
 *     ),
 *     @OA\Property(
 *         property="nombre",
 *         type="string",
 *         maxLength=100,
 *         description="Nombre del contacto",
 *         example="Carlos Pérez"
 *     ),
 *     @OA\Property(
 *         property="telefono",
 *         type="string",
 *         maxLength=20,
 *         description="Número de teléfono del contacto",
 *         example="+34 612 345 678"
 *     ),
 *     @OA\Property(
 *         property="relacion",
 *         type="string",
 *         maxLength=50,
 *         description="Relación con el paciente",
 *         example="Hermano"
 *     )
 * )
 */
class UpdateContactRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'paciente_id' => 'required|exists:pacientes,id',
            'nombre' => 'required|string|max:100',
            'telefono' => 'required|string|max:20',
            'relacion' => 'required|string|max:50',
        ];
    }

    public function messages()
    {
        return [
            'paciente_id.required' => 'El paciente es obligatorio',
            'paciente_id.exists' => 'El paciente seleccionado no existe',
            'nombre.required' => 'El nombre es obligatorio',
            'nombre.max' => 'El nombre no puede tener más de 100 caracteres',
            'relacion.required' => 'La relación es obligatoria',
            'relacion.max' => 'La relación no puede tener más de 50 caracteres',
            'telefono.required' => 'El teléfono es obligatorio',
            'telefono.max' => 'El teléfono no puede tener más de 20 caracteres',
        ];
    }
}
