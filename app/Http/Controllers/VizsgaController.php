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
    public function Regisztralas(Request $request)
    {
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

    public function Bejelentkez()
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
        return view('vizsga.dashboard', ['user' => $user]);
    }

    public function Upload()
    {
        return view('vizsga.upload');
    }

    public function UploadManager(Request $request)
    {
        $data = $request->all();
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,webp|max:15360',
        ]);
        if ($request->file()) {
            $fileName = time() . '_' . $request->file->getClientOriginalName();
            $destinationPath = public_path('uploads/vizsga');
            $request->file('file')->move($destinationPath, $fileName);
            
            $useradat = Auth::user();
            $upload = new VizsgaShows();
            $upload->title = $data['title'];
            $upload->description = $data['description'];
            $upload->category = $data['category'];
            $upload->type = $data['type'];
            $upload->image_url = $fileName;
            $upload->user_id = $useradat->id;
            $upload->save();

            return back()->with('success', 'Fájl sikeresen feltöltve.');
        }
        return back()->withErrors('A fájl feltöltése nem sikerült.');
    }
}