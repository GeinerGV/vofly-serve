<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\VoflyApp;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    public function terminos(Request $request) {
		$index = isset($request->app) && is_numeric($request->app) && $request->app>0 ?
			 $request->app : 1;
        $result = [];
        $result["politica"] = VoflyApp::find($index)->politica;
        return response()->json($result);
    }
}
