<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\VoflyApp;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    public function terminos(Request $request) {
        $result = [];
        $result["politica"] = VoflyApp::find(1)->politica;
        return response()->json($result);
    }
}
