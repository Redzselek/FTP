<?php

use App\Http\Controllers\BingoController;
use App\Http\Controllers\BirdController;
use App\Http\Controllers\VizsgaController;
use App\Http\Controllers\VizsgaUserApiController;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

Route::post('/bingo/bingo', [BingoController::class, 'bingo'])->name('bingo');
Route::get('/bird', [BirdController::class, 'index']);



Route::get('frontend/{any?}', function () {
    return file_get_contents(public_path('frontend/index.csr.html'));
})->where('any', '.*');

Route::post('/vizsga-api/login', [VizsgaUserApiController::class, 'Login']);
Route::post('/vizsga-api/register', [VizsgaUserApiController::class, 'Register']);
Route::post('/vizsga-api/logout', [VizsgaUserApiController::class, 'Logout']);
Route::get('/vizsga-api/user', [VizsgaUserApiController::class, 'User']);

Route::post('/vizsga-api/upload', [VizsgaFileApiController::class, 'UploadManager']);















Route::post('/vizsga/login', [VizsgaController::class, 'Bejelentkezes'])->name('vizsga.bejelentkez');;
Route::post('/vizsga/register', [VizsgaController::class, 'Regisztralas']);
Route::post('/vizsga/forgot-password', [VizsgaController::class, 'ElfelejtettJelszo']);
Route::get('/vizsga/movies', [VizsgaController::class, 'MoviesView']);
Route::get('/vizsga/series', [VizsgaController::class, 'SeriesView']);
Route::get('/vizsga/dashboard', [VizsgaController::class, 'Dashboard']);
Route::post('/vizsga/feltoltesek', [VizsgaController::class, 'FeltoltesekView']);

Route::get('/vizsga/user/{id}', [VizsgaController::class, 'user']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/vizsga/logout', [VizsgaController::class, 'Kijelentkezes']);
    Route::post('/vizsga/profile/password', [VizsgaController::class, 'JelszoValtoztatas']);
    Route::post('/vizsga/profile/delete', [VizsgaController::class, 'FiokTorles']);
    Route::post('/vizsga/upload', [VizsgaController::class, 'UploadManager']);
    Route::post('/vizsga/show/edit', [VizsgaController::class, 'ShowSzerkesztes']);
    Route::post('/vizsga/show/delete', [VizsgaController::class, 'ShowTorles']);
    Route::post('/vizsga/watchlist', [VizsgaController::class, 'WatchlistView']);
});
