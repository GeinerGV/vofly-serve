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
        "user"=>$request->user()->load("driver"),
        //"api_token"=> Str::random(60),
        #"current"=>Route::current(),
        #"currentRouteName"=>Route::currentRouteName(),
        #"currentRouteAction"=>Route::currentRouteAction(),
    ]);
});

Route::group(['middleware' => 'auth:api'], function() {
	//Route::apiResource('profile', 'API\Profile');
	Route::post('avatar', 'API\Profile@updateAvatar');
	Route::post('perfil', 'API\Profile@updateData');
	Route::post('savedplace', 'API\Profile@userPlace');
	Route::get('savedplaces', 'API\Profile@places');

    Route::apiResource('delivery', 'API\DeliveryController');
    Route::apiResource('mensajes', 'API\MensajeController');
    Route::post('dashboard', 'API\DashboardController@lapsoTiempoData');
    Route::post('paginator', 'API\DashboardController@pagination');
    Route::get('paginator', 'API\DashboardController@pagination');
	Route::get('precios', 'API\DeliveryController@precios');
    Route::get('deliveries', 'API\DeliveryController@lista');

    Route::group(['middleware' => 'auth.driver'], function() {
        Route::get('historial', 'API\DeliveryController@historial');
        Route::get('pedidos', 'API\DeliveryController@pedidos');
        Route::get('currpedido', 'API\DeliveryController@currentPedido');
        Route::post('pedido/start', 'API\DeliveryController@iniciarPedido');
        Route::post('usertodriver', 'API\Profile@userToDriverForm');
        Route::post('savelocation', 'API\Profile@saveLocation');
    });
    
	Route::get('terminos', 'API\InfoController@terminos');
});

Route::group(['middleware' => 'fromapp'], function() {
    Route::post('login', 'Auth\Api\LoginController@applogin');
    Route::post('prelogin', 'Auth\Api\LoginController@prelogin');
    Route::post('register', 'Auth\Api\RegisterController@appregister');
    Route::post('preregister', 'Auth\Api\RegisterController@preregister');

    Route::post('logindriver', 'Auth\Api\DriverLoginController@applogin');
    Route::post('prelogindriver', 'Auth\Api\DriverLoginController@prelogin');
    Route::post('registerdriver', 'Auth\Api\DriverRegisterController@appregister');
    Route::post('preregisterdriver', 'Auth\Api\DriverRegisterController@preregister');

    Route::post('auth', 'Auth\Api\RegisterController@evaluateSignAuthCachedData');
    Route::post('local/send', 'API\Local@sendTester');
    Route::post('local/test', 'API\Local@tester');
});
