<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="CategoriaResource",
 *     description="Esquema del recurso Categoría",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Identificador de la categoría",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="nombre",
 *         type="string",
 *         description="Nombre de la categoría",
 *         example="Urgencias Médicas"
 *     ),
 *     @OA\Property(
 *         property="descripcion",
 *         type="string",
 *         nullable=true,
 *         description="Descripción de la categoría",
 *         example="Categoría para gestionar urgencias médicas prioritarias"
 *     ),
 *     @OA\Property(
 *         property="subcategorias",
 *         type="array",
 *         description="Lista de subcategorías asociadas a la categoría",
 *         @OA\Items(ref="#/components/schemas/SubcategoriaResource")
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha de creación de la categoría",
 *         example="2025-02-23 12:00:00"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha de última actualización de la categoría",
 *         example="2025-02-24 12:00:00"
 *     )
 * )
 */

class CategoriaResource extends JsonResource
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
            'descripcion' => $this->descripcion,
            'subcategorias' => SubcategoriaResource::collection($this->whenLoaded('subcategorias')),
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
