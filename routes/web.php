<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestAPIController;
use App\Http\Controllers\VizsgaremekController;
use App\Http\Controllers\BingoController;
use App\Http\Controllers\VizsgaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SanctumTestController;

// Sanctum Authentication Routes
Route::get('/sanctum/login', [SanctumTestController::class, 'showLoginForm'])->name('login');
Route::post('/sanctum/login', [SanctumTestController::class, 'login']);
Route::get('/sanctum/dashboard', [SanctumTestController::class, 'dashboard'])->middleware('auth:sanctum')->name('dashboard');
Route::post('/sanctum/logout', [SanctumTestController::class, 'logout'])->middleware('auth:sanctum');

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
    Route::get('/vizsga/regisztracio', 'Regisztracio')->name('vizsga.regisztracio');
    Route::post('/vizsga/regisztralas', 'Regisztralas')->name('vizsga.regisztralas');
    Route::get('/vizsga/login', 'Bejelentkez')->name('vizsga.login');
    Route::post('/vizsga/bejelentkezes', 'Bejelentkezes')->name('vizsga.bejelentkezes');
    Route::get('/vizsga/kijelentkezes', 'Kijelentkezes')->name('vizsga.kijelentkezes');

});

Route::middleware(['auth'])->group(function () {
    Route::controller(VizsgaController::class)->group(function () {
        Route::get('/vizsga/dashboard', 'Dashboard')->name('vizsga.dashboard');
        Route::get('/vizsga/upload', 'Upload')->name('vizsga.upload.form');
        Route::post('/vizsga/uploadmanager', 'UploadManager')->name('vizsga.uploadManager');
    });
});

// Sanctum test routes
Route::get('/sanctum', [SanctumTestController::class, 'showLoginForm'])->name('sanctum.login.form');
Route::post('/sanctum-test/login', [SanctumTestController::class, 'login'])->name('sanctum.login');

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/sanctum-test/dashboard', [SanctumTestController::class, 'dashboard'])->name('sanctum.dashboard');
    Route::post('/sanctum-test/logout', [SanctumTestController::class, 'logout'])->name('sanctum.logout');
});