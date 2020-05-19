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
			'phone' => array_merge(User::PHONE_BASIC_VALIDATE_RULES),
			'email' => array_merge(User::EMAIL_BASIC_VALIDATE_RULES, ['exists:users']),
			'pais' => User::PAIS_BASIC_VALIDATE_RULES,
		]);
	}

	public function prelogin(Request $request) {
		$this->prevalidator($request->all())->validate();
		$phone = "+" . $request->pais . $request->phone;
		Validator::make(["phone" => $phone], [
			"phone" => ['exists:users']
		])->validate();
		$user = User::where("phone", $phone)->where('email', $request->email)->first();
        $result = [];
		//$request->validate(static::PrevalidateRules);
		if ($user) $result["status"] = Controller::STATUS_SUCCES;
		else {
			$result["errors"] = [
				"email" => ["Credeciales no registradas"],
				"phone" => ["Credeciales no registradas"],
			];
		}
		#$result["data"] = $request->all();
		return response()->json($result, 200);
	}

	public function validator(array $data) {
		return Validator::make($data, [
			'uid' => array_merge(['required'], User::UID_BASIC_VALIDATE_RULES, ["exists:users"])
		]);
	}

	public function applogin(Request $request)
    {
        $this->validator($request->all())->validate();
		$result = [];
		$user = User::where("uid", $request->uid)->first();
		$result["data"] = $user->makeVisible(["api_token", "uid"])->makeHidden(["id"])->toArray();
		return response()->json($result, 200);

		/* PROVIDER PHONE OBJECT DATA {
		"providerId": "phone",
		"verificationCode": "766087",
		"verificationId": "AM5PThBWLzH54Pvxe5Px7cD9P79Jx1bGG_zCcZbTSAkxci4_NbN2Yu0Dbo-AGuLf1rx2u_W4rEi8QNuUNt0QL9NOsFxMzTAQ00W3xhgBFRWBzMAq7w2sgeaXR6pV-MLRzL2hzrLX87SII1DtRDf0AxVBq_-QVbNQIQaC8weLrJUVo1w90NoKNUgvGagApRHCLzI0Rc5xrAIluZ3wkJDLF40snHtRL8Rl1Q",       
		}
		*/
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
