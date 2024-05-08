<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class BaseUserRequest extends FormRequest
{
    public function mappedAttributes() {
        $attributesMap = [
            'name' => 'name',
            'email' => 'email',
            'password' => 'password',
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
