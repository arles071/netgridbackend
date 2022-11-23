<?php

use App\Http\Controllers\api\AuthenticationController;
use App\Http\Controllers\api\FavoriteController;
use App\Http\Controllers\api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register', [UserController::class, 'register']);
Route::post('login', [AuthenticationController::class, 'login']);

// put all api protected routes here
Route::middleware('auth:api')->group(function () {
    Route::post('user-detail', [AuthenticationController::class, 'userDetail']);
    Route::post('logout', [AuthenticationController::class, 'logout']);
    Route::post('user/update', [UserController::class, 'update']);
    Route::resource('favorite', FavoriteController::class); //ruta de tipo resource
});

