<?php

namespace App\Http\Requests\Api\V1;

use App\Rules\Api\V1\CheckParentDepth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends BaseCategoryRequest
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
            'name' => [
                'sometimes',
                'string',
                Rule::unique('categories')->ignore($this->category)],
            'parentId' => [
                'sometimes',
                'exists:categories,id',
                new CheckParentDepth
            ]
        ];
    }
}
