<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'product',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
                'createdAt' => $this->created_at,
                'updatedAt' => $this->updated_at
            ],

            'includes' => [
                MediaResource::collection($this->whenLoaded('media')),
                CommentResource::collection($this->whenLoaded('comments')),
                CategoryResource::collection($this->whenLoaded('categories')),
            ],
            'links' => [
                'self' => route('products.show', ['product' => $this->id])
            ],
        ];
    }
}
