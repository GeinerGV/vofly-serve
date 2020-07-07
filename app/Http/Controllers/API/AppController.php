<?php

namespace App\Http\Controllers\API;

use App\Driver;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function driverOpenSign(Request $request) {
		$result = [];
		/**
		 * @var Driver
		 */
		$driver = $request->user()->driver;
		$result["isActive"] = boolval($driver->activo);
		$currpedidos = $driver->pedidosActuales();
		$result["currpedidos"] = $currpedidos ? $currpedidos->modelKeys() : [];
		$result['status'] = "success";
		return response()->json($result);
	}
}
