<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\Api\V1\UserFilter;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(UserFilter $filters)
    {
        $this->authorize('viewAny', User::class);

        return UserResource::collection(User::filter($filters)->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $this->authorize('create', User::class);

        $user = User::create($request->mappedAttributes());

        $user->assignRole($request->input('role'))->refresh();

        return new UserResource($user);
    }

    /**
     * Display the specified resource.
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
        }
    }

    /**
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
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
        }
    }
}
