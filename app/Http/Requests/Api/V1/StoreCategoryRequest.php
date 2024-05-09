<?php

namespace App\Http\Requests\Api\V1;

use App\Models\Category;
use App\Rules\Api\V1\CheckParentDepth;
use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends BaseCategoryRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:categories',
            'parentId' => [
                'sometimes',
                'exists:categories,id',
                new CheckParentDepth
            ]
        ];
    }

    /**
     * Add calculated depth to the request after validation passes.
     */
    protected function passedValidation()
    {
        $parentId = $this->input('parentId');
        if ($parentId) {
            $parentCategory = Category::find($parentId);
            $depth = $parentCategory->depth + 1;
        } else {
            $depth = 0;
        }

        $this->merge(['depth' => $depth]);
    }
}
