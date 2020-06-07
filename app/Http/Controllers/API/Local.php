<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\VerifyPhone;
use Illuminate\Support\Facades\Hash;

class Local extends Controller
{
	public function __construct() {
		# middleware is local and dev
	}

    public function sendTester(Request $request) {
        $request_phone = strlen($request->phone)==9 ? '51' . $request->phone : $request->phone;
        $result = [];
        $result['data'] = VerifyPhone::sendVerifationMessageLabsMobile($request_phone, VerifyPhone::generate6Code());
        return response()->json($result, 200);
    }

    public function tester(Request $request) {
        //$password = Hash::make($request->password);
        $result = [];
        #$result['data'] = $password;
        #$result["phone"] = app('firebase.auth')->getUser($request->uid)->uid;
		$url = 'https://maps.googleapis.com/maps/api/geocode/json?address=';
		$url .= urlencode($request->direccion);
		$url .= '&components=country:pe&language=es-419&bounds=-11.572723,-76.6205821|-12.526549,-77.200966&key=AIzaSyBmwP2cEtGGbcJIOUVz-C88NOO2vtMd6bo';
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true,);
		$response = curl_exec($curl);
		$err = curl_error($curl);
		if (!$err) {
			$response = json_decode($response);
			$result['data'] = ;
		} else {
			$result['status'] = "error";
		}
        return response()->json($result, 200);
    }
}
