<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UpdateIncidentRequest",
 *     description="Validación para la actualización de incidencias",
 *     @OA\Property(
 *         property="paciente_id",
 *         type="integer",
 *         description="ID del paciente asociado a la incidencia",
 *         example=5
 *     ),
 *     @OA\Property(
 *         property="descripcion",
 *         type="string",
 *         description="Descripción detallada de la incidencia",
 *         example="El paciente experimentó una caída durante la noche"
 *     )
 * )
 */
class UpdateIncidentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'paciente_id' => 'sometimes|exists:pacientes,id',
            'descripcion' => 'sometimes|string',
        ];
    }

    public function messages(): array
    {
        return [
            'paciente_id.exists' => 'El paciente seleccionado no existe',
            'descripcion.string' => 'La descripción debe ser una cadena de texto',
        ];
    }
}
