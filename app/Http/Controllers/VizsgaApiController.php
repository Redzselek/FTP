<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;

class VizsgaApiController extends Controller{
    public function Register(Request $request)
    {
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' =>  Hash::make($request->input('password'))
        ]);

        return $user;
    }

    public function Login(Request $request)
    {
        if (!Auth::guard('web')->attempt($request->only("email", "password"))) {
            return response([
                'message' => 'Invalid credentials!'
            ], 401);
        }
        $user = Auth::user();

        $token = $user->createToken('token')->plainTextToken;
        $cookie = cookie('jwt', $token,  60 * 24);

        return response([
            'message' => $token
        ])->withCookie($cookie);
    }

    public function Logout(Request $request)
    {
        $cookie = Cookie::forget('jwt');
        auth()->guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->user()->tokens()->delete();
        return response([
            "message" => "Success"
        ])->withCookie($cookie);
    }

    public function User()
    {
        return Auth::user();
    }
}