<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TokenController extends Controller
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

        $tokens = $user->tokens()->get(['id', 'name', 'last_used_at']);

        return response()->json(['tokens' => $tokens]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        /** @var User $user */
        $user = Auth::user();

        $user->tokens()->where('id', $id)->delete();

        return response()->json(['message' => 'token successfully revoked']);
    }
}
