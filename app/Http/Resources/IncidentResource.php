<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="IncidentResource",
 *     description="Esquema del recurso Incidencia",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Identificador de la incidencia",
 *         example=1
 *     ),
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
 *         example="El paciente presentó una caída durante la noche"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha de creación de la incidencia",
 *         example="2025-02-23 12:00:00"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha de última actualización de la incidencia",
 *         example="2025-02-24 12:00:00"
 *     )
 * )
 */

class IncidentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'paciente_id' => $this->paciente_id,
            'descripcion' => $this->descripcion,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
