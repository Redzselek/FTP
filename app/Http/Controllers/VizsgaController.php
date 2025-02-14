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
        try {
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
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Regisztráció sikertelen',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function Bejelentkezes(Request $request)
    {
        try {
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

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Sikeres bejelentkezés',
                'data' => [
                    'user' => $user,
                    'token' => $token
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bejelentkezés sikertelen',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function Kijelentkezes()
    {
        try {
            auth()->user()->tokens()->delete();
            Auth::logout();

            return response()->json([
                'status' => 'success',
                'message' => 'Sikeres kijelentkezés'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kijelentkezés sikertelen',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function Dashboard()
    {
        try {
            $user = Auth::user();
            $shows = VizsgaShows::all();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'user' => $user ? ['name' => $user->name, 'email' => $user->email] : null,
                    'shows' => $shows
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Adatok lekérése sikertelen',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function UploadManager(Request $request)
    {
        try {
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
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Feltöltés sikertelen',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function JelszoValtoztatas(Request $request)
    {
        try {
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
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jelszó módosítása sikertelen',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function ElfelejtettJelszo(Request $request)
    {
        try {
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
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jelszó visszaállítás sikertelen',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function FiokTorles()
    {
        try {
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
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Fiók törlése sikertelen',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function FeltoltesekView()
    {
        try {
            $user = Auth::user();
            $shows = VizsgaShows::where('user_id', $user->id)->get();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'shows' => $shows
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Feltöltések lekérése sikertelen',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function ShowSzerkesztes(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|exists:vizsga_shows,id',
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'category' => 'required|string',
                'type' => 'required|string'
            ]);

            $show = VizsgaShows::findOrFail($request->id);
            
            // Check if the user owns this show
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
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Show szerkesztése sikertelen',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function ShowTorles(Request $request)
    {
        try {
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
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Show törlése sikertelen',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function MoviesView(Request $request)
    {
        try {
            $movies = VizsgaShows::where('type', 'film')->get();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'movies' => $movies
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Filmek lekérése sikertelen',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function SeriesView(Request $request)
    {
        try {
            $series = VizsgaShows::where('type', 'sorozat')->get();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'series' => $series
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sorozatok lekérése sikertelen',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function WatchlistView(Request $request)
    {
        try {
            $watchlist = VizsgaWatchlist::with('show')
                ->where('user_id', Auth::id())
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'watchlist' => $watchlist
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Watchlist lekérése sikertelen',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
