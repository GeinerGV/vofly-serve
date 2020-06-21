<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

Route::get('/politicas', function () {
    return view('politicas');
});

Auth::routes(['verify' => true]);

Route::get('/dashboard', 'HomeController@index')->middleware('verified')->name('dashboard');
Route::get('/drivers', 'HomeController@index')->middleware('verified');
Route::post('/drivers', 'HomeController@index')->middleware('verified');
Route::get('/usuarios', 'HomeController@index')->middleware('verified');
Route::post('/usuarios', 'HomeController@index')->middleware('verified');
Route::get('/pedidos', 'HomeController@index')->middleware('verified');
Route::post('/pedidos', 'HomeController@index')->middleware('verified');
Route::get('/pagos', 'HomeController@index')->middleware('verified');
Route::post('/pagos', 'HomeController@index')->middleware('verified');

Route::get('/track', 'GeneralController@trackid');
Route::post('/track', 'GeneralController@trackid');

Route::get('/tmp', function(Request $request) {
    $result = [DB::table('deliveries')->select("id")->get()];
    return response()->json($result);
});

Route::get('/home', function () {
    return redirect("/dashboard");
});
