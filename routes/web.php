<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestAPIController;
use App\Http\Controllers\VizsgaremekController;
use App\Http\Controllers\BingoController;
use App\Http\Controllers\VizsgaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SanctumTestController;


Route::controller(TestAPIController::class)->group(function () { 
    Route::get('/testapi','TestAPI')->name('testapi');
});

Route::controller(BingoController::class)->group(function () {
    Route::get('/bingo/bingo','bingo')->name('bingo');
});

// Route::controller(VizsgaremekController::class)->group(function () {
//     Route::get('/vizsgaremek/regisztracio', 'Regisztracio')->name('regisztracio');
//     Route::post('/vizsgaremek/regisztralas', 'Regisztralas')->name('regisztralas');
//     Route::get('/vizsgaremek/login', 'Login')->name('login');
//     Route::post('/vizsgaremek/bejelentkezes', 'Bejelentkezes')->name('bejelentkezes');
//     Route::get('/vizsgaremek/elfelejtett-jelszo', 'ElfelejtettJelszo')->name('password.request');
//     Route::post('/vizsgaremek/elfelejtett-jelszo', 'ElfelejtettJelszoKuldes')->name('password.email');
//     Route::get('/vizsgaremek/fooldal', 'Fooldal')->name('fooldal');
// });

// Route::middleware(['auth'])->group(function () {
//     Route::controller(VizsgaremekController::class)->group(function () {
//         Route::get('/vizsgaremek/kijelentkezes', 'Kijelentkezes')->name('kijelentkezes');
//         Route::get('/vizsgaremek/jelszo-valtoztatas', 'JelszoValtoztatas')->name('valtoztatas');
//         Route::post('/vizsgaremek/jelszo-valtoztatas', 'JelszoValtoztatasMentes')->name('valtoztatas-mentes');
//         Route::get('/vizsgaremek/profil', 'Profil')->name('profil');
//         Route::post('/vizsgaremek/profil/nev-valtoztatas', 'NevValtoztatas')->name('nev.valtoztatas');
//         // CRUD műveletek
//         Route::get('/vizsgaremek/feltoltes', 'FeltoltesOldal')->name('feltoltesoldal');
//         Route::post('/vizsgaremek/feltoltes', 'FeltoltesKezelo')->name('feltoltes');
//         Route::get('/vizsgaremek/musorok', 'Musorok')->name('musorok');
//         Route::get('/vizsgaremek/musorok/tovabbinezet/{id}', 'MusorMegtekint')->name('musor.megtekint');
//         Route::post('/vizsgaremek/musor-szerkesztes', 'MusorSzerkesztes')->name('musor.szerkesztes');
//         Route::delete('/vizsgaremek/musor-torles', 'MusorTorles')->name('musor.torles');
//         Route::post('/vizsgaremek/musorok/ertekeles/{musor_id}', 'MusorErtekeles')->name('musor.ertekeles');
//         Route::post('/vizsgaremek/musorok/hozzaszolas/{musor_id}', 'Hozzaszolas')->name('hozzaszolas');
//         Route::post('/vizsgaremek/musorok/hozzaszolas/szerkesztes/{hozzaszolas_id}', 'KommentSzerkesztes')->name('hozzaszolas.szerkesztes');
//         Route::post('/vizsgaremek/musorok/hozzaszolas/torles/{hozzaszolas_id}', 'KommentTorles')->name('hozzaszolas.torles');
//         Route::post('/vizsgaremek/musor/{musor_id}/hozzaszolas', [VizsgaremekController::class, 'Hozzaszolas'])->name('hozzaszolas.store')->middleware('auth');        
//     });
// });

Route::controller(VizsgaController::class)->group(function () {
    // Nyilvános útvonalak
    Route::get('/vizsga/login', 'Bejelentkez')->name('vizsga.bejelentkez');
    Route::post('/vizsga/login', 'Bejelentkezes')->name('vizsga.bejelentkezes');
    Route::get('/vizsga/register', 'Regisztracio')->name('vizsga.regisztracio');
    Route::post('/vizsga/register', 'Regisztralas')->name('vizsga.regisztralas');
    Route::get('/vizsga/forgot-password', 'ElfelejtettJelszoView')->name('vizsga.password.request');
    Route::post('/vizsga/forgot-password', 'ElfelejtettJelszo')->name('vizsga.password.email');
    Route::get('/vizsga/movies', 'MoviesView')->name('vizsga.movies');
    Route::get('/vizsga/series', 'SeriesView')->name('vizsga.series');

    // Védett útvonalak
    Route::middleware(['auth'])->group(function () {
        Route::get('/vizsga/dashboard', 'Dashboard')->name('vizsga.dashboard');
        Route::post('/vizsga/logout', 'Kijelentkezes')->name('vizsga.kijelentkezes');
        Route::get('/vizsga/profile', 'ProfilView')->name('vizsga.profile');
        Route::post('/vizsga/profile/password', 'JelszoValtoztatas')->name('vizsga.password');
        Route::post('/vizsga/profile/delete', 'FiokTorles')->name('vizsga.profile.delete');
        Route::get('/vizsga/upload', 'Upload')->name('vizsga.upload.form');
        Route::post('/vizsga/uploadmanager', 'UploadManager')->name('vizsga.uploadManager');
        Route::get('/vizsga/feltoltesek', 'FeltoltesekView')->name('vizsga.feltoltesek');
        Route::post('/vizsga/show/edit', 'ShowSzerkesztes')->name('vizsga.show.edit');
        Route::post('/vizsga/show/delete', 'ShowTorles')->name('vizsga.show.delete');
        Route::get('/vizsga/watchlist', 'WatchlistView')->name('vizsga.watchlist');
    });
});

Route::post('/api/register', [AuthController::class, 'register']);
Route::post('/api/login', [AuthController::class, 'login']);
Route::post('/api/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');