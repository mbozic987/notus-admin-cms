<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class BaseCategoryRequest extends FormRequest
{
    public function mappedAttributes() {
        $attributesMap = [
            'name' => 'name',
            'depth' => 'depth',
            'parentId' => 'parent_id',
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

}
