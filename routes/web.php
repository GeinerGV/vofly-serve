<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['verify' => true]);

Route::get('/dashboard', 'HomeController@index')->middleware('verified');
Route::get('/drivers', 'HomeController@index')->middleware('verified');
Route::post('/drivers', 'HomeController@index')->middleware('verified');
Route::get('/usuarios', 'HomeController@index')->middleware('verified');
Route::get('/pedidos', 'HomeController@index')->middleware('verified');
Route::get('/pagos', 'HomeController@index')->middleware('verified');
Route::post('/pagos', 'HomeController@index')->middleware('verified');

Route::get('/home', function () {
    return redirect("/dashboard");
});
