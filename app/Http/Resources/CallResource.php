<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PatientResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\CategoriaResource;
use App\Http\Resources\AvisoResource;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="CallResource",
 *     description="Esquema del recurso Llamada",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="Identificador de la llamada",
 *         example=1
 *     ),
 *     @OA\Property(
 *         property="fecha_hora",
 *         type="string",
 *         format="date-time",
 *         description="Fecha y hora en que se realizó la llamada",
 *         example="2025-02-24 15:30:00"
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
 *         property="planificada",
 *         type="boolean",
 *         description="Indica si la llamada fue planificada o no",
 *         example=true
 *     ),
 *     @OA\Property(
 *         property="operador_id",
 *         ref="#/components/schemas/UserResource",
 *         description="Información del operador que realizó la llamada"
 *     ),
 *     @OA\Property(
 *         property="paciente_id",
 *         ref="#/components/schemas/PatientResource",
 *         description="Información del paciente asociado a la llamada"
 *     ),
 *     @OA\Property(
 *         property="categoria_id",
 *         ref="#/components/schemas/CategoriaResource",
 *         description="Categoría de la llamada"
 *     ),
 *     @OA\Property(
 *         property="subcategoria_id",
 *         ref="#/components/schemas/SubcategoriaResource",
 *         description="Subcategoría de la llamada"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha de creación de la llamada",
 *         example="2025-02-23 12:00:00"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Fecha de última actualización de la llamada",
 *         example="2025-02-24 12:00:00"
 *     )
 * )
 */

class CallResource extends JsonResource
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
            'fecha_hora' => $this->fecha_hora ? $this->fecha_hora->format('Y-m-d H:i:s') : null,
            'tipo_llamada' => $this->tipo_llamada,
            'duracion' => $this->duracion,
            'descripcion' => $this->descripcion,
            'planificada' => $this->planificada,     
            'operador_id' => new UserResource($this->whenLoaded('operador')),
            'paciente_id' => new PatientResource($this->whenLoaded('paciente')),
            'categoria_id' => new CategoriaResource($this->whenLoaded('categoria')),
            'subcategoria_id' => new SubcategoriaResource($this->whenLoaded('subcategoria')),
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
