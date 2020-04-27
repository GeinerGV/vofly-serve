<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ApiCustomAuthController extends Controller
{
	public $successStatus = 200;

	/**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
	public function login(Request $request) {
		if(Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])){ 
			$user = Auth::user();
			$success['user'] = $user;
			return response()->json(['success' => $success], $this->successStatus);
		} else { 
			return response()->json(['error'=>'Unauthorised'], 401); 
		}
	}

	/**
	 * Send the response after the user was authenticated.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	protected function sendLoginResponse(Request $request) {
		
	}
}
