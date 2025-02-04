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
use Illuminate\Support\Facades\DB;

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
        $shows = VizsgaShows::all();
        return view('vizsga.dashboard', ['user' => $user, 'shows' => $shows]);
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

    public function ProfilView()
    {
        $user = Auth::user();
        return view('vizsga.profile', ['user' => $user]);
    }

    public function JelszoValtoztatas(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|different:current_password',
            'confirm_password' => 'required|same:new_password'
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'A jelenlegi jelszó nem megfelelő.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect('/vizsga/profile')->with('success', 'Jelszó sikeresen megváltoztatva.');
    }

    public function ElfelejtettJelszoView()
    {
        return view('vizsga.forgot-password');
    }

    public function ElfelejtettJelszo(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        // Új jelszó generálása
        $newPassword = Str::random(12);

        // Felhasználó keresése és jelszó frissítése
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($newPassword);
        $user->save();

        // Email küldése az új jelszóval
        $emailContent = "Kedves {$user->name}!\n\n"
            . "Az új ideiglenes jelszavad: {$newPassword}\n"
            . "Kérjük, jelentkezz be ezzel a jelszóval, majd változtasd meg a profilodban.\n\n"
            . "Üdvözlettel,\nA rendszer";

        mail($request->email, 'Új ideiglenes jelszó', $emailContent);

        return back()->with('status', 'Az új jelszót elküldtük az email címedre!');
    }

    public function FiokTorles()
    {
        $user = Auth::user();
        
        // Felhasználóhoz kapcsolódó adatok törlése
        VizsgaRatings::where('user_id', $user->id)->delete();
        VizsgaComments::where('user_id', $user->id)->delete();
        VizsgaWatchlist::where('user_id', $user->id)->delete();
        VizsgaLevel::where('user_id', $user->id)->delete();
        mail($user->email, 'Fiók törlése sikeres volt', 'Sikeresen töröltük a fiókat!');
        // Felhasználó törlése
        $user->delete();
        
        Auth::logout();
        return redirect('/vizsga/login')->with('status', 'Fiók sikeresen törölve.');
    }

    public function FeltoltesekView()
    {
        $user = Auth::user();
        $shows = VizsgaShows::where('user_id', $user->id)->get();
        return view('vizsga.feltoltesek', ['shows' => $shows]);
    }

    public function ShowSzerkesztes(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:vizsga_shows,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'type' => 'required|string'
        ]);

        $show = VizsgaShows::findOrFail($request->id);
        
        // Ellenőrizzük, hogy a felhasználó tulajdonosa-e a shownak
        if ($show->user_id !== Auth::id()) {
            return response()->json(['error' => 'Nem vagy jogosult szerkeszteni ezt a tartalmat.'], 403);
        }

        $show->title = $request->title;
        $show->description = $request->description;
        $show->category = $request->category;
        $show->type = $request->type;

        if ($request->hasFile('file')) {
            $request->validate([
                'file' => 'file|mimes:jpg,jpeg,png,webp|max:15360',
            ]);

            // Régi kép törlése
            if ($show->image_url) {
                $oldImagePath = public_path('uploads/vizsga/' . $show->image_url);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // Új kép feltöltése
            $fileName = time() . '_' . $request->file->getClientOriginalName();
            $request->file('file')->move(public_path('uploads/vizsga'), $fileName);
            $show->image_url = $fileName;
        }

        $show->save();
        return response()->json(['success' => true, 'show' => $show]);
    }

    public function ShowTorles(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:vizsga_shows,id'
        ]);

        $user_id = Auth::id();
        $shows = VizsgaShows::whereIn('id', $request->ids)
                            ->where('user_id', $user_id)
                            ->get();

        foreach ($shows as $show) {
            // Kép törlése
            if ($show->image_url) {
                $imagePath = public_path('uploads/vizsga/' . $show->image_url);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            $show->delete();
        }

        return response()->json(['success' => true]);
    }

    public function MoviesView()
    {
        $movies = VizsgaShows::where('type', 'film')->get();
        return view('vizsga.movies', ['shows' => $movies]);
    }

    public function SeriesView()
    {
        $series = VizsgaShows::where('type', 'sorozat')->get();
        return view('vizsga.series', ['shows' => $series]);
    }

    public function WatchlistView()
    {
        return view('vizsga.watchlist');
    }
}
