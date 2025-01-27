<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Users;
use App\Models\VizsgaRatings;
use App\Models\VizsgaComments;
use App\Models\VizsgaLevel;
use App\Models\VizsgaShows;
use Exception;

class VizsgaremekController extends Controller
{
    //Robi álltal készített kód innentől indul
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

    /**
     * Regisztrációs oldal megjelenítése
     *
     * Regisztrációs űrlap nézet
     */
    public function Regisztracio()
    {
        return view('vizsgaremek.regisztracio');
    }

    /**
     * Főoldal megjelenítése
     *
     * @return view Főoldal nézet a felhasználói adatokkal
     */
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

    /**
     * Kijelentkezés kezelése
     *
     * Átirányítás a bejelentkezési oldalra
     */
    public function Kijelentkezes()
    {
        Auth::logout();
        return redirect('/vizsgaremek/login');
    }

    /**
     * Bejelentkezési oldal megjelenítése
     *
     * Bejelentkezési űrlap nézet
     */
    public function Login()
    {
        return view('vizsgaremek.login');
    }

    /**
     * Bejelentkezés kezelése
     *
     * A bejelentkezési űrlap adatai
     * Átirányítás a főoldalra sikeres bejelentkezés esetén
     */
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

    /**
     * Elfelejtett jelszó oldal megjelenítése
     *
     * Elfelejtett jelszó űrlap nézet
     */
    public function ElfelejtettJelszo()
    {
        return view('vizsgaremek.elfelejtett-jelszo');
    }

    /**
     * Elfelejtett jelszó kezelése
     *
     * Az elfelejtett jelszó űrlap adatai
     * Visszairányítás az előző oldalra sikerességi üzenettel
     */
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

    /**
     * Jelszó változtatás oldal megjelenítése
     *
     * Jelszó változtatás űrlap nézet
     */
    public function JelszoValtoztatas()
    {
        $useradat = Auth::user();
        $user = ['name' => $useradat->name];
        return view('vizsgaremek.jelszo-valtoztatas', compact('user'));
    }

    /**
     * Jelszó változtatás kezelése
     *
     * A jelszó változtatás űrlap adatai
     * Átirányítás a főoldalra sikeres jelszó változtatás esetén
     */
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

        // Sikeresen megváltoztatott jelszó email
        $message = 'Kedves ' . $user->name . "!\n\n";
        $message .= "A jelszavad sikeresen megváltozott.\n";
        $message .= "Ha nem te kezdeményezted ezt a változtatást, kérjük azonnal lépj kapcsolatba velünk.\n\n";
        $message .= "Üdvözlettel,\nVizsgaremek";

        mail($user->email, 'Jelszó sikeresen megváltozott', $message);

