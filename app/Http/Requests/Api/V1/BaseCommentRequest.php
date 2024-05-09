<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class BaseCommentRequest extends FormRequest
{
    public function mappedAttributes() {
        $attributesMap = [
            'title' => 'title',
            'content' => 'content',
            'grade' => 'grade',
            'productId' => 'product_id',
            'userId' => 'user_id',
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

    //Optional validation messages
    public function messages()
    {
        return [
            'grade' => 'Your review grade must be between 1-5!',
        ];
    }
}
