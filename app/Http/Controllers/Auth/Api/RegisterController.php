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
     * Get a validator for an incoming preregistration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function prevalidator(array $data)
    {
		//error_log('REGISTRO LOG prevalidator init');
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'digits_between:9,11', 'unique:users'],
            'direccion' => ['required', 'string', 'max:255'],
			'verify_id' => ['uuid', 'exists:verify_phones'],
        ]);
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
            'name' => ['required', 'string', 'max:255'],
            'direccion' => ['required', 'string', 'max:255'],
			'code' => ['required', 'digits:6'],
			'verify_id' => ['required', 'uuid', 'exists:verify_phones,verify_id,deleted_at,NULL'],
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
		$token = Str::random(60);

		$newUser = User::create([
			'name' => $data['name'],
			'phone' => $data['phone'],
			"direccion" => $data['direccion'],
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
     * Handle a preregistration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function preregister(Request $request)
    {
        $this->prevalidator($request->all())->validate();
		$result = [];
		if ($request->filled('verify_id')) {
			if ($phone = VerifyPhone::firstWhere('verify_id', $request->verify_id)) {
				if ($phone->phone == $request->phone && strtotime($phone->updated_at)>=time()-5*60) {
					$result['status'] = 'WAIT_YOUR_CONFIRMATION';
					$result['data'] = $phone;
				} else {
					$phone->phone = $request->phone;
					$result['status'] = 'NEW_CODE_SENDED';
					$result['data'] = $phone->resend();
				}
			}
		}
				
		if ( !isset($result['status']) ) {
			$count = VerifyPhone::where('phone', $request->phone)->whereDate('created_at', '>', Date::now()->addDay(-1))->count();
			Validator::make(['phone_verifying_tries'=>$count], [
				'phone_verifying_tries' => ['integer', 'max:9']
			])->validate();

			$verify = new VerifyPhone;
			$verify->ip = $request->ip();
			$result['status'] = 'NEW_CODE_SENDED';
			$result['data'] = $verify->send($request->phone);
		}
		error_log(json_encode($result));
		return response()->json($result, 200);
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
		if ($phone = VerifyPhone::confirmPhone($request->verify_id, $request->code)) {
			event(new Registered($user = $this->create(
				array_merge($request->all(), ['phone'=>$phone])
			)));
			$this->guard()->login($user);
			$result['status'] = 'REGISTERED';
			$result['data'] = array_merge($user->toArray(), ['api_token'=>$user->api_token]);
		} elseif (is_null($phone)) {
			$result['status'] = 'INVALID_CODE';
			$result['messsage'] = "El código no es válido";
			//$result['error'] = "INVALID_CODE";
		} else {
			$result['status'] = 'EXPIRED_CODE';
			$result['messsage'] = "El código ha expirado";
			$result['tmp_phone'] = $phone;
		}
		return response()->json($result, 200);
    }
}
