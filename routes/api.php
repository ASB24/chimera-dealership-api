<?php

use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\CarController;
use App\Http\Controllers\API\CartController;
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

Route::controller(UserController::class)->group(function(){
    Route::get('users','index');
    Route::post('users','store');
    Route::post('users/login','login');
    Route::get('users/{id}','show');
    Route::put('users/{id}','update');
    Route::delete('users/{id}','destroy');
});

Route::controller(CarController::class)->group(function(){
    Route::get('cars','index');
    Route::get('cars/list/{id}','indexByUser');
    Route::get('cars/{id}','show');
    Route::post('cars','store');
    Route::put('cars/{id}','update');
    Route::delete('cars/{id}','destroy');
});

Route::controller(CartController::class)->group(function(){
    Route::get('carts','index');
    Route::get('carts/{id}','show');
    Route::post('carts','store');
    Route::put('carts/{id}','update');
    Route::delete('carts/{id}','destroy');
});
