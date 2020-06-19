<?php

namespace App\Http\Controllers;

use App\Delivery;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class GeneralController extends Controller
{
	public function trackid(Request $request) {
		$validator = Validator::make($request->all(), [
            'id'=>['required', "string", "size:16", "exists:deliveries,trackid"]
        ]);
        if ($validator->fails()) {
			if ($request->acceptsJson()) {
				return response()->json(["errors"=>$validator->errors()]);
			}
            return new Response("ERROR_ID: {$validator->errors()}", 404);
        }
        /**
         * @var Delivery
         */
        $delivery = Delivery::where('trackid', $request->id)->first();
		$delivery->load('recojo.place', 'entrega.place');
		if ($delivery->estado!=="Enviado") {
			$delivery->load("driver");
		}
        if ($request->user()) {
            
        } else {
			
        }

		$result = [];

		if ($request->isMethod('post') && $request->acceptsJson()) {
			if ($request->has('woLocation')) {
				$result["delivery"] = $delivery->makeHidden('location')->toArray();
			} else {
				$result["delivery"] = $delivery;
			}
			return response()->json($result);
		}
		$result["delivery"] = $delivery;
        return view("track", $result);
	}
}
