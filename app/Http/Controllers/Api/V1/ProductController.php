<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\Api\V1\ProductFilter;
use App\Http\Requests\Api\V1\StoreProductRequest;
use App\Http\Requests\Api\V1\UpdateProductRequest;
use App\Http\Resources\Api\V1\ProductResource;
use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(ProductFilter $filters)
    {
        return ProductResource::collection(Product::filter($filters)->with('media')->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $this->authorize('store', Product::class);
        $product = Product::create($request->mappedAttributes());

        $product->categories()->attach($request->categories);

        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     */
    public function show($product_id)
    {
        try {
            $product = Product::with('media')->findOrFail($product_id);

            if ($this->include('comments')) {
                return new ProductResource($product->load('comments'));
            } elseif ($this->include('categories')) {
                return new ProductResource($product->load('categories'));
            }

            return new ProductResource($product);
        } catch (ModelNotFoundException $exception) {
            return $this->error('Product not found', 404);
        }
    }

    /**88888888
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, $product_id)
    {
        try {
            $product = Product::findOrFail($product_id);
            $this->authorize('update', $product);

            $product->update($request->mappedAttributes());

            $product->categories()->sync($request->categories);

            return new ProductResource($product);
        } catch (ModelNotFoundException $exception) {
            return $this->error('Product not found', 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($product_id)
    {
        try {
            $product = Product::findOrFail($product_id);
            $this->authorize('delete', $product);

            $product->delete();
            return $this->success('Product deleted!', null);
        } catch (ModelNotFoundException $exception) {
            return $this->error('Product not found', 404);
        }
    }
}
