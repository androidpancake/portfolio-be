<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'background' => $this->background,
            'stack1' => $this->stack1,
            'stack2' => $this->stack2,
            'stack3' => $this->stack3,
            'db' => $this->db,
            'logo' => $this->logo,
        ];
    }
}
