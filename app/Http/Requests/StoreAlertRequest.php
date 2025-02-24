<?php

namespace App\Http\Requests;

use App\Models\Aviso;
use App\Models\Paciente;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="StoreAlertRequest",
 *     description="Validación para la creación y actualización de alertas",
 *     required={"tipo", "fecha_hora", "descripcion", "completado", "categoria_id", "paciente_id", "operador_id"},
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         nullable=true,
 *         description="ID de la alerta existente (opcional)",
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
 *         example="2025-02-24T01:45:22Z"
 *     ),
 *     @OA\Property(
 *         property="descripcion",
 *         type="string",
 *         description="Descripción detallada de la alerta",
 *         example="Paciente necesita atención inmediata"
 *     ),
 *     @OA\Property(
 *         property="completado",
 *         type="boolean",
 *         description="Estado de la alerta",
 *         example=false
 *     ),
 *     @OA\Property(
 *         property="fecha_completado",
 *         type="string",
 *         format="date-time",
 *         nullable=true,
 *         description="Fecha y hora en que la alerta fue completada",
 *         example=null
 *     ),
 *     @OA\Property(
 *         property="categoria_id",
 *         type="integer",
 *         description="ID de la categoría de la alerta",
 *         example=2
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
 *         description="ID del operador responsable de la alerta",
 *         example=3
 *     )
 * )
 */
class StoreAlertRequest extends FormRequest
{
    public function authorize()
    {
        // Verificar si el usuario puede crear alertas
        if (!$this->user()->can('create', Aviso::class)) {
            return false;
        }

        // Verificar si el usuario tiene acceso al paciente
        $patient = Paciente::findOrFail($this->input('paciente_id'));
        return $this->user()->can('view', $patient);
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
            'completado.required' => 'El campo completado es obligatorio',
            'completado.boolean' => 'El campo completado debe ser true o false',
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
