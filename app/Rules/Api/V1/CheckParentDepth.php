<?php

namespace App\Rules\Api\V1;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Category;

class CheckParentDepth implements Rule
{
    public function passes($attribute, $value)
    {
        if ($value) {
            $parentCategory = Category::find($value);
            if ($parentCategory && $parentCategory->depth === 3) {
                return false;
            }
        }
        return true;
    }

    public function message()
    {
        return 'The selected parent category has reached the maximum depth.';
    }
}
