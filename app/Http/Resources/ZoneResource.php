<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
