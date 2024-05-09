<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'category',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'depth' => $this->depth,
            ],

            'includes' => [
                CategoryResource::collection($this->children),

            ],
            'links' => [
                'self' => route('categories.show', ['category' => $this->id])
            ],
        ];
    }
}
