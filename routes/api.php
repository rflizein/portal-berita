<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Routing\Route as RoutingRoute;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// login
Route::post('login', [\App\Http\Controllers\API\AuthController::class, 'login']);
Route::post('register', [\App\Http\Controllers\API\AuthController::class, 'register']);
Route::post('logout', [\App\Http\Controllers\API\AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::put('update-password', [\App\Http\Controllers\API\AuthController::class, 'updatePassword'])->middleware('auth:sanctum');

// get all user
Route::get('getAllUser', [\App\Http\Controllers\API\UserController::class, 'getAllUser']);
Route::get('getUserById/{id}', [\App\Http\Controllers\API\UserController::class, 'getUserById']);

// category
Route::get('category', [\App\Http\Controllers\API\CategoryController::class, 'index']);
Route::get('category/{id}', [\App\Http\Controllers\API\CategoryController::class, 'show']);
Route::post('category', [\App\Http\Controllers\API\CategoryController::class, 'create']);
Route::delete('category/{id}', [\App\Http\Controllers\API\CategoryController::class, 'destroy'])->middleware('auth:sanctum');

// slider
Route::get('slider', [\App\Http\Controllers\API\SliderController::class, 'index']);
Route::get('slider/{id}', [\App\Http\Controllers\API\SliderController::class, 'show']);
Route::post('createSlider', [\App\Http\Controllers\API\SliderController::class, 'createSlider']);
Route::delete('slider/{id}', [\App\Http\Controllers\API\SliderController::class, 'destroy'])->middleware('auth:sanctum');

// category
Route::get('news', [\App\Http\Controllers\API\NewsController::class, 'index']);
Route::get('news/{id}', [\App\Http\Controllers\API\NewsController::class, 'show']);