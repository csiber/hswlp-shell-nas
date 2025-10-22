<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppInstanceResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'app_id' => $this->app_id,
            'name' => $this->name,
            'bind_path' => $this->bind_path,
            'env' => $this->env_json,
            'status' => $this->status,
            'app' => $this->whenLoaded('app', fn () => new AppResource($this->app)),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