        return redirect('/vizsgaremek/fooldal')->with('status', 'A jelszó sikeresen megváltozott!');
    }

    /**
     * Név változtatás kezelése
     *
     * A név változtatás űrlap adatai
     * Sikeres név változtatás visszajelzése
     */
    public function NevValtoztatas(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $useradat = Auth::user();

        // Ellenőrizzük, hogy létezik-e már ilyen nevű felhasználó
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
            // Ha nincs ilyen név, akkor frissítjük
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

    /**
     * Profil oldal megjelenítése
     *
     * Profil nézet a felhasználói adatokkal és műsorokkal
     */
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

    /**
     * Feltöltés oldal megjelenítése
     *
     * Feltöltés űrlap nézet
     */
    public function FeltoltesOldal()
    {
        $useradat = Auth::user();
        $user = ['name' => $useradat->name];
        return view('vizsgaremek.feltoltes', compact('user'));
    }

    /**
     * Műsorok feltöltésének kezelése
     *
     * A feltöltési űrlap adatai
     * Visszairányítás az előző oldalra sikerességi üzenettel
     */
    public function FeltoltesKezelo(Request $request)
    {
        $data = $request->all();
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,webp|max:2048',
            'title' => 'required|string|max:200',
            'description' => 'required|string',
            'category' => 'required|in:film,sorozat',
        ]);

        if ($request->file()) {
            $fileName = time() . '_' . $request->file->getClientOriginalName();
            $destinationPath = public_path('uploads/vizsgaremek/');
            $request->file('file')->move($destinationPath, $fileName);

            $upload = new VizsgaShows();
            $upload->title = $data['title'];
            $upload->description = $data['description'];
            $upload->image_url = $fileName;
            $upload->category = $data['category'];
            $upload->user_id = auth()->id();
            $upload->save();

            return back()->with('success', 'Fájl sikeresen feltöltve.');
        }
        return back()->withErrors('A fájl feltöltése nem sikerült.');
    }

    /**
     * Összes műsor listázása
     *
     * Műsorok listázása nézet a felhasználói adatokkal
     */
    public function Musorok()
    {
        $useradat = Auth::user();
        $musorok = VizsgaShows::join('user', 'shows.user_id', '=', 'user.id')->select('shows.*', 'user.name as feltolto_neve')->orderBy('created_at', 'desc')->get();

        $user = ['name' => $useradat->name];
        return view('vizsgaremek.musorok', compact('user', 'musorok'));
    }

    /**
     * Kiválasztott műsorok törlése
     *
     * A törlendő műsorok azonosítói
     * Sikeres törlés visszajelzése
     */
    public function MusorTorles(Request $request)
    {
        $musor_ids = $request->input('musor_ids', []);

        $musorok = VizsgaShows::whereIn('id', $musor_ids)->where('user_id', auth()->id())->get();

        foreach ($musorok as $musor) {
            // Kép törlés
            $imagePath = public_path('uploads/vizsgaremek/' . $musor->image_url);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            // Műsor törlés
            $musor->delete();
        }

        return response()->json(['success' => true]);
    }

    /**
     * Műsor adatainak szerkesztése
     *
     * A módosított műsor adatai
     * Sikeres módosítás visszajelzése
     */
    public function MusorSzerkesztes(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:vizsgaremek_musorok,id',
            'title' => 'required|string|max:200',
            'leiras' => 'required|string',
            'kategoria' => 'required|in:film,sorozat',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $musor = VizsgaShows::where('id', $request->id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $musor->title = $request->title;
        $musor->leiras = $request->leiras;
        $musor->kategoria = $request->kategoria;

        if ($request->hasFile('file')) {
            // Kép törlés
            $oldImagePath = public_path('uploads/vizsgaremek/' . $musor->image_url);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }

            // Új kép feltöltés
            $fileName = time() . '_' . $request->file->getClientOriginalName();
            $request->file('file')->move(public_path('uploads/vizsgaremek/'), $fileName);
            $musor->image_url = $fileName;
        }

        $musor->save();

        return response()->json([
            'success' => true,
            'message' => 'Műsor sikeresen frissítve!',
        ]);
    }

    //Robi álltal készített kód idáig tart

    //Máté álltal készített kód innentől indul
    /**
     * Egy műsor részletes megtekintése
     *
     * $id A megtekintendő műsor azonosítója
     * Részletes nézet a műsor adataival és hozzászólásokkal
     *
     * findORFail Ez biztonságosabb mint a sima find() metódus, mert:
     *
     *  Ha nem létezik a műsor az adott ID-val, akkor nem null-t ad vissza
     *  helyette kivételt dob, amit Laravel automatikusan kezel
     *  a felhasználó 404-es (Not Found) hibaoldalt kap válaszként
     *  nem kell külön ellenőrizni, hogy létezik-e a műsor
     *
     *
     */

    public function MusorMegtekint($id)
    {
        $musor = VizsgaShows::findOrFail($id);
        $hozzaszolasok = VizsgaremekHozzaszolas::where('musor_id', $id)
            ->orderBy('created_at', 'desc')
            ->with('user')
            ->get();
        
        return view('vizsgaremek.tovabbinezet', compact('musor', 'hozzaszolasok'));
    }
    

    /**
     * Műsor értékelésének kezelése
     *
     * Az értékelés adatai (műsor_id, értékelés)
     * Visszairányítás az előző oldalra sikerességi üzenettel
     */
    public function MusorErtekeles(Request $request)
    {
        $request->validate([
            'musor_id' => 'required|exists:vizsgaremek_musorok,id',
            'ertekeles' => 'required|in:1,2,3,4,5',
        ]);
        $ertekeles = VizsgaremekErtekeles::where('user_id', auth()->id())
            ->where('musor_id', $request->musor_id)
            ->first();

        if ($ertekeles) {
            $ertekeles->ertekeles = $request->ertekeles;
            $ertekeles->save();
        } else {
            $ertekeles = new VizsgaremekErtekeles();
            $ertekeles->musor_id = $request->musor_id;
            $ertekeles->ertekeles = $request->ertekeles;
            $ertekeles->user_id = auth()->id();
            $ertekeles->save();
        }
        $averageRating = VizsgaremekErtekeles::where('musor_id', $request->musor_id)->avg('ertekeles');

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
    
        $hozzaszolas = new VizsgaremekHozzaszolas();
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
    
        $comment = VizsgaremekHozzaszolas::findOrFail($hozzaszolas_id);
        
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    
        $comment->hozzaszolas = $validated['hozzaszolas'];
        $comment->save();
    
        return response()->json(['message' => 'Komment sikeresen módosítva']);
    }

    public function KommentTorles(Request $request, $hozzaszolas_id)
    {
        $comment = VizsgaremekHozzaszolas::findOrFail($hozzaszolas_id);
        
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $comment->delete();

        return response()->json(['message' => 'Komment sikeresen törölve']);
    }
}
