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
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Kreait\Firebase\Database;

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
			'recojo' => ['required', "array"],
			'entrega' => ['required', "array"],
			'carga' => ['required', "array"],
			'pago' => ['required', "array"],
			'detalles_carga' => ['string'],
			'distancia' => ["integer"],
			'duracion' => ["integer"],
			'type' => ['required', "in:DELIVERY_PAQUETE"],
		])->validate();
		Validator::make(['pago'=>$request->pago["id"]], [
			'pago' => ['required', "exists:delivery_plans,id"],
		])->validate();
		$result = [];
		$delivery = new Delivery;
		if ($request->distancia) $delivery->distancia = $request->distancia;
		if ($request->duracion) $delivery->duracion_media = $request->duracion;
		if ($request->trayectoria) $delivery->trayectoria = $request->trayectoria;
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
		$delivery->precio_plan = $plan->precio;

		# USER
		$delivery->user()->associate(Auth::user());

		#$recojo->delivery()->save($delivery);
		#$delivery->entrega()->save($recojo);

		$delivery->push();
		$delivery->trackid = hash('adler32', $delivery->id) . hash('adler32', time());
		$delivery->save();
		//$result["delivery"] = $delivery;

		//$firestore = app('firebase.firestore');
		/**
		 * @var Database
		 */
		$db = app('firebase.database');
		$newDeliveryRef = $db->getReference('deliveries')->push();
		$newDeliveryRef->set(array_merge($request->all(), ['id'=>$delivery->id]));
		$delivery->firebasekey = $newDeliveryRef->getKey();
		$delivery->save();

		$result["status"] = "success";
		return response()->json($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Delivery  $delivery
     * @return \Illuminate\Http\Response
     */
    public function show(Delivery $delivery)
    {
		$result = [];
		//$result["delivery"] = $delivery;
		$user = Auth::user();
		if ($delivery->user_id===$user->id) {
			$delivery->load("recojo.place", "entrega.place", "plan", "carga", "driver.user");
			$result["delivery"] = $delivery->toArray();
			if (request()->filled("woLocation")) {
				$result["delivery"]['location'] = null;
			}
		} else if ($user->driver && (!$delivery->driver_id || $delivery->driver_id==$user->driver->id)) {
			$result["delivery"] = $delivery;
			$delivery->load("recojo.place", "entrega.place", "plan", "carga", "user");
		} else if($user->driver) {
			$result["status"] = "reservado";
		}
		//$result["data"] = $delivery->user_id;
        return response()->json($result);
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

	public function precios(Request $request) {
		$result = [];
		$result["precios"] = DeliveryPlan::all();
		return response()->json($result);
	}

	public function lista(Request $request) {
		/* $deliveries = $request->user()->deliveries;
		$deliveries->load("recojo.place", "entrega.place", "plan", "carga");
		return response()->json($deliveries); */

		Validator::make($request->all(), [
			'status' => ['required', "string"],
			'last_id' => ["integer"],
			'limit' => ["integer"],
		])->validate();

		//$last_id = isset($request->last_id) ? $request->pag : null;
		$limit = isset($request->limit) ? $request->limit :4;
		$result = [];
		//$data = Delivery::where("user_id", $request->user()->id);
		$filter = null;
		switch ($request->status) {
			case "WAITING":
				$filter = function ($query) {
					$query->where("estado", "<>", "Enviado")->orWhereNull("estado");
				};
			break;
			case "FINISHED":
				$filter = function ($query) {
					$query->where("estado", "=", "Enviado");//->orWhereNull("estado");
				};
			break;
		}
		$result["deliveries"] = $filter ? Delivery::where(function ($query) {
				global $request;
				if ($request->filled("last_id")) {
					$query->where("id", '<', $request->last_id);
				} else {
					$query;
				}
			})->where("user_id", $request->user()->id)->where($filter)->orderBy('id', 'desc')
			->limit($limit)->get() : 
			[];
		if ($filter) $result["deliveries"]->load("recojo.place", "entrega.place", "plan", "carga");
		return response()->json($result);

	}

	public function historial(Request $request) {
		if (!$request->user()->driver) return new Response("", 403);
		Validator::make($request->all(), [
			'status' => ['required', "string"],
			'last_id' => ["integer"],
			'limit' => ["integer"],
		])->validate();

		//$last_id = isset($request->last_id) ? $request->pag : null;
		$limit = isset($request->limit) ? $request->limit :4;
		$result = [];
		//$data = Delivery::where("user_id", $request->user()->id);
		$filter = null;
		switch ($request->status) {
			case "FINISHED":
				$filter = function ($query) {
					$query->where("estado", "=", "Enviado");//->orWhereNull("estado");
				};
			break;
		}
		$result["deliveries"] = $filter ? Delivery::where(function ($query) {
				global $request;
				if ($request->filled("last_id")) {
					$query->where("id", '<', $request->last_id);
				} else {
					$query;
				}
			})->where("driver_id", $request->user()->id)->where($filter)->orderBy('id', 'desc')
			->limit($limit)->get() : 
			[];
		if ($filter) $result["deliveries"]->load("recojo.place", "entrega.place", "plan", "carga");
		return response()->json($result);
	}

	public function pedidos(Request $request) {
		if (!$request->user()->driver) return new Response("", 403);
		Validator::make($request->all(), [
			'last_id' => ["integer"],
			'limit' => ["integer"],
		])->validate();

		//$last_id = isset($request->last_id) ? $request->pag : null;
		$limit = isset($request->limit) ? $request->limit :4;
		$result = [];
		//$data = Delivery::where("user_id", $request->user()->id);
		$filter = function ($query) {
			$query->whereNull("driver_id");//->orWhereNull("estado");
		};
		$result["deliveries"] = $filter ? Delivery::where(function ($query) {
				global $request;
				if ($request->filled("last_id")) {
					$query->where("id", '<', $request->last_id);
				} else {
					$query;
				}
			})->where($filter)->orderBy('id', 'desc')
			->limit($limit)->get() : 
			[];
		if ($filter) $result["deliveries"]->load("recojo.place", "entrega.place", "plan", "carga");
		return response()->json($result);
	}

	public function currentPedido(Request $request) {
		$result = [];
		$result["delivery"] = $request->user()->driver->currentPedido();
		return response()->json($result);
	}

	public function pedidosActuales(Request $request) {
		$result = [];
		$result["deliveries"] = $request->user()->driver->pedidosActuales();
		$result["deliveries"]->load("recojo.place", "entrega.place", "plan", "carga");
		return response()->json($result);
	}
}
