<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PatientResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\AlertResource;

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
            'datetime' => $this->datetime->format('Y-m-d H:i:s'),
            'type' => $this->type,
            'type_formatted' => $this->call_type_formatted,
            'description' => $this->description,
            'is_recent' => $this->is_recent,
            'scheduled' => $this->scheduled,
            'patient' => new PatientResource($this->whenLoaded('patient')),
            'operator' => new UserResource($this->whenLoaded('operator')),
            'category' => new CategoryResource($this->whenLoaded('category')),
            'alert' => new AlertResource($this->whenLoaded('alert')),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
