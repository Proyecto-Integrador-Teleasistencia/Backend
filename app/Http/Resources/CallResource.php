<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PatientResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\CategoriaResource;
use App\Http\Resources\AvisoResource;

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
            'estado' => $this->estado,
            'motivo' => $this->motivo,
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
