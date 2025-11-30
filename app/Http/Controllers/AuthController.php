<?php

namespace App\Http\Controllers;

use App\Events\UserRegistered;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        $user = User::create($request->validated());
        $user->profile()->create(['bio' => $user->name]);
        $token = $user->createToken($request->name);
        
        return [
            'user' => new UserResource($user),
            'token' => $token->plainTextToken
        ];
    }

    public function login(LoginUserRequest $request)
    {
        $request->validated();
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password))
        {
            return [
                'message' => 'the provided credentials are incorrect.'
            ];
        }
        $token = $user->createToken($user->name);
        
        return [
            'user' => new UserResource($user),
            'token' => $token->plainTextToken
        ];

        
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return [
                'message' => 'You were logged out'
            ];;
    }
}
