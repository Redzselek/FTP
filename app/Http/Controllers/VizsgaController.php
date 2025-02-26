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
use Illuminate\Support\Facades\Cookie;

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

        mail($user->email, $user->name . ' regisztráció sikeres volt', 'Sikeres regisztráció!"');

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Sikeres regisztráció',
            'data' => [
                'user' => $user,
                'token' => $token
            ]
        ], 201);
    }

    public function Bejelentkezes(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'A megadott hitelesítő adatok nem egyeznek.'
            ], 401);
        }

        $token = $user->createToken('token')->plainTextToken;
        $cookie = cookie('jwt', $token,  24 * 60); // 1 day

        return response()->json([
            'status' => 'success',
            'message' => 'Sikeres bejelentkezés',
            'token' => $token,
            'user' => $user
        ])->withCookie($cookie);
    }

    public function Kijelentkezes()
    {
        $cookie = Cookie::forget('jwt');
        // auth()->user()->tokens()->delete();
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Sikeres kijelentkezés'
        ])->withCookie($cookie);
        
    }

    public function Dashboard()
    {
        $user = Auth::user();
        $shows = VizsgaShows::all();

        return response()->json([
            'status' => 'success',
            'data' => [
                'user' => $user ? ['name' => $user->name, 'email' => $user->email] : null,
                'shows' => $shows
            ]
        ]);
    }

    public function UploadManager(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,webp|max:15360',
            'title' => 'required|string',
            'description' => 'required|string',
            'category' => 'required|string',
            'type' => 'required|string'
        ]);

        if ($request->file()) {
            $fileName = time() . '_' . $request->file->getClientOriginalName();
            $destinationPath = public_path('uploads/vizsga');
            $request->file('file')->move($destinationPath, $fileName);
            
            $useradat = Auth::user();
            $upload = new VizsgaShows();
            $upload->title = $request->title;
            $upload->description = $request->description;
            $upload->category = $request->category;
            $upload->type = $request->type;
            $upload->image_url = $fileName;
            $upload->user_id = $useradat->id;
            $upload->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Fájl sikeresen feltöltve',
                'data' => $upload
            ], 201);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'A fájl feltöltése nem sikerült'
        ], 400);
    }

    public function JelszoValtoztatas(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|different:current_password',
            'confirm_password' => 'required|same:new_password'
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'A jelenlegi jelszó nem megfelelő'
            ], 400);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Jelszó sikeresen megváltoztatva'
        ]);
    }

    public function ElfelejtettJelszo(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $newPassword = Str::random(12);
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($newPassword);
        $user->save();

        $emailContent = "Kedves {$user->name}!\n\n"
            . "Az új ideiglenes jelszavad: {$newPassword}\n"
            . "Kérjük, jelentkezz be ezzel a jelszóval, majd változtasd meg a profilodban.\n\n"
            . "Üdvözlettel,\nA rendszer";

        mail($request->email, 'Új ideiglenes jelszó', $emailContent);

        return response()->json([
            'status' => 'success',
            'message' => 'Az új jelszót elküldtük az email címére'
        ]);
    }

    public function FiokTorles()
    {
        $user = Auth::user();
        
        VizsgaRatings::where('user_id', $user->id)->delete();
        VizsgaComments::where('user_id', $user->id)->delete();
        // VizsgaWatchlist::where('user_id', $user->id)->delete();
        // VizsgaLevel::where('user_id', $user->id)->delete();
        
        mail($user->email, 'Fiók törlése sikeres volt', 'Sikeresen töröltük a fiókat!');
        
        $user->tokens()->delete();
        $user->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Fiók sikeresen törölve'
        ]);
    }

    public function FeltoltesekView(Request $request)
    {
        $jwt = Cookie::get('jwt');
        if (!$jwt) {
            return response()->json([
                'status' => 'error',
                'message' => 'Nem vagy bejelentkezve'
            ], 401);
        }

        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Érvénytelen hitelesítés'
            ], 401);
        }

        $shows = VizsgaShows::where('user_id', $user->id)->get();
        return response()->json([
            'status' => 'success',
            'data' => [
                'shows' => $shows
            ]
        ]);
    }

    public function ShowSzerkesztes(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:vizsga_shows,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'type' => 'required|string'
        ]);

        $show = VizsgaShows::findOrFail($request->id);
        
        if ($show->user_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Nem jogosult a művelet végrehajtására'
            ], 403);
        }

        $show->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Show sikeresen frissítve',
            'data' => $show
        ]);
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

        return response()->json([
            'status' => 'success',
            'message' => 'Show sikeresen törölve'
        ]);
    }

    public function MoviesView(Request $request)
    {
        $movies = VizsgaShows::where('type', 'film')->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'movies' => $movies
            ]
        ]);
    }

    public function SeriesView(Request $request)
    {
        $series = VizsgaShows::where('type', 'sorozat')->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'series' => $series
            ]
        ]);
    }

    public function WatchlistView(Request $request)
    {
        $watchlist = VizsgaWatchlist::with('show')
            ->where('user_id', Auth::id())
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'watchlist' => $watchlist
            ]
        ]);
    }

    public function user(Request $request){
        Auth::check();
        return response()->json([
            'data' => Auth::check(),
            'aas' => Auth::user(),
        ]);
    }
}
