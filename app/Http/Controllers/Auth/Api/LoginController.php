<?php

namespace App\Http\Controllers\Auth\Api;

use App\Http\Controllers\Auth\LoginController as Controller;
use \Illuminate\Http\Request;
use \Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\VerifyPhone;
use Illuminate\Support\Facades\Date;

class LoginController extends Controller
{

	public function prevalidator(array $data) {
		//error_log("data prelogin: ==========" . json_encode($data));
		return Validator::make($data, [
			'verify_id' => array_merge(
				#['required'],
				VerifyPhone::VERIFY_ID_BASIC_VALIDATE_RULES,
				VerifyPhone::VERIFY_ID_EXITS_VALIDATE_RULES
			),
			'phone' => array_merge(
				['required'], User::PHONE_BASIC_VALIDATE_RULES, ['exists:users']
			),
			'email' => array_merge(['required'], User::EMAIL_BASIC_VALIDATE_RULES, ['exists:users']),
		]);
	}

	public function prelogin(Request $request) {
		$request_phone = strlen($request->phone)==9 ? '51' . $request->phone : $request->phone;
        $this->prevalidator(
			array_merge($request->all(), ['phone'=> $request_phone])
		)->validate();
		$result = [];
		if (User::where('phone', $request_phone)->where('email', $request->email)->first()) {
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
		}
		//error_log(json_encode($result));
		return response()->json($result, 200);
	}

	public function validator(array $data) {
		return Validator::make($data, [
			'verify_id' => array_merge(
				['required'],
				VerifyPhone::VERIFY_ID_BASIC_VALIDATE_RULES,
				VerifyPhone::VERIFY_ID_EXITS_VALIDATE_RULES
			),
			'code' => array_merge(['required'], VerifyPhone::CODE_BASIC_VALIDATE_RULES),
		]);
	}

	public function applogin(Request $request)
    {
        $this->validator($request->all())->validate();
		$result = [];
		error_log("init login: ==========" . json_encode($request->all()));
		if ($phone_number = VerifyPhone::getConfirmedPhoneNumber($request->verify_id, $request->code)) {
			/*event(new Registered($user = $this->create(
				array_merge($request->all(), ['phone'=>$phone_number])
			)));*/
			$user = User::where('phone', $phone_number)->first();
			$this->guard()->login($user);
			$result['status'] = 'LOGGED';
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
		
		error_log("result login: ==========" . json_encode($result));
		return response()->json($result, 200);
	}

	
	

    /**
	 * Send the response after the user was authenticated.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	protected function sendLoginResponse(Request $request) {
		$this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        return $request->wantsJson()
                    ? new Response('', 204)
                    : redirect()->intended($this->redirectPath());
	}
}
