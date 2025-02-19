<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'apellido' => $this->apellido,
            'telefono' => $this->telefono,
            'relacion' => $this->relacion,
            'direccion' => $this->direccion,
            'nivel_prioridad' => $this->nivel_prioridad,
            'disponibilidad' => $this->disponibilidad,
            'tiene_llaves' => $this->tiene_llaves,
            'paciente' => new PatientResource($this->whenLoaded('paciente')),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
