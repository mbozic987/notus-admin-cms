<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'comment',
            'id' => $this->id,
            'attributes' => [
                'title' => $this->title,
                'content' => $this->content,
                'grade' => $this->grade,
                'createdAt' => $this->created_at,
                'updatedAt' => $this->updated_at
            ],
            'relationships' => [
                'user' => [
                    'data' => [
                        'type' => 'user',
                        'id' => $this->user_id
                    ],
                    'links' => [
                        'self' => $this->user_id ? route('users.show', ['user' => $this->user_id]) : null,
                        ]
                ],
                'product' => [
                    'data' => [
                        'type' => 'product',
                        'id' => $this->product_id
                    ],
                    'links' => [
                        'self' => route('products.show', ['product' => $this->product_id])
                    ]
                ]
            ],
            'includes' => [
                new UserResource($this->whenLoaded('user')),
                new ProductResource($this->whenLoaded('product'))
                ],
            'links' => [
                'self' => route('comments.show', ['comment' => $this->id])
            ],
        ];
    }
}
