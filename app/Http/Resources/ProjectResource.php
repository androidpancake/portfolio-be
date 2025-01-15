<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'description' => $this->title,
            'image' => $this->title,
            'category' => CategoryResource::collection($this->whenLoaded('category')),
            'start_date' => $this->title,
            'end_date' => $this->title,
        ];
    }
}
