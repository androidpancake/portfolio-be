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
            'description' => $this->description,
            'image' => $this->image,
            'category_id' => $this->category_id,
            'category' => $this->whenLoaded('categories', function () {
                return $this->categories->name;
            }),
            'skills' => $this->whenLoaded('skills', function () {
                return SkillsResource::collection($this->skills);
            }),
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ];
    }
}
