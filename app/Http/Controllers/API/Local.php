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
        $password = Hash::make($request->password);
        $result = [];
        $result['data'] = $password;
        return response()->json($result, 200);
    }
}
