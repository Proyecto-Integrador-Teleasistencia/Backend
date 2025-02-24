<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="StoreIncidentRequest",
 *     description="Validación para la creación de incidencias",
 *     required={"paciente_id"},
 *     @OA\Property(
 *         property="paciente_id",
 *         type="integer",
 *         description="ID del paciente asociado a la incidencia",
 *         example=5
 *     ),
 *     @OA\Property(
 *         property="descripcion",
 *         type="string",
 *         nullable=true,
 *         description="Descripción detallada de la incidencia",
 *         example="El paciente sufrió una caída durante la noche"
 *     )
 * )
 */
class StoreIncidentRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'paciente_id' => 'required|exists:pacientes,id',
            'descripcion' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'paciente_id.required' => 'El ID del paciente es obligatorio',
            'paciente_id.exists' => 'El paciente seleccionado no existe',
            'descripcion.string' => 'La descripci n debe ser una cadena',
        ];
    }

    public function response(array $errors): \Symfony\Component\HttpFoundation\Response
    {
        return response()->json(['success' => false, 'message' => 'Error creant la incidència'], 422);
    }
}
