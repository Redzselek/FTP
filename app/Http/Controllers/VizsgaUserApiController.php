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

    // HTML email sablon
    $emailContent = "
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                line-height: 1.6;
                color: #333;
            }
            .container {
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
                background-color: #f9f9f9;
                border-radius: 5px;
            }
            .header {
                background-color: #4a90e2;
                color: white;
                padding: 15px;
                text-align: center;
                border-radius: 5px 5px 0 0;
            }
            .content {
                padding: 20px;
                background-color: white;
                border-radius: 0 0 5px 5px;
            }
            .password-box {
                background-color: #f0f0f0;
                padding: 10px;
                text-align: center;
                font-size: 18px;
                margin: 15px 0;
                border-radius: 5px;
            }
            .password-box:hover {
                background-color: #e0e0e0;
            }
            .footer {
                text-align: center;
                font-size: 12px;
                color: #666;
                margin-top: 20px;
            }
            h4 {
                text-align: center;
            }
            a {
                color: #4a90e2;
                text-decoration: none;
                margin: 15px 0;
                border-radius: 5px;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>Password Reset</h2>
            </div>
            <div class='content'>
                <p>Dear {$user->name},</p>
                <p>Your password has been successfully reset. Here is your new temporary password:</p>
                <div class='password-box'>
                    <strong>{$randomPassword}</strong>
                </div>
                <p>For your security, we recommend changing this password immediately after logging in.</p>
                <h4><a href='https://egyedirobi.moriczcloud.hu/frontend/login' class='click'>Click here to log in.</a></h4>
            </div>
            <div class='footer'>
                <p>MovieTreasure - Your Movie Companion</p>
            </div>
        </div>
    </body>
    </html>";

    // Email fejléc beállítása HTML tartalomhoz
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: MovieTreasure <no-reply@movietreasure.com>" . "\r\n";

    $mailSent = mail($email, "Password Reset", $emailContent, $headers);

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
    function ShowMovies(Request $request) {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
        }
        
        $movies = VizsgaShows::where('type', 'movie')->where('user_id', $user->id)->get();
        return response()->json($movies);
    }

    function ShowSeries(Request $request) {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
        }
        
        $series = VizsgaShows::where('type', 'series')->where('user_id', $user->id)->get();
        return response()->json($series);
    }
}