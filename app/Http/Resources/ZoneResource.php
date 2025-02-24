<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="ZonaResource",
 *     description="Esquema del recurso Zona",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Identificador único de la zona",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="nombre",
 *         type="string",
 *         description="Nombre de la zona",
 *         example="Zona Norte"
 *     ),
 *     @OA\Property(
 *         property="codigo",
 *         type="string",
 *         description="Código único de la zona",
 *         example="ZN-001"
 *     ),
 *     @OA\Property(
 *         property="activa",
 *         type="boolean",
 *         description="Estado de la zona, activa o inactiva",
 *         example=true
 *     ),
 *     @OA\Property(
 *         property="estado_texto",
 *         type="string",
 *         nullable=true,
 *         description="Descripción del estado actual de la zona",
 *         example="En mantenimiento"
 *     ),
 *     @OA\Property(
 *         property="numero_operadores",
 *         type="integer",
 *         description="Número total de operadores asignados a la zona",
 *         example=5
 *     ),
 *     @OA\Property(
 *         property="numero_pacientes",
 *         type="integer",
 *         description="Número total de pacientes asignados a la zona",
 *         example=50
 *     ),
 *     @OA\Property(
 *         property="operadores",
 *         type="array",
 *         description="Lista de operadores asignados a la zona",
 *         @OA\Items(ref="#/components/schemas/UserResource")
 *     ),
 *     @OA\Property(
 *         property="pacientes",
 *         type="array",
 *         description="Lista de pacientes asignados a la zona",
 *         @OA\Items(ref="#/components/schemas/PatientResource")
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha de creación de la zona",
 *         example="2025-02-23 12:00:00"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha de última actualización de la zona",
 *         example="2025-02-24 12:00:00"
 *     )
 * )
 */

class ZoneResource extends JsonResource
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
            'nombre' => $this->nombre,
            'codigo' => $this->codigo,
            'activa' => $this->activa,
            'estado_texto' => $this->estado_texto,
            'numero_operadores' => $this->numero_operadores,
            'numero_pacientes' => $this->whenCounted('pacientes'),
            'operadores' => UserResource::collection($this->whenLoaded('operator')),
            'pacientes' => PatientResource::collection($this->whenLoaded('pacientes')),
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
