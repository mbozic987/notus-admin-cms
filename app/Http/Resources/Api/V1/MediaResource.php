<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'media',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name . $this->extention,
                'path' => Storage::url($this->path),
                'isPrimary' => $this->is_primary
            ],

            'links' => [
                'self' => route('products.pictures.show',
                    ['product' => $this->product_id, 'picture' => $this->id])
            ],
        ];
    }
}
