<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

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
    Route::put('/update/{id}',      [UserController::class, 'update']);
    Route::delete('/delete/{id}',   [UserController::class, 'destroy']);
})->middleware('auth:api');
