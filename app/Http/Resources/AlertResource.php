<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlertResource extends JsonResource
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
            'tipo' => $this->tipo,
            'fecha_hora' => $this->fecha_hora?->format('Y-m-d H:i:s'),
            'descripcion' => $this->descripcion,
            'completado' => $this->completado,
            'fecha_completado' => $this->fecha_completado?->format('Y-m-d H:i:s'),
            'categoria' => new CategoryResource($this->whenLoaded('categoria')),
            'paciente' => new PatientResource($this->whenLoaded('paciente')),
            'operador' => new UserResource($this->whenLoaded('operador')),
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
