<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="SubcategoriaResource",
 *     description="Esquema del recurso Subcategoría",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Identificador único de la subcategoría",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="nombre",
 *         type="string",
 *         description="Nombre de la subcategoría",
 *         example="Emergencia leve"
 *     ),
 *     @OA\Property(
 *         property="categoria_id",
 *         ref="#/components/schemas/CategoriaResource",
 *         description="Categoría a la que pertenece la subcategoría"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha de creación de la subcategoría",
 *         example="2025-02-23 12:00:00"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha de última actualización de la subcategoría",
 *         example="2025-02-24 12:00:00"
 *     )
 * )
 */

class SubcategoriaResource extends JsonResource
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
            'categoria_id' => new CategoriaResource($this->whenLoaded('categoria')),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
