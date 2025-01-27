<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestAPIController;
use App\Http\Controllers\VizsgaremekController;
use App\Http\Controllers\BingoController;

Route::controller(TestAPIController::class)->group(function () { 
    Route::get('/testapi','TestAPI')->name('testapi');
});


Route::controller(BingoController::class)->group(function () {
    Route::get('/bingo/bingo','bingo')->name('bingo');
});

Route::controller(VizsgaremekController::class)->group(function () {
    Route::get('/vizsgaremek/regisztracio', 'Regisztracio')->name('regisztracio');
    Route::post('/vizsgaremek/regisztralas', 'Regisztralas')->name('regisztralas');
    Route::get('/vizsgaremek/login', 'Login')->name('login');
    Route::post('/vizsgaremek/bejelentkezes', 'Bejelentkezes')->name('bejelentkezes');
    Route::get('/vizsgaremek/elfelejtett-jelszo', 'ElfelejtettJelszo')->name('password.request');
    Route::post('/vizsgaremek/elfelejtett-jelszo', 'ElfelejtettJelszoKuldes')->name('password.email');
    Route::get('/vizsgaremek/fooldal', 'Fooldal')->name('fooldal');
});

Route::group(['middleware' => 'auth'], function () {
    Route::controller(VizsgaremekController::class)->group(function () {
        Route::get('/teliprojekt/kijelentkezes', 'Kijelentkezes')->name('kijelentkezes');
        Route::get('/teliprojekt/jelszo-valtoztatas', 'JelszoValtoztatas')->name('valtoztatas');
        Route::post('/teliprojekt/jelszo-valtoztatas', 'JelszoValtoztatasMentes')->name('valtoztatas-mentes');
        Route::get('/teliprojekt/profil', 'Profil')->name('profil');
        Route::post('/teliprojekt/profil/nev-valtoztatas', 'NevValtoztatas')->name('nev.valtoztatas');
    });
});

//Route kontrollerekkel