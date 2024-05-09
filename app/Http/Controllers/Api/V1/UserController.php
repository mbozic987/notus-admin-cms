<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\Api\V1\UserFilter;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class UserController extends ApiController
{
    /**
     * View all users
     *
     * Fetch and return all users, filters, sort and includes implemented.
     *
     * @group User
     */
    public function index(UserFilter $filters)
    {
        try {
            $this->authorize('viewAny', User::class);

            return UserResource::collection(User::filter($filters)->paginate());
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to view those resource', 401);
        }
    }

    /**
     * Create user
     *
     * Create new user.
     *
     * @group User
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $this->authorize('create', User::class);

            $user = User::create($request->mappedAttributes());

            $user->assignRole($request->input('role'))->refresh();

            return new UserResource($user);
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to create that resource', 401);
        }
    }

    /**
     * View user
     *
     * Fetch specific user.
     *
     * @group User
     */
    public function show($user_id)
    {
        try {
            $user = User::findOrFail($user_id);
            $this->authorize('view', $user);

            if ($this->include('comments')) {
                return new UserResource($user->load('comments'));
            }

            return new UserResource($user);
        } catch (ModelNotFoundException $exception) {
            return $this->error('User not found', 404);
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to view that resource', 401);
        }
    }

    /**
     * Update user
     *
     * Update specific user.
     *
     * @group User
     */
    public function update(UpdateUserRequest $request, $user_id)
    {
        try {
            $user = User::findOrFail($user_id);
            $this->authorize('update', $user);


            $user->update($request->mappedAttributes());

            if ($request->has('role')) {
                $this->changeRole($user, $request);
            }

            return new UserResource($user);
        } catch (ModelNotFoundException $exception) {
            return $this->error('User not found', 404);
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to update that resource', 401);
        }
    }

    private function changeRole($user, $request)
    {
        if (!Auth::user()->hasRole('admin')) {
            return;
        } elseif ($user->hasRole($request->input('role'))) {
            return;
        }
        $user->syncRoles($request->input('role'))->refresh();
    }

    /**
     * Delete user
     *
     * Delete specific user.
     *
     * @group User
     */
    public function destroy($user_id)
    {
        try {
            $user = User::findOrFail($user_id);
            $this->authorize('delete', $user);

            $user->delete();
            return $this->success('User deleted!', null);
        } catch (ModelNotFoundException $exception) {
            return $this->error('User not found', 404);
        } catch (AuthorizationException $exception) {
            return $this->error('You are not authorized to delete that resource', 401);
        }
    }
}
