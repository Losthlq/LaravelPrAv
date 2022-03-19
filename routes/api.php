<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//protected
Route::group(['middleware' => 'auth:sanctum'], function(){
    Route::resource('/ticket', \App\Http\Controllers\TicketController::class);
});

//public
Route::post('/register', [\App\Http\Controllers\ApiTokenController::class,'register']);
Route::post('/login', [\App\Http\Controllers\ApiTokenController::class,'login']);
