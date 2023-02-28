<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        return response()->json(['profile' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ProfileUpdateRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ProfileUpdateRequest $request)
    {
        $data = $request->validated();

        /** @var User $user */
        $user = Auth::user();

        $user->fill($data);
        $user->save();


        return response()->json(['profile' => $user]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy()
    {
        /** @var User $user */
        $user = Auth::user();

        $user->delete();

        return response()->json(['message' => 'profile successfully deleted']);
    }
}
