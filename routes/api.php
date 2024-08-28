<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Infrastructure\Adapter\In\Web\Http\Controllers\AuthController;
use \App\Infrastructure\Adapter\In\Web\Http\Controllers\GiphyController;
use \App\Infrastructure\Adapter\In\Web\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('/v1')->group(function () {
    Route::group(['prefix'=>'auth','as'=>'auth.'], function() {
        Route::post('signin', [AuthController::class, 'signin'])->name("v1.auth.signin");
    });

    Route::middleware('auth:api')->prefix('gif')->group(function() {
        Route::get('search', [GiphyController::class, 'search'])->name("v1.gif.search");
        Route::get('{id}', [GiphyController::class, 'findByID'])->name("v1.gif.findByID");
    });

    Route::middleware('auth:api')->prefix('user')->group(function() {
        Route::post('/favorite', [UserController::class, 'addFavorite'])->name("v1.user.addFavorite");
    });
});
