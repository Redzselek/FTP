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

class VizsgaremekController extends Controller
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

        return redirect('/vizsgaremek/fooldal');
    }

    public function Regisztracio()
    {
        return view('vizsgaremek.regisztracio');
    }

    public function Fooldal()
    {
        $useradat = Auth::user();
        $user = null;
        if ($useradat) {
            $user = ['name' => $useradat->name];
        }

        // Get top 5 rated shows
        $topMusorok = VizsgaShows::select('id', 'title', 'image_url', 'user_rating')
            ->orderBy('user_rating', 'desc')
            ->take(6)
            ->get();
        $latestComments = VizsgaComments::with(['user', 'musor'])
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();
        return view('vizsgaremek.fooldal', [
            'user' => $user,
            'topMusorok' => $topMusorok,
            'latestComments' => $latestComments
        ]);
    }

    public function Kijelentkezes()
    {
        Auth::logout();
        return redirect('/vizsgaremek/login');
    }

    public function Login()
    {
        return view('vizsgaremek.login');
    }

    public function Bejelentkezes(Request $request)
    { 
        $email = $request->input('email');
        $password = $request->input('password');
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $request->session()->regenerate();
            return redirect('/vizsgaremek/fooldal');
        }
        return back()->withErrors([
            'email' => 'A megadott hitelesítő adatok nem egyeznek.',
        ]);
    }

    public function ElfelejtettJelszo()
    {
        return view('vizsgaremek.elfelejtett-jelszo');
    }

    public function ElfelejtettJelszoKuldes(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $newPassword = Str::random(10);
            $user->password = bcrypt($newPassword);
            $user->save();

            $message = "Kedves Felhasználó!\n\n";
            $message .= 'Az új jelszavad: ' . $newPassword . "\n\n";
            $message .= "Jelszavadat itt tudod megváltoztatni bejelentkezés után: https://egyedirobi.moriczcloud.hu/vizsgaremek/jelszo-valtoztatas\n\n";
            $message .= "Üdvözlettel,\n";
            $message .= 'Vizsgaremek';

            mail($request->email, 'Új jelszó', $message);

            return back()->with('status', 'Az új jelszót elküldtük az email címedre!');
        }
        return back()->withErrors(['email' => 'Nem található felhasználó ezzel az email címmel.']);
    }

    public function JelszoValtoztatas()
    {
        $useradat = Auth::user();
        $user = ['name' => $useradat->name];
        return view('vizsgaremek.jelszo-valtoztatas', compact('user'));
    }

    public function JelszoValtoztatasMentes(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|different:current_password',
            'new_password_confirmation' => 'required|same:new_password',
        ]);
        $user = Auth::user();
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'A jelenlegi jelszó nem megfelelő.']);
        }
        $user->password = bcrypt($request->new_password);
        $user->save();

        $message = 'Kedves ' . $user->name . "!\n\n";
        $message .= "A jelszavad sikeresen megváltozott.\n";
        $message .= "Ha nem te kezdeményezted ezt a változtatást, kérjük azonnal lépj kapcsolatba velünk.\n\n";
        $message .= "Üdvözlettel,\nVizsgaremek";

        mail($user->email, 'Jelszó sikeresen megváltozott', $message);

        return redirect('/vizsgaremek/fooldal')->with('status', 'A jelszó sikeresen megváltozott!');
    }

    public function NevValtoztatas(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $useradat = Auth::user();
        $existingUser = User::where('name', $request->name)
            ->where('id', '!=', $useradat->id)
            ->first();
        if ($existingUser) {
            return response()->json([
                'success' => false,
                'error' => 'Ez a név már foglalt!',
            ]);
        }
        try {
            User::where('id', $useradat->id)->update(['name' => $request->name]);
            return response()->json([
                'success' => true,
                'name' => $request->name,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Hiba történt a név mentése közben',
            ]);
        }
    }

    public function Profil()
    {
        $useradat = Auth::user();
        $musorok = VizsgaShows::where('user_id', $useradat->id)
            ->orderBy('created_at', 'desc')
            ->get();
        $user = [
            'name' => $useradat->name,
            'email' => $useradat->email,
            'date' => date('Y.m.d', strtotime($useradat->created_at)),
        ];
        return view('vizsgaremek.profil', compact('user', 'musorok'));
    }

    public function FeltoltesOldal()
    {
        $useradat = Auth::user();
        $user = ['name' => $useradat->name];
        return view('vizsgaremek.feltoltes', compact('user'));
    }

    public function FeltoltesKezelo(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'nullable|string',
            'category' => 'required|string|max:20',
            'type' => 'required|in:f,s',
            'image' => 'required|image|max:2048'
        ]);

        try {
            // Handle file upload
            $imagePath = $request->file('image')->store('uploads/vizsgaremek');
            $imageUrl = str_replace('public/', $imagePath);

            // Create new record
            $show = new VizsgaShows();
            $show->title = $validated['title'];
            $show->description = $validated['description'];
            $show->category = $validated['category'];
            $show->type = $validated['type'];
            $show->image_url = $imageUrl;
            $show->user_id = Auth::id();
            $show->user_rating = 0; // Default rating
            $show->save();

            return redirect()->back()->with('success', 'A műsor sikeresen feltöltve!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Hiba történt a feltöltés során: ' . $e->getMessage());
        }
    }

    public function Musorok()
    {
        $useradat = Auth::user();
        $musorok = VizsgaShows::join('users', 'shows.user_id', '=', 'users.id')
            ->select('shows.*', 'users.name as nev')
            ->orderBy('created_at', 'desc')
            ->get();

        $user = ['name' => $useradat->name];
        return view('vizsgaremek.musorok', compact('user', 'musorok'));
    }

    public function MusorTorles(Request $request)
    {
        $musor_ids = $request->input('musor_ids', []);

        $musorok = VizsgaShows::whereIn('id', $musor_ids)->where('user_id', auth()->id())->get();

        foreach ($musorok as $musor) {
            $imagePath = public_path('uploads/vizsgaremek/' . $musor->kep_url);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            $musor->delete();
        }

        return response()->json(['success' => true]);
    }

    public function MusorSzerkesztes(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:shows,id',
            'cim' => 'required|string|max:200',
            'leiras' => 'required|string',
            'kategoria' => 'required|in:film,sorozat',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $musor = VizsgaShows::where('id', $request->id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $musor->cim = $request->cim;
        $musor->leiras = $request->leiras;
        $musor->kategoria = $request->kategoria;

        if ($request->hasFile('file')) {
            $oldImagePath = public_path('uploads/vizsgaremek/' . $musor->kep_url);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
            $fileName = time() . '_' . $request->file->getClientOriginalName();
            $request->file('file')->move(public_path('uploads/vizsgaremek/'), $fileName);
            $musor->kep_url = $fileName;
        }

        $musor->save();

        return response()->json([
            'success' => true,
            'message' => 'Műsor sikeresen frissítve!',
        ]);
    }

    public function MusorMegtekint($id)
    {
        $musor = VizsgaShows::findOrFail($id);
        $hozzaszolasok = VizsgaComments::where('musor_id', $id)
            ->orderBy('created_at', 'desc')
            ->with('user')
            ->get();
        
        return view('vizsgaremek.tovabbinezet', compact('musor', 'hozzaszolasok'));
    }

    public function MusorErtekeles(Request $request)
    {
        $request->validate([
            'musor_id' => 'required|exists:shows,id',
            'ertekeles' => 'required|in:1,2,3,4,5',
        ]);

        $ertekeles = VizsgaRatings::where('user_id', auth()->id())
            ->where('musor_id', $request->musor_id)
            ->first();

        if ($ertekeles) {
            $ertekeles->ertekeles = $request->ertekeles;
            $ertekeles->save();
        } else {
            $ertekeles = new VizsgaRatings();
            $ertekeles->musor_id = $request->musor_id;
            $ertekeles->ertekeles = $request->ertekeles;
            $ertekeles->user_id = auth()->id();
            $ertekeles->save();
        }

        $averageRating = VizsgaRatings::where('musor_id', $request->musor_id)->avg('ertekeles');

        $musor = VizsgaShows::find($request->musor_id);
        $musor->user_rating = floor($averageRating); 
        $musor->save();

        return redirect()->back()->with('success', 'Értékelés sikeresen rögzítve!');
    }

    public function Hozzaszolas(Request $request, $musor_id)
    {
        $request->validate([
            'hozzaszolas' => 'required|string'
        ]);
    
        $hozzaszolas = new VizsgaComments();
        $hozzaszolas->hozzaszolas = $request->hozzaszolas;
        $hozzaszolas->user_id = Auth::id();
        $hozzaszolas->musor_id = $musor_id;
        $hozzaszolas->save();
    
        return redirect()->back()->with('success', 'Hozzászólás sikeresen elküldve!');
    }

    public function KommentSzerkesztes(Request $request, $hozzaszolas_id)
    {
        $validated = $request->validate([
            'hozzaszolas' => 'required|string'
        ]);
    
        $comment = VizsgaComments::findOrFail($hozzaszolas_id);
        
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    
        $comment->hozzaszolas = $validated['hozzaszolas'];
        $comment->save();
    
        return response()->json(['message' => 'Komment sikeresen módosítva']);
    }

    public function KommentTorles(Request $request, $hozzaszolas_id)
    {
        $comment = VizsgaComments::findOrFail($hozzaszolas_id);
        
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $comment->delete();

        return response()->json(['message' => 'Komment sikeresen törölve']);
    }
}