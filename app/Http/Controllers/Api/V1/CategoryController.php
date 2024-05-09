<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreCategoryRequest;
use App\Http\Requests\Api\V1\UpdateCategoryRequest;
use App\Http\Resources\Api\V1\CategoryResource;
use App\Models\Category;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryController extends ApiController
{
    /**
     * View all categories
     *
     * Fetch and return all categories with subcategories.
     *
     * @unauthenticated
     * @group Category
     */
    public function index()
    {
        return CategoryResource::collection(Category::getAllWithSubcategories());
    }

    /**
     * Create category
     *
     * Create new category.
     *
     * @group Category
     */
    public function store(StoreCategoryRequest $request)
    {
        try {
            $this->authorize('create', Category::class);
            $category = Category::create($request->mappedAttributes());

            return new CategoryResource($category);
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to create that resource', 401);
        }
    }

    /**
     * View category
     *
     * Fetch specific category.
     *
     * @unauthenticated
     * @group Category
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
     * Update category
     *
     * Update specific category.
     *
     * @group Category
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
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to update that resource', 401);
        }
    }

    /**
     * Delete category
     *
     * Delete specific category.
     *
     * @group Category
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
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to delete that resource', 401);
        }
    }
}
