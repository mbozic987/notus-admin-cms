<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class BaseProductRequest extends FormRequest
{
    public function mappedAttributes() {
        $attributesMap = [
            'name' => 'name',
            'description' => 'description',
            'price' => 'price',
            'createdAt' => 'created_at',
            'updatedAt' => 'updated_at'
        ];

        $attributesToUpdate = [];
        foreach ($attributesMap as $key => $attribute) {
            if ($this->has($key)) {
                $attributesToUpdate[$attribute] = $this->input($key);
            }
        }

        return $attributesToUpdate;
    }
    public function messages()
    {
        return [
            'role' => 'Role is required and can be only admin or moderator.'
        ];
    }
}
