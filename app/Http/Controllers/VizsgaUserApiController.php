<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\VizsgaShows;
use App\Models\VizsgaRatings;
use App\Models\VizsgaComments;
use App\Models\VizsgaLevel;
use App\Models\VizsgaWatchlist;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;

class VizsgaUserApiController extends Controller{
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
        // $request->user()->tokens()->delete();
        return response([
            "message" => "Success"
        ])->withCookie($cookie);
    }

    public function User()
    {
        return Auth::user();
    }

    public function ForgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->input('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $randomPassword = Str::random(10);
        
        $user->password = Hash::make($randomPassword);
        $user->save();

        $emailContent = "Dear {$user->name},\n\n";
        $emailContent .= "Your password has been reset. Here is your new password: {$randomPassword}\n\n";
        $emailContent .= "We recommend changing this password after logging in for security purposes.\n\n";
        $emailContent .= "If you did not request this password reset, please contact us immediately.\n\n";
        $emailContent .= "MovieTreasure";
        
        $mailSent = mail($email, "Password Reset", $emailContent);

        if ($mailSent) {
            return response()->json([
                'success' => true,
                'message' => "Password has been reset and sent to your email, new pass: $randomPassword"
            ]);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'Could not send email, but password was reset',
                'newPassword' => $randomPassword
            ]);
        }
    }

    public function NewPassword(Request $request)
    {
        $request->validate([
            'oldPassword' => 'required',
            'newPassword' => 'required',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->input('oldPassword'), $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Old password is incorrect'
            ], 401);
        }

        $user->password = Hash::make($request->input('newPassword'));
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully'
        ]);
    }

}