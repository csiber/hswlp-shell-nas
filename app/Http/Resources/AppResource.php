<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'status' => $this->status,
            'http_port' => $this->http_port,
            'instances' => AppInstanceResource::collection($this->whenLoaded('instances')),
            'instances_count' => $this->when(isset($this->instances_count), $this->instances_count),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
