<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\SignupRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Login with email and password
     *
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse{
        $data = $request->validated();

        if(!Auth::attempt($data)) {
            return response()->json(["message" => "Credentials not match"], 401);
        }

        /** @var User $user */
        $user = $request->user();

        $token = $user->createToken("api");

        return response()->json(['token' => $token->plainTextToken]);
    }

    public function signUp(SignupRequest $request): JsonResponse {
        $data =  $request->validated();

        $data['password'] = Hash::make($data['password']);

        /** @var User $user */
        $user = User::create($data);

        return response()->json(['user' => $user, 'token' => $user->createToken("api")->plainTextToken]);
    }

    public function signOut(Request $request) {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => ' you have been successfully signed out']);
    }
}
