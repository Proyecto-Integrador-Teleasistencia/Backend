<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="AvisoResource",
 *     description="Esquema del recurso Aviso",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Identificador del aviso",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="tipo",
 *         type="string",
 *         enum={"puntual", "periodico"},
 *         description="Tipo de aviso",
 *         example="puntual"
 *     ),
 *     @OA\Property(
 *         property="fecha_hora",
 *         type="string",
 *         format="date-time",
 *         description="Fecha y hora programada para el aviso",
 *         example="2025-02-24 15:30:00"
 *     ),
 *     @OA\Property(
 *         property="descripcion",
 *         type="string",
 *         description="Descripción del aviso",
 *         example="Revisión médica programada para el paciente"
 *     ),
 *     @OA\Property(
 *         property="completado",
 *         type="boolean",
 *         description="Indica si el aviso ha sido completado",
 *         example=true
 *     ),
 *     @OA\Property(
 *         property="fecha_completado",
 *         type="string",
 *         format="date-time",
 *         nullable=true,
 *         description="Fecha y hora en que se completó el aviso",
 *         example="2025-02-25 10:00:00"
 *     ),
 *     @OA\Property(
 *         property="categoria",
 *         ref="#/components/schemas/CategoriaResource",
 *         description="Categoría del aviso"
 *     ),
 *     @OA\Property(
 *         property="paciente",
 *         ref="#/components/schemas/PatientResource",
 *         description="Información del paciente relacionado con el aviso"
 *     ),
 *     @OA\Property(
 *         property="operador",
 *         ref="#/components/schemas/UserResource",
 *         description="Operador encargado de gestionar el aviso"
 *     ),
 *     @OA\Property(
 *         property="zona",
 *         ref="#/components/schemas/ZonaResource",
 *         description="Zona asociada al aviso"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha de creación del aviso",
 *         example="2025-02-23 12:00:00"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha de última actualización del aviso",
 *         example="2025-02-24 12:00:00"
 *     )
 * )
 */
class AvisoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tipo' => $this->tipo,
            'fecha_hora' => $this->fecha_hora?->format('Y-m-d H:i:s'),
            'descripcion' => $this->descripcion,
            'completado' => $this->completado,
            'fecha_completado' => $this->fecha_completado?->format('Y-m-d H:i:s'),
            'categoria' => new CategoriaResource($this->whenLoaded('categoria')),
            'paciente' => new PatientResource($this->whenLoaded('paciente')),
            'operador' => new UserResource($this->whenLoaded('operador')),
            'zona' => new ZonaResource($this->whenLoaded('zona')),
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
