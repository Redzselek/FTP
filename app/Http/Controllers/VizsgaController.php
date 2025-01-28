<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\VizsgaShows;
use App\Models\VizsgaRatings;
use App\Models\VizsgaComments;
use App\Models\VizsgaLevel;
use App\Models\VizsgaWatchlist;
use Exception;

class VizsgaController extends Controller
{
    public function Regisztralas (Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        Auth::login($user);

        mail($user->email, $user->name . ' regisztráció sikeres volt', 'Sikeres regisztráció!"');
        return redirect('/vizsga/dashboard');
    }

    public function Regisztracio()
    {
        return view('vizsga.register');
    }

    public function Kijelentkezes()
    {
        Auth::logout();
        return redirect('/vizsga/login');
    }

    public function Login()
    {
        return view('vizsga.login');
    }

    public function Bejelentkezes(Request $request)
    { 
        $email = $request->input('email');
        $password = $request->input('password');
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $request->session()->regenerate();
            return redirect('/vizsga/dashboard');
        }
        return back()->withErrors([
            'email' => 'A megadott hitelesítő adatok nem egyeznek.',
        ]);
    }
    public function Dashboard()
    {
        $useradat = Auth::user();
        $user = null;
        if ($useradat) {
            $user = ['name' => $useradat->name, 'email' => $useradat->email];
        }
        return view('vizsga.dashboard', [
            'user' => $user
        ]);
    }
}