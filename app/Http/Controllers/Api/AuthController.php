<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AuthLoginRequest;
use App\Http\Requests\Api\LoginAuthRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(AuthLoginRequest $request): JsonResponse
    {
        if (!Auth::attempt($request->only('login', 'password'))) {
            return response()->json([
                'message' => 'неверный пароль',
            ], 400);
        }

        $user = User::where('login', $request['login'])->firstOrFail();
        if ($request->has('device_token')) {
            $user->device_token = $request->get('device_token');
            $user->save();
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => new UserResource($user),
        ]);
    }

    public function logout(): JsonResponse
    {
        Auth::user()->tokens()->delete();

        return response()->json(['message' => 'вы вышли']);
    }

}
