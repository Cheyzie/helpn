<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SignupController extends Controller
{
    /**
     * Display a login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('signup');
    }

    public function create(Request $request)
    {
        $userdata = $request->validate([
            'name' => ['required'],
            'email' => ['email', 'required', 'unique:users,email'],
            'password' => ['required'],
            'confirmation' => ['required', 'same:password']
        ]);

        $user = new User();

        $user->name = $userdata['name'];
        $user->email = $userdata['email'];
        $user->password = Hash::make($userdata['password']);

        $user->save();
        return redirect('/');
    }
}
