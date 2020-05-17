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
        return Validator::make($data, [
            'name' => array_merge(['required'], User::NAME_BASIC_VALIDATE_RULES),
            'phone' => array_merge(['required'], User::PHONE_BASIC_VALIDATE_RULES, ['unique:users']),
            'direccion' => array_merge(['required'], User::DIRECCION_BASIC_VALIDATE_RULES),
			'verify_id' => array_merge(
				VerifyPhone::VERIFY_ID_BASIC_VALIDATE_RULES, VerifyPhone::VERIFY_ID_EXITS_VALIDATE_RULES
			),
			'email' => array_merge(['required'], User::EMAIL_BASIC_VALIDATE_RULES),
        ]);
	}

	/**
     * Handle a preregistration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function preregister2(Request $request)
    {
		$request_phone = strlen($request->phone)==9 ? '51' . $request->phone : $request->phone;
        $this->prevalidator(
			array_merge($request->all(), ['phone'=> $request_phone])
		)->validate();
		$result = [];
		if ($request->filled('verify_id') && $phone = VerifyPhone::getVerifyPhone($request->verify_id)) {
			#if ($phone = VerifyPhone::getVerifyPhone($request->verify_id)) {
				if ($phone->phone == $request_phone && $phone->isAvailableCode()) {
					$result['data'] = $phone;
				} else {
					$phone->phone = $request_phone;
					$result['data'] = $phone->resend($request->ip());
				}
				$result['status'] =  $phone->getStatus();
			#}
		} else {
				
		#if ( !isset($result['status']) ) {
			$count = VerifyPhone::where('phone', $request_phone)
						->whereDate('created_at', '>', Date::now()->addDay(-1))
						->count();

			Validator::make([
					VerifyPhone::KEYRULE_REGISTER_TRIES_PHONE_DAY => $count // data
				], [
				VerifyPhone::KEYRULE_REGISTER_TRIES_PHONE_DAY => array_merge(
					['required'],
					VerifyPhone::REGISTER_TRIES_PHONE_DAY_BASIC_VALIDATE_RULES, // rules
					['max:' . (VerifyPhone::MAX_REGISTER_TRIES_PER_DAY_PER_PHONE - 1)],
				),
			])->validate();

			$result['data'] = VerifyPhone::send($request_phone, $request->ip());
			$result['status'] = $result['data']->getStatus();
		}
		//error_log(json_encode($result));
		return response()->json($result, 200);
    }

	/**
     * Handle a preregistration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function preregister(Request $request) {
		$result = [];
		$request_phone = strlen($request->phone)==9 ? '51' . $request->phone : $request->phone;
		$body = $request->all();
		if ($request->phone) $body["phone"] = strlen($request->phone)==9 ? '51' . $request->phone : $request->phone;
		Validator::make(
			$body
			, [
				'name' => array_merge(User::NAME_BASIC_VALIDATE_RULES),
				'phone' => array_merge( User::PHONE_BASIC_VALIDATE_RULES, ['unique:users']),
				'direccion' => array_merge(User::DIRECCION_BASIC_VALIDATE_RULES),
				'email' => array_merge(User::EMAIL_BASIC_VALIDATE_RULES, ['unique:users']),
		])->validate();
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
        return Validator::make($data, [
            'name' => array_merge(['required'], User::NAME_BASIC_VALIDATE_RULES),
            'direccion' => array_merge(['required'], User::DIRECCION_BASIC_VALIDATE_RULES),
			'verify_id' => array_merge(
				['required'],
				VerifyPhone::VERIFY_ID_BASIC_VALIDATE_RULES, VerifyPhone::VERIFY_ID_EXITS_VALIDATE_RULES
			),
			'email' => array_merge(['required'], User::EMAIL_BASIC_VALIDATE_RULES),
			'code' => array_merge(['required'], VerifyPhone::CODE_BASIC_VALIDATE_RULES),
        ]);
	}
	
	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return \App\User
	 */
	protected function create(array $data)
	{
		$token = Str::uuid();

		$newUser = User::create([
			'name' => $data['name'],
			'phone' => $data['phone'],
			'direccion' => $data['direccion'],
			'email' => $data['email'],
			//'api_token' => hash('sha256', $token),
		]);

		$newUser->api_token = hash('sha256', $token);
		
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
		if ($phone_number = VerifyPhone::getConfirmedPhoneNumber($request->verify_id, $request->code)) {
			event(new Registered($user = $this->create(
				array_merge($request->all(), ['phone'=>$phone_number])
			)));
			$this->guard()->login($user);
			$result['status'] = 'REGISTERED';
			$result['data'] = array_merge($user->toArray(), ['api_token'=>$user->api_token]);
		} elseif (is_null($phone_number)) {
			$result['status'] = VerifyPhone::STATUS_INVALID_CODE;
			$result['messsage'] = "El código no es válido";
			//$result['error'] = "INVALID_CODE";
		} else {
			$result['status'] = VerifyPhone::STATUS_EXPIRED_CODE;
			$result['messsage'] = "El código ha expirado";
			$result['tmp_phone'] = $phone_number;
		}
		return response()->json($result, 200);
	}
}
