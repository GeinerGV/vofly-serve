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

Route::middleware('auth:api')->post('/user', function (Request $request) {
    return response()->json([
        "user"=>$request->user(),
        //"api_token"=> Str::random(60),
        #"current"=>Route::current(),
        #"currentRouteName"=>Route::currentRouteName(),
        #"currentRouteAction"=>Route::currentRouteAction(),
    ]);
});
Route::group(['middleware' => 'fromapp'], function() {
    Route::post('login', 'Auth\Api\LoginController@login');
    Route::post('register', 'Auth\Api\RegisterController@appregister');
    Route::post('preregister', 'Auth\Api\RegisterController@preregister');
    Route::post('auth', 'Auth\Api\RegisterController@evaluateSignAuthCachedData');
});
