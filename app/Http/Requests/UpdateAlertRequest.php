<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UpdateAlertRequest",
 *     description="Validación para la actualización de alertas",
 *     required={"tipo", "fecha_hora", "descripcion", "completado", "categoria_id", "paciente_id", "operador_id"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         nullable=true,
 *         description="ID de la alerta existente",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="tipo",
 *         type="string",
 *         enum={"puntual", "periodico"},
 *         description="Tipo de alerta",
 *         example="puntual"
 *     ),
 *     @OA\Property(
 *         property="fecha_hora",
 *         type="string",
 *         format="date-time",
 *         description="Fecha y hora de la alerta",
 *         example="2025-02-24T15:30:00Z"
 *     ),
 *     @OA\Property(
 *         property="descripcion",
 *         type="string",
 *         description="Descripción de la alerta",
 *         example="Revisión médica programada"
 *     ),
 *     @OA\Property(
 *         property="completado",
 *         type="boolean",
 *         description="Indica si la alerta ha sido completada",
 *         example=true
 *     ),
 *     @OA\Property(
 *         property="fecha_completado",
 *         type="string",
 *         format="date-time",
 *         nullable=true,
 *         description="Fecha y hora en que se completó la alerta",
 *         example="2025-02-25T10:00:00Z"
 *     ),
 *     @OA\Property(
 *         property="categoria_id",
 *         type="integer",
 *         description="ID de la categoría de la alerta",
 *         example=3
 *     ),
 *     @OA\Property(
 *         property="paciente_id",
 *         type="integer",
 *         description="ID del paciente asociado a la alerta",
 *         example=5
 *     ),
 *     @OA\Property(
 *         property="operador_id",
 *         type="integer",
 *         description="ID del operador encargado de la alerta",
 *         example=2
 *     )
 * )
 */
class UpdateAlertRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => 'nullable|integer|exists:avisos,id',
            'tipo' => 'required|string|in:puntual,periodico',
            'fecha_hora' => 'required|date',
            'descripcion' => 'required|string',
            'completado' => 'required|boolean',
            'fecha_completado' => 'nullable|date',
            'categoria_id' => 'required|exists:categorias,id',
            'paciente_id' => 'required|exists:pacientes,id',
            'operador_id' => 'required|exists:users,id',
        ];
    }

    public function messages()
    {
        return [
            'id.integer' => 'El id debe ser un número',
            'id.exists' => 'El id no existe',
            'tipo.required' => 'El tipo es obligatorio',
            'tipo.in' => 'El tipo debe ser puntual o periodico',
            'fecha_hora.required' => 'La fecha y hora son obligatorias',
            'fecha_hora.date' => 'El formato de fecha y hora no es válido',
            'descripcion.required' => 'La descripción es obligatoria',
            'completado.required' => 'La completado es obligatorio',
            'completado.boolean' => 'La completado debe ser true o false',
            'fecha_completado.date' => 'El formato de fecha y hora no es válido',
            'categoria_id.required' => 'La categoría es obligatoria',
            'categoria_id.exists' => 'La categoría seleccionada no existe',
            'paciente_id.required' => 'El paciente es obligatorio',
            'paciente_id.exists' => 'El paciente seleccionado no existe',
            'operador_id.required' => 'El operador es obligatorio',
            'operador_id.exists' => 'El operador seleccionado no existe',
        ];
    }
}
