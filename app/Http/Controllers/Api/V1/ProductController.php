<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\Api\V1\ProductFilter;
use App\Http\Requests\Api\V1\StoreProductRequest;
use App\Http\Requests\Api\V1\UpdateProductRequest;
use App\Http\Resources\Api\V1\ProductResource;
use App\Models\Product;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductController extends ApiController
{
    /**
     * View all products
     *
     * Fetch and return all products, filters, sort and includes implemented.
     *
     * @unauthenticated
     * @group Product
     */
    public function index(ProductFilter $filters)
    {
        return ProductResource::collection(Product::filter($filters)->with('media')->paginate());
    }

    /**
     * Create product
     *
     * Create new product.
     *
     * @group Product
     */
    public function store(StoreProductRequest $request)
    {
        $this->authorize('store', Product::class);
        $product = Product::create($request->mappedAttributes());

        $product->categories()->attach($request->categories);

        return new ProductResource($product);
    }

    /**
     * View product
     *
     * Fetch specific product.
     *
     * @unauthenticated
     * @group Product
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

    /**
     * Update product
     *
     * Update specific product.
     *
     * @group Product
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
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to update that resource', 401);
        }
    }

    /**
     * Delete product
     *
     * Delete specific product.
     *
     * @group Product
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
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to delete that resource', 401);
        }
    }
}
