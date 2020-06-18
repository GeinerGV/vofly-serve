<?php

namespace App\Http\Controllers\Auth\Api;

use App\Http\Controllers\Auth\RegisterController as Controller;
use Illuminate\Support\Facades\Storage;
use \Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Str;
//use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
//use Twilio\Rest\Client;
use App\VerifyPhone;
use Illuminate\Support\Facades\Date;
use Illuminate\Auth\Events\Registered;

//require_once '../../../../../vendor/autoload.php';

class RegisterController extends Controller
{

	/**
	 * Evaluar si está registrado o preregistrado o ninguno.
	 * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
	 */
	public function evaluateSignAuthCachedData(Request $request) {

		# Validator
		Validator::make($request->all(), [
			'verify_id' => array_merge(
				['required'],
				VerifyPhone::VERIFY_ID_BASIC_VALIDATE_RULES, VerifyPhone::VERIFY_ID_EXITS_VALIDATE_RULES
			),
			'phone' => array_merge(['required'], User::PHONE_BASIC_VALIDATE_RULES),
			'email' => array_merge(['required'], User::EMAIL_BASIC_VALIDATE_RULES),
		])->validate();

		#
		$result = [];
		$verify= VerifyPhone::getVerifyPhone($request->verify_id);
		#
		if ($request->phone==$verify->phone) {
			$result['data'] = [];
			$user = User::where('phone', $request->phone)->where('email', $request->email)->first();
			$result['data']['preregistered'] = !$user;
			$result['data']['verify_data'] = $verify;
		} else {
			$result['message'] = 'Diferentes números';
		}
		return response()->json($result, 200);
	}

    /**
     * Get a validator for an incoming preregistration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function prevalidator(array $data)
    {
		//error_log('REGISTRO LOG prevalidator init');
        return Validator::make(
			$data
		, [
			'name' => array_merge(User::NAME_BASIC_VALIDATE_RULES),
			'phone' => array_merge( User::PHONE_BASIC_VALIDATE_RULES),
			'direccion' => array_merge(User::DIRECCION_BASIC_VALIDATE_RULES),
			'email' => array_merge(User::EMAIL_BASIC_VALIDATE_RULES, ['unique:users']),
			'pais' => User::PAIS_BASIC_VALIDATE_RULES
		]);
	}

	/**
     * Handle a preregistration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function preregister(Request $request) {
		$validator1 = $this->prevalidator($request->all());
		$validator2 = Validator::make(["phone" => "+" . $request->pais . $request->phone], [
			"phone" => ['unique:users']
		]);
		$result = [];
		if ($validator1->fails() || $validator2->fails()) {
			$result["errors"] = array_merge(
				$validator1->errors()->toArray(), $validator2->errors()->toArray()
			);
		} else {
			//$request->validate(static::PrevalidateRules);
			$result["status"] = Controller::STATUS_SUCCES;
			#$result["data"] = $request->all();
		}
		return response()->json($result, 200);
	}

	/**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
		//error_log('REGISTRO LOG validator init');
       return Validator::make(
			$data
		, [
			#'name' => array_merge(User::NAME_BASIC_VALIDATE_RULES),
			#'phone' => array_merge( User::PHONE_BASIC_VALIDATE_RULES, ['unique:users']),
			'direccion' => array_merge(['required'], User::DIRECCION_BASIC_VALIDATE_RULES),
			#'email' => array_merge(User::EMAIL_BASIC_VALIDATE_RULES, ['unique:users']),
			#'pais' => User::PAIS_BASIC_VALIDATE_RULES,
			'uid' => array_merge(['required'], User::UID_BASIC_VALIDATE_RULES)
		]);
	}
	
	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return \App\User
	 */
	protected function create($data)
	{
		$token = Str::uuid();

		$newUser = User::create([
			'name' => $data['displayName'],
			'phone' => $data['phoneNumber'],
			'direccion' => $data['direccion'],
			'email' => $data['email'],
			'uid' => $data['uid'],
		]);

		#$newUser->api_token = hash('sha256', $token);
		$newUser->api_token = $data['uid'];
		
		//error_log(json_encode($data));
		
		/* if (Request::HasFile('avatar')) {
			$file = Request::file('avatar');
			$path = Storage::putFile(User::AvatarDirStorage, $file);
			$newUser->avatar = $path;
		} */

		$newUser->save();
		
		return $newUser;
	}

	/**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function appregister(Request $request)
    {
        $this->validator($request->all())->validate();
		$result = [];
		$auth = app('firebase.auth');
		$userFirebase = $auth->getUser($request->uid);
		$data = array_merge($userFirebase->jsonSerialize(),
			['direccion' => $request->direccion]
		);
		#return response()->json([$data["uid"]], 200);
		event(new Registered($user = $this->create($data)));
		$this->guard()->login($user);
		$result['status'] = 'REGISTERED';
		$result["api_token"] = $user->api_token;
		/*$result["uid"] = $result["data"]["uid"];
		$result["email"] = $user->email;
		$result["phone"] = $user->phoneNumber;
		$result["name"] = $user->displayName;*/
		return response()->json($result, 200);
	}
}
