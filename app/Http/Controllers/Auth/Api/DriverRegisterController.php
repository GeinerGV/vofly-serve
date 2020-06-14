<?php

namespace App\Http\Controllers\Auth\Api;

use App\Driver;
use App\Http\Controllers\Auth\RegisterController as Controller;
use \Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Str;
//use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
//use Twilio\Rest\Client;
use App\VerifyPhone;
use Illuminate\Auth\Events\Registered;
use Kreait\Firebase\Database;

//require_once '../../../../../vendor/autoload.php';

class DriverRegisterController extends Controller
{

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
			'pais' => User::PAIS_BASIC_VALIDATE_RULES,
			'dni' => array_merge(Driver::DNI_BASIC_VALIDATE_RULES, ['unique:drivers']),
		]);
	}

	/**
     * Handle a preregistration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function preregister(Request $request) {
		$this->prevalidator($request->all())->validate();
		Validator::make(["phone" => $request->pais . $request->phone], [
			"phone" => ['unique:users']
		])->validate();
		$result = [];
		//$request->validate(static::PrevalidateRules);
		$result["status"] = Controller::STATUS_SUCCES;
		#$result["data"] = $request->all();
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
			'uid' => array_merge(['required'], User::UID_BASIC_VALIDATE_RULES),
			'dni' => array_merge(['required'], Driver::DNI_BASIC_VALIDATE_RULES, ['unique:drivers'])
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
		#$token = Str::uuid();

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

		$driver = new Driver;
		$driver->dni = $data['dni'];
		$driver->user()->associate($newUser);
		$driver->push();

		/**
		 * @var Database
		 */
		$db = app('firebase.database');
		$userRef = $db->getReference('users/'.$data['uid'].'/driver');
		$userRef->set(true);
		
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
			[
				'direccion' => $request->direccion, 
				'dni' => $request->dni,
			],
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
