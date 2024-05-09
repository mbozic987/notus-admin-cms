<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Requests\Api\V1\LoginUserRequest;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends ApiController
{public function register(StoreUserRequest $request)
    {
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        $user->assignRole('user')->refresh();

        $access_token = $user->createToken('API token for ' . $user->email)->plainTextToken;

        return $this->loggedin('Welcome, you have successfully signed in!', $user, $access_token);
    }

    public function login(LoginUserRequest $request)
    {
        $request->validated($request->all());

        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->error('Invalid credentials', 401);
        }

        $user = User::firstWhere('email', $request->email);
        $access_token = $user->createToken('API token for ' . $user->email)->plainTextToken;

        return $this->loggedin('Successfully logged in!', $user, $access_token);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success('Successfully logged out', null);
    }
}
