<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="OperatorResource",
 *     description="Esquema del recurso Operador",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Identificador del operador",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="nombre",
 *         type="string",
 *         description="Nombre completo del operador",
 *         example="Juan Pérez"
 *     ),
 *     @OA\Property(
 *         property="email",
 *         type="string",
 *         format="email",
 *         description="Correo electrónico del operador",
 *         example="juan.perez@example.com"
 *     ),
 *     @OA\Property(
 *         property="telefono",
 *         type="string",
 *         description="Número de teléfono del operador",
 *         example="+34 612 345 678"
 *     ),
 *     @OA\Property(
 *         property="fecha_contratacion",
 *         type="string",
 *         format="date",
 *         description="Fecha de contratación del operador",
 *         example="2023-05-10"
 *     ),
 *     @OA\Property(
 *         property="fecha_baja",
 *         type="string",
 *         format="date",
 *         nullable=true,
 *         description="Fecha de baja del operador (si aplica)",
 *         example="2025-12-01"
 *     ),
 *     @OA\Property(
 *         property="is_active",
 *         type="boolean",
 *         description="Indica si el operador está activo",
 *         example=true
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha de creación del operador",
 *         example="2025-02-23 12:00:00"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha de última actualización del operador",
 *         example="2025-02-24 12:00:00"
 *     )
 * )
 */

class OperatorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'email' => $this->email,
            'telefono' => $this->telefono,
            'fecha_contratacion' => $this->fecha_contratacion?->format('Y-m-d'),
            'fecha_baja' => $this->fecha_baja?->format('Y-m-d'),
            'is_active' => $this->is_active,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
