<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserUpdateRequest;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', User::class);

        return response()->json(['users' => User::all()->makeVisible(['role'])]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  User $user
     * @return JsonResponse
     */
    public function show(User $user): JsonResponse
    {
        return response()->json(['user' => $user->makeVisible(['role'])]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UserUpdateRequest  $request
     * @param  User $user
     * @return JsonResponse
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $data = $request->validated();

        if (array_key_exists('role_id', $data)
            && $request->user()->cannot('changeRole', $user))
        {
            unset($data['role_id']);
        }

        $user->fill($data);
        $user->save();

        return response()->json(['user'=>$user]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return JsonResponse
     * @throws \Throwable
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json(['message'=>'user successfully deleted']);
    }
}
