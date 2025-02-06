<?php
use App\Http\Controllers\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Route::post('/sanctum-teszt/login', [SanctumTestController::class, 'login']);
// Route::post('/register', [SanctumTestController::class, 'register']);

// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('/logout', [SanctumTestController::class, 'logout']);
//     Route::get('/user', [SanctumTestController::class, 'user']);
//     Route::get('/test-auth', [SanctumTestController::class, 'testAuth']);
// });