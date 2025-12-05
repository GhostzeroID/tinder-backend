<?php

use App\Http\Controllers\PersonController;
use App\Http\Controllers\LikeController;

Route::get('/people', [PersonController::class, 'index']);
Route::post('/like', [LikeController::class, 'like']);
Route::get('/liked/{personId}', [LikeController::class, 'likedPeople']);
