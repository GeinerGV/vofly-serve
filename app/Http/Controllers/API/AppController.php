<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function driverOpenSign(Request $request) {
		$result = [];
		$driver = $request->user()->driver;
		$result["isActive"] = boolval($driver->activo);
		$currpedido = $driver->currentPedido();
		$result["currpedido"] = $currpedido ? $currpedido->id : null;
		$result['status'] = "success";
		return response()->json($result);
	}
}
