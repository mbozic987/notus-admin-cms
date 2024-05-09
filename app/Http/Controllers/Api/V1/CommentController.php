<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\Api\V1\CommentFilter;
use App\Http\Requests\Api\V1\StoreCommentRequest;
use App\Http\Requests\Api\V1\UpdateCommentRequest;
use App\Http\Resources\Api\V1\CommentResource;
use App\Models\Comment;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CommentController extends ApiController
{
    /**
     * View all Comments
     *
     * Fetch and return all comments, filters, sort and includes implemented.
     *
     * @unauthenticated
     * @group Comment
     */
    public function index(CommentFilter $filters)
    {
        return CommentResource::collection(Comment::filter($filters)->paginate());
    }

    /**
     * Create Comment
     *
     * Create new comment.
     *
     * @unauthenticated
     * @group Comment
     */
    public function store(StoreCommentRequest $request)
    {
        $comment = Comment::create($request->mappedAttributes());

        return new CommentResource($comment);
    }

    /**
     * View Comment
     *
     * Fetch specific category.
     *
     * @unauthenticated
     * @group Comment
     */
    public function show(Comment $comment)
    {
        if ($this->include('user')) {
            return new CommentResource($comment->load('user'));
        }
        return new CommentResource($comment);
    }

    /**
     * Update Comment
     *
     * Update specific comment.
     *
     * @group Comment
     */
    public function update(UpdateCommentRequest $request, $comment_id)
    {
        try {
            $comment = Comment::findOrFail($comment_id);
            $this->authorize('update', $comment);

            $comment->update($request->mappedAttributes());
            return new CommentResource($comment);
        } catch (ModelNotFoundException $exception) {
            return $this->error('Comment not found', 404);
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to update that resource', 401);
        }
    }

    /**
     * Delete Comment
     *
     * Delete specific comment.
     *
     * @group Comment
     */
    public function destroy($comment_id)
    {
        try {
            $comment = Comment::findOrFail($comment_id);
            $this->authorize('delete', $comment);

            $comment->delete();
            return $this->success('Comment deleted!', null);
        } catch (ModelNotFoundException $exception) {
            return $this->error('Comment not found', 404);
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to delete that resource', 401);
        }
    }
}
