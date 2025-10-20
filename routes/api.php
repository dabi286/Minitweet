<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TweetController;
use App\Http\Controllers\LikeController;
use Illuminate\Http\Request;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/tweets', [TweetController::class, 'index']); // Public — anyone can view tweets

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });


    Route::post('/tweets', [TweetController::class, 'store']);
    Route::post('/tweets/{tweet}/like', [LikeController::class, 'toggleLike']);

    Route::post('/logout', [AuthController::class, 'logout']);
});
