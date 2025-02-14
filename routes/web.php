<?php
use App\Http\Controllers\TestAPIController;
use App\Http\Controllers\VizsgaremekController;
use App\Http\Controllers\BingoController;
use App\Http\Controllers\VizsgaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SanctumTestController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

Route::post('/testapi', [TestAPIController::class, 'TestAPI'])->name('testapi');
Route::post('/bingo/bingo', [BingoController::class, 'bingo'])->name('bingo');




Route::post('/vizsga/login', [VizsgaController::class, 'Bejelentkezes']);
Route::post('/vizsga/register', [VizsgaController::class, 'Regisztralas']);
Route::post('/vizsga/forgot-password', [VizsgaController::class, 'ElfelejtettJelszo']);
Route::get('/vizsga/movies', [VizsgaController::class, 'MoviesView']);
Route::get('/vizsga/series', [VizsgaController::class, 'SeriesView']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/vizsga/dashboard', [VizsgaController::class, 'Dashboard']);
    Route::post('/vizsga/logout', [VizsgaController::class, 'Kijelentkezes']);
    Route::post('/vizsga/profile/password', [VizsgaController::class, 'JelszoValtoztatas']);
    Route::post('/vizsga/profile/delete', [VizsgaController::class, 'FiokTorles']);
    Route::post('/vizsga/upload', [VizsgaController::class, 'UploadManager']);
    Route::post('/vizsga/feltoltesek', [VizsgaController::class, 'FeltoltesekView']);
    Route::post('/vizsga/show/edit', [VizsgaController::class, 'ShowSzerkesztes']);
    Route::post('/vizsga/show/delete', [VizsgaController::class, 'ShowTorles']);
    Route::post('/vizsga/watchlist', [VizsgaController::class, 'WatchlistView']);
});

Route::post('frontend/{any?}', function () {
    return file_get_contents(public_path('frontend/index.html'));
})->where('any', '.*');