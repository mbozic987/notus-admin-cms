<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\StoreProductMediaRequest;
use App\Http\Requests\Api\V1\UpdateProductMediaRequest;
use App\Http\Resources\Api\V1\MediaResource;
use App\Models\Media;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;

class ProductMediaController extends ApiController
{
    /**
     * View all product pictures
     *
     * Fetch and return all pictures for specific product.
     *
     * @unauthenticated
     * @group ProductPicture
     */
    public function index($product_id)
    {
        return MediaResource::collection(Media::where('product_id', $product_id)->orderByDesc('is_primary')->get());
    }

    /**
     * Create product picture
     *
     * Upload media and create input in media table.
     *
     * @group ProductPicture
     */
    public function store(StoreProductMediaRequest $request, $product_id)
    {
        try {
            $this->authorize('create', Media::class);
            $folderPath = 'public/products/' . $product_id;

            $file = $request->file('media');
            $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();

            $path = $file->store($folderPath);

            if ($request->isPrimary) {
                Media::where('product_id', $product_id)
                    ->where('is_primary', true)
                    ->update(['is_primary' => false]);
            }

            $media = Media::create([
                'name' => $name,
                'path' => $path,
                'ext' => $extension,
                'product_id' => $product_id,
                'is_primary' => $request->isPrimary ?? false,
            ]);

            return new MediaResource($media);
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to create that resource', 401);
        }
    }

    /**
     * View product picture
     *
     * Fetch specific picture data.
     *
     * @unauthenticated
     * @group ProductPicture
     */
    public function show($product_id, $media_id)
    {
        try {
            $media = Media::where('id', $media_id)
                ->where('product_id', $product_id)
                ->firstOrFail();

            $this->authorize('update', $media);

            return new MediaResource($media);
        } catch (ModelNotFoundException $exception) {
            return $this->error('Media file not found', 404);
        }
    }

    /**
     * Update product picture
     *
     * Update picture data, for now only change is_primary state.
     *
     * @group ProductPicture
     */
    public function update(UpdateProductMediaRequest $request, $product_id, $media_id)
    {
        try {
            $media = Media::where('id', $media_id)
                ->where('product_id', $product_id)
                ->firstOrFail();

            $this->authorize('update', $media);

            Media::where('product_id', $product_id)
                ->where('is_primary', true)
                ->update(['is_primary' => false]);

            $media->update(['is_primary' => true]);

            return new MediaResource($media);
        } catch (ModelNotFoundException $exception) {
            return $this->error('User not found', 404);
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to update that resource', 401);
        }
    }

    /**
     * Delete product picture
     *
     * Delete media resource and remove file from server.
     *
     * @group ProductPicture
     */
    public function destroy($product_id, $media_id)
    {
        try {
            $media = Media::where('id', $media_id)
                ->where('product_id', $product_id)
                ->firstOrFail();

            $this->authorize('delete', $media);

            Storage::delete($media->path);

            $media->delete();

            return $this->success('Media file deleted!', null);
        } catch (ModelNotFoundException $exception) {
            return $this->error('Media file not found', 404);
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to delete that resource', 401);
        }
    }
}
