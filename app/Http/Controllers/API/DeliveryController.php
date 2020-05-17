<?php

namespace App\Http\Controllers\API;

use App\Delivery;

#DESTINO
use App\Models\Destino\Agente;
use App\Models\Destino\Shop;
use App\Place;

#CARGA
use App\Models\Carga\Paquete;
use App\Models\Carga\ListaItem;
use App\Models\Carga\ListaCompra;

#PAGO
use App\Models\Pago\DeliveryPlan;
use App\Models\Pago\PagoInfo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class DeliveryController extends Controller
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
			'recojo' => ['required'],
			'entrega' => ['required'],
			'carga' => ['required'],
			'pago' => ['required'],
			'detalles_carga' => ['string'],
			'distancia' => ['required'],
			'type' => ['required'],
		])->validate();

		$delivery = new Delivery;
		if ($request->distancia) $delivery->distancia = $request->distancia;
		if ($request->detallesCarga) $delivery->detalles_carga = $request->detallesCarga;
		$delivery->type = $request->type;

		$carga = null;
		if ($request->type==='DELIVERY_PAQUETE') {
			# Carga
			$carga = new Paquete;
			$carga->updateData($request->carga);
			#$carga->delivery->save($delivery);
			$carga->push();
			$delivery->cargable_id = $carga->id;
			$delivery->cargable_type = Paquete::class;

			# RECOJO
			$recojo = new Agente;
			$recojo->updateData($request->recojo, 'recojo');
			$recojo->push();
			$delivery->recogible_type = Agente::class;
			$delivery->recogible_id = $recojo->id;
			#$recojo->delivery()->save($delivery);
		}
		
		# ENTREGA
		$entrega = new Agente;
		$entrega->updateData($request->entrega, 'entrega');
		$entrega->push();
		$delivery->entregable_type = Agente::class;
		$delivery->entregable_id = $entrega->id;
		#$entrega->delivery()->save($delivery);

		# Pago
		$plan = DeliveryPlan::find($request->pago['id']);
		$delivery->plan()->associate($plan);
		
		# USER
		$delivery->user()->associate(Auth::user());

		#$recojo->delivery()->save($delivery);
		#$delivery->entrega()->save($recojo);

		$delivery->push();

		return response()->json(["data"=>$delivery]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function show(Delivery $delivery)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Delivery $delivery)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function destroy(Delivery $delivery)
    {
        //
    }
}
