<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
// use App\Models\User;
// use Illuminate\Support\Facades\Hash;
// use Illuminate\Validation\ValidationException;
// use App\Http\Middleware\Authenticate;

// class SanctumTestController extends Controller
// {
//     public function showLoginForm()
//     {
//         return view('sanctum-test.login');
//     }

//     public function register(Request $request)
//     {
//         $request->validate([
//             'name' => 'required|string|max:255',
//             'email' => 'required|string|email|max:255|unique:users',
//             'password' => 'required|string|min:8',
//         ]);

//         $user = User::create([
//             'name' => $request->name,
//             'email' => $request->email,
//             'password' => Hash::make($request->password),
//         ]);

//         $token = $user->createToken('auth-token')->plainTextToken;

//         return response()->json([
//             'token' => $token,
//             'user' => $user
//         ], 201);
//     }

//     public function login(Request $request)
//     {
//         $request->validate([
//             'email' => 'required|email',
//             'password' => 'required'
//         ]);

//         $user = User::where('email', $request->email)->first();

//         if (!$user || !Hash::check($request->password, $user->password)) {
//             throw ValidationException::withMessages([
//                 'email' => ['A megadott adatok helytelenek.'],
//             ]);
//         }

//         $token = $user->createToken('auth-token')->plainTextToken;

//         return response()->json([
//             'token' => $token,
//             'user' => $user
//         ]);
//     }

//     public function user(Request $request)
//     {
//         return response()->json($request->user());
//     }

//     public function logout(Request $request)
//     {
//         $request->user()->currentAccessToken()->delete();
        
//         return response()->json(['message' => 'Logged out successfully']);
//     }

//     public function dashboard()
//     {
//         if (!Auth::guard('web')->check()) {
//             return redirect()->route('login');
//         }
//         return view('sanctum-test.dashboard');
//     }

//     public function testAuth()
//     {
//         return response()->json([
//             'message' => 'You are authenticated!',
//             'user' => auth()->user()
//         ]);
//     }
// }
