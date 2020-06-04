<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Contacto\Mensaje;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MensajeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Validator::make($request->all(), [
           "mensaje" => ["required", "string", "max:300"],
           "from" => ["required", "string"],
        ])->validate();
        $result = [];
        $mensaje = new Mensaje;
        $mensaje->user()->associate($request->user());
        $mensaje->mensaje = $request->mensaje;
        $mensaje->from = $request->from;
        $mensaje->save();
        if ($mensaje->id) {
            $result["status"] = "success";
        }
        return response()->json($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Contacto\Mensaje  $mensaje
     * @return \Illuminate\Http\Response
     */
    public function show(Mensaje $mensaje)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contacto\Mensaje  $mensaje
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Mensaje $mensaje)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contacto\Mensaje  $mensaje
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mensaje $mensaje)
    {
        //
    }
}
