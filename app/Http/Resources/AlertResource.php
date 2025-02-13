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
            'type' => $this->type,
            'description' => $this->description,
            'priority' => $this->priority,
            'status' => $this->status,
            'completed_at' => $this->completed_at?->format('Y-m-d H:i:s'),
            'patient' => new PatientResource($this->whenLoaded('patient')),
            'calls' => CallResource::collection($this->whenLoaded('calls')),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
