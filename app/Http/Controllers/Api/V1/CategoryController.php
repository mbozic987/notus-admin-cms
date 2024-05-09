<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreCategoryRequest;
use App\Http\Requests\Api\V1\UpdateCategoryRequest;
use App\Http\Resources\Api\V1\CategoryResource;
use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return CategoryResource::collection(Category::getAllWithSubcategories());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $this->authorize('create', Category::class);
        $category = Category::create($request->mappedAttributes());

        return new CategoryResource($category);
    }

    /**
     * Display the specified resource.
     */
    public function show($category_id)
    {
        try {
            $category = Category::findOrFail($category_id);

            return new CategoryResource($category);
        } catch (ModelNotFoundException $exception) {
            return $this->error('Category not found', 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, $category_id)
    {
        try {
            $category = Category::findOrFail($category_id);
            $this->authorize('update', $category);

            $category->update($request->mappedAttributes());

            return new CategoryResource($category);
        } catch (ModelNotFoundException $exception) {
            return $this->error('Category not found', 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($category_id)
    {
        try {
            $category = Category::findOrFail($category_id);
            $this->authorize('delete', $category);

            $category->delete();

            return $this->success('Category deleted', null);
        } catch (ModelNotFoundException $exception) {
            return $this->error('Category not found', 404);
        }
    }
}
