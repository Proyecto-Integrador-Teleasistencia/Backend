<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UserResource",
 *     description="Esquema del recurso Usuario",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Identificador único del usuario",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="nombre",
 *         type="string",
 *         description="Nombre completo del usuario",
 *         example="Juan Pérez"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         description="Correo electrónico del usuario",
 *         example="juan.perez@example.com"
 *     ),
 *     @OA\Property(
 *         property="role",
 *         type="string",
 *         description="Rol asignado al usuario",
 *         example="admin"
 *     ),
 *     @OA\Property(
 *         property="is_active",
 *         type="boolean",
 *         description="Indica si el usuario está activo",
 *         example=true
 *     ),
 *     @OA\Property(
 *         property="zona_id",
 *         type="integer",
 *         description="ID de la zona asignada al usuario",
 *         example=3
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha de creación del usuario",
 *         example="2025-02-23 12:00:00"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha de última actualización del usuario",
 *         example="2025-02-24 12:00:00"
 *     )
 * )
 */

class UserResource extends JsonResource
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
            'email' => $this->email,
            'role' => $this->role,
            'is_active' => $this->is_active,
            'zona_id' => $this->zona_id,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
