<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UpdateCallRequest",
 *     description="Validación para la actualización de llamadas",
 *     required={"fecha_hora", "tipo_llamada", "operador_id", "paciente_id", "categoria_id"},
 *     @OA\Property(
 *         property="fecha_hora",
 *         type="string",
 *         format="date-time",
 *         description="Fecha y hora en que se realizó la llamada",
 *         example="2025-02-24T15:30:00Z"
 *     ),
 *     @OA\Property(
 *         property="tipo_llamada",
 *         type="string",
 *         enum={"entrante", "saliente"},
 *         description="Tipo de llamada realizada",
 *         example="entrante"
 *     ),
 *     @OA\Property(
 *         property="duracion",
 *         type="integer",
 *         nullable=true,
 *         description="Duración de la llamada en segundos",
 *         example=300
 *     ),
 *     @OA\Property(
 *         property="descripcion",
 *         type="string",
 *         nullable=true,
 *         description="Descripción detallada de la llamada",
 *         example="Llamada de seguimiento con el paciente"
 *     ),
 *     @OA\Property(
 *         property="operador_id",
 *         type="integer",
 *         description="ID del operador que realizó la llamada",
 *         example=2
 *     ),
 *     @OA\Property(
 *         property="paciente_id",
 *         type="integer",
 *         description="ID del paciente asociado a la llamada",
 *         example=5
 *     ),
 *     @OA\Property(
 *         property="categoria_id",
 *         type="integer",
 *         description="ID de la categoría asignada a la llamada",
 *         example=3
 *     ),
 *     @OA\Property(
 *         property="subcategoria_id",
 *         type="integer",
 *         nullable=true,
 *         description="ID de la subcategoría asignada a la llamada",
 *         example=1
 *     )
 * )
 */
class UpdateCallRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'fecha_hora' => 'required|date_format:Y-m-d H:i:s',
            'tipo_llamada' => 'required|string|in:entrante,saliente',
            'duracion' => 'nullable|integer',
            'descripcion' => 'nullable|string',
            'operador_id' => 'required|exists:users,id',
            'paciente_id' => 'required|exists:pacientes,id',
            'categoria_id' => 'required|exists:categorias,id',
            'subcategoria_id' => 'nullable|exists:subcategorias,id',
        ];
    }

    public function messages()
    {
        return [
            'fecha_hora.required' => 'La fecha y hora son obligatorias',
            'fecha_hora.date_format' => 'El formato de fecha y hora no es válido',
            'tipo_llamada.required' => 'El tipo de llamada es obligatorio',
            'tipo_llamada.in' => 'El tipo de llamada debe ser entrante o saliente',
            'duracion.integer' => 'La duración debe ser un número entero',
            'operador_id.required' => 'El operador es obligatorio',
            'operador_id.exists' => 'El operador seleccionado no existe',
            'paciente_id.required' => 'El paciente es obligatorio',
            'paciente_id.exists' => 'El paciente seleccionado no existe',
            'categoria_id.required' => 'La categoría es obligatoria',
            'categoria_id.exists' => 'La categoría seleccionada no existe',
            'subcategoria_id.exists' => 'La subcategoría seleccionada no existe',
        ];
    }
}
