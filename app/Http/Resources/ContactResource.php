<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="ContactResource",
 *     description="Esquema del recurso Contacto",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Identificador del contacto",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="nombre",
 *         type="string",
 *         description="Nombre completo del contacto",
 *         example="Carlos Pérez"
 *     ),
 *     @OA\Property(
 *         property="telefono",
 *         type="string",
 *         description="Número de teléfono del contacto",
 *         example="+34 612 345 678"
 *     ),
 *     @OA\Property(
 *         property="relacion",
 *         type="string",
 *         description="Relación del contacto con el paciente",
 *         example="Hermano"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha de creación del contacto",
 *         example="2025-02-23 12:00:00"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha de última actualización del contacto",
 *         example="2025-02-24 12:00:00"
 *     )
 * )
 */

class ContactResource extends JsonResource
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
            'telefono' => $this->telefono,
            'relacion' => $this->relacion,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
