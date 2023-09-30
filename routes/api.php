<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\MeController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Status\StatusController;
use App\Http\Controllers\Users\UserController;
use Illuminate\Support\Facades\Route;


Route::post('/login', LoginController::class);
Route::post('/register', RegisterController::class);

Route::middleware("auth:sanctum")->group(function () {
    Route::get('/me', MeController::class);
    Route::post('/logout', LogoutController::class);

    // status

    Route::get('/status', [StatusController::class, 'index']);
    Route::post('/status', [StatusController::class, 'store']);

    // comment status
    Route::post('/status/{identifier}/comment', [StatusController::class, 'comment']);

    // likes
    Route::post('/status/{identifier}/likes', [StatusController::class, 'likes']);

    // users

    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/follow', [UserController::class, 'follow']);

    // follow

    Route::post('/users/following', [UserController::class, 'following']);


    // profile
    Route::get('/profile/{username}', [UserController::class, 'profile']);
});
