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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
//use Twilio\Rest\Client;

//require_once '../../../../../vendor/autoload.php';

class RegisterController extends Controller
{

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'min:9', "max:11", 'unique:users'],
            'direccion' => ['required', 'string', 'max:255'],
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
		
		error_log(json_encode($data));
		
		/* if (Request::HasFile('avatar')) {
			$file = Request::file('avatar');
			$path = Storage::putFile(User::AvatarDirStorage, $file);
			$newUser->avatar = $path;
		} */
		
		return $newUser;
	}

	/**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function preregister(Request $request)
    {
        $this->validator($request->all())->validate();
		
		/* $sid    = "ACd7647d7cdc71cf93213ae9509cecdac6";
		$token  = "10b20788fc2d42dcda0e8b72eb7673d6";
		$twilio = new Client($sid, $token);
		$message = $twilio->messages
					->create("+51958318097", // to
						["body" => "Hi there!", "from" => "+12025198137"] 
					);*/
		
		//$user = $this->create($request->all());
		//$value_hash = $data['name'] . $data['phone'] . $data['direccion']
		//hash()
		$nexmo = app('Nexmo\Client');
		$message = $nexmo->message()->send([
			'to' => '51958318097',
			'from' => 'Vofly',
			'text' => 'Tu codigo Vofly es: ' . rand(10, 99) . '' . rand(1000, 9999) . '. Valido por 5 minutos.'
		]);

		return response()->json(['success'=>[
			"messageId"=> Str::random(30),
			"message"=> $message
		]], 200);

        /* return $request->wantsJson()
                    ? new Response('', 201)
                    : redirect($this->redirectPath()); */
    }
}
