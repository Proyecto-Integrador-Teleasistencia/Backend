<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
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
            'fecha_nacimiento' => $this->fecha_nacimiento ? $this->fecha_nacimiento->format('Y-m-d') : null,
            'direccion' => $this->direccion,
            'ciudad' => $this->ciudad,
            'codigo_postal' => $this->codigo_postal,
            'dni' => $this->dni,
            'tarjeta_sanitaria' => $this->tarjeta_sanitaria,
            'telefono' => $this->telefono,
            'email' => $this->email,
            'zona' => new ZoneResource($this->whenLoaded('zona')),
            'situacion_personal' => $this->situacion_personal,
            'estado_salud' => $this->estado_salud,
            'condicion_vivienda' => $this->condicion_vivienda,
            'nivel_autonomia' => $this->nivel_autonomia,
            'situacion_economica' => $this->situacion_economica,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
