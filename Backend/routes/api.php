<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::post('/login',           [AuthController::class, 'login']);
Route::post('/logout',          [AuthController::class, 'logout']);
Route::post('/register',        [AuthController::class, 'register']);

Route::middleware('auth:api')->group(function () {
    Route::get('/user-auth',        [AuthController::class, 'me']);
    Route::post('/refresh-token',   [AuthController::class, 'refresh']);
    Route::post('/validate-token',  [AuthController::class, 'validateToken']);
});

Route::prefix('user')->group(function () {
    Route::get('/get-users',        [UserController::class, 'getUsers']);

    Route::get('/',                 [UserController::class, 'index']);
    Route::get('/show/{id}',        [UserController::class, 'show']);
    Route::post('/create',          [UserController::class, 'store']);
    Route::put('/update',           [UserController::class, 'update']);
    Route::delete('/delete/{id}',   [UserController::class, 'destroy']);
})->middleware('auth:api');

Route::prefix('product')->group(function () {
    Route::get('/{id}',             [ProductController::class, 'index']);
    Route::post('/create/{id}',      [ProductController::class, 'store']);
    Route::put('/update',           [ProductController::class, 'update']);
    Route::delete('/delete/{id}',   [ProductController::class, 'destroy']);
})->middleware('auth:api');
