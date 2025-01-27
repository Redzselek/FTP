<?php
use App\Http\Controllers\MovieController;

Route::apiResource('movies', MovieController::class);