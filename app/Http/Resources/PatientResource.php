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
            'name' => $this->name,
            'birth_date' => $this->birth_date->format('Y-m-d'),
            'age' => $this->age,
            'address' => $this->address,
            'full_address' => $this->full_address,
            'dni' => $this->dni,
            'health_card' => $this->health_card,
            'phone' => $this->phone,
            'email' => $this->email,
            'zone' => new ZoneResource($this->whenLoaded('zone')),
            'contacts' => ContactResource::collection($this->whenLoaded('contacts')),
            'personal_situation' => $this->personal_situation,
            'health_condition' => $this->health_condition,
            'home_condition' => $this->home_condition,
            'autonomy_level' => $this->autonomy_level,
            'economic_situation' => $this->economic_situation,
            'recent_alerts_count' => $this->whenCounted('recent_alerts'),
            'alerts' => AlertResource::collection($this->whenLoaded('alerts')),
            'calls' => CallResource::collection($this->whenLoaded('calls')),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
