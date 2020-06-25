<?php

namespace App\Http\Controllers\API;

use App\Delivery;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Database;

class DriverController extends Controller
{

	public function driver(Request $request) {
		$user = $request->user();
		$user->load("driver");
	}

	public function location(Request $request) {
		$driver = $request->user()->driver;
		if ($request->isMethod('post')) {
			Validator::make($request->all(), [
				"location"=>["required", 'array']
			])->validate();
			if (!$driver->location || $driver->location["timestamp"]<=$request->location["timestamp"]) {
				$driver->location = $request->location;
			}
			$driver->save();
			if ($pedido=$driver->currentPedido()) {
				if (!$pedido->last_location_time || 
					(($pedido->last_location_time+Delivery::DELAY_UPDATE_LOCATION) <=
						$request->location["timestamp"]) )
				{
					$pedido->last_location_time = $request->location["timestamp"];
					$past = $pedido->location ? $pedido->location : [];
					$pedido->location = array_merge($past, [[
						"latitude"=>$request->location["coords"]["latitude"],
						"longitude"=>$request->location["coords"]["longitude"],
					]]);
					$pedido->save();
				}
			}
		}
		$result = [];
		$result["location"] = $driver->location;
		return response()->json($result);
	}

	public function isActive(Request $request) {
		$driver = $request->user()->driver;
		if ($request->isMethod('post')) {
			Validator::make($request->all(), [
				"isActive"=>["required", "boolean"]
			])->validate();
			$driver->activo = boolval($request->isActive);
			$driver->save();
			/**
			 * @var Database
			 */
			$db = app('firebase.database');
			$driverRef = $db->getReference('users/'.$request->user()->uid);
			$driverRef->update([
				"driverActive"=>$driver->activo,
			]);
		}
		$result = [];
		$result['status'] = "success";
		$result["isActive"] = $driver->activo;
		return response()->json($result);
	}

	public function iniciarPedido(Request $request) {
		$validacion = Validator::make($request->all(), [
			"id" => ["required", "integer"]
		]);
		$result = [];
		if(!$validacion->fails()) {
			$current = $request->user()->driver->currentPedido();
			if (!$current) {
				$delivery = Delivery::find($request->id);//Delivery::where("id", $request->id)->whereNull("driver_id")->first();
				if ($delivery && !$delivery->driver_id) {
					$delivery->driver()->associate($request->user()->driver);
					$delivery->estado = Delivery::STATUS_INICIADO;
					
					$location = $request->user()->driver->location;

					$past = $delivery->location ? $delivery->location : [];
					$delivery->location = array_merge($past, [[
						"latitude"=>$location["coords"]["latitude"],
						"longitude"=>$location["coords"]["longitude"],
					]]);

					
					$delivery->save();

					/**
					 * @var Database
					 */
					$db = app('firebase.database');
					$ref = $db->getReference('deliveries/'.$delivery->firebasekey);
					$delivery->load(['driver.user']);
					$snapshot = $ref->getSnapshot();
					$update = [];
					if (!$snapshot->exists()) {
						$update["recojo"] = ["lugar"=>["direccion"=>$delivery->recojo->place->direccion]];
						$update["entrega"] = ["lugar"=>["direccion"=>$delivery->entrega->place->direccion]];
						$update["carga"] = ["nombre"=>$delivery->carga->nombre];
					}
					$update = array_merge($update, [
						"estado"=>Delivery::STATUS_INICIADO,
						"driver"=>$delivery->driver,
						"user"=>$delivery->user,
					]);
					$ref->update($update);
					$ref = $db->getReference('users/'.$request->user()->uid);
					$ref->update([
						'currpedido'=>$delivery->id,
					]);
					if ($delivery->driver_id) $result["status"] = "success";
				} else {
					$result["status"] = "reservado";
				}
			}
		}
		return response()->json($result);
	}

	public function pedidoRecogido(Request $request) {
		$validacion = Validator::make($request->all(), [
			"id" => ["required", "integer"]
		]);
		$result = [];
		if(!$validacion->fails()) {
			$delivery = $request->user()->driver->currentPedido();
			if ($delivery && $delivery->id==$request->id) {
				$delivery->estado = Delivery::STATUS_RECOGIDO;
				$delivery->save();


				/**
				 * @var Database
				 */
				$db = app('firebase.database');
				$ref = $db->getReference('deliveries/'.$delivery->firebasekey);
				$delivery->load(['driver.user']);
				$ref->update([
					"estado"=>Delivery::STATUS_RECOGIDO,
				]);
				/* $ref = $db->getReference('users/'.$request->user()->uid);
				$ref->update([
					'currpedido'=>$delivery->id,
				]); */
				$result["status"] = "success";
			}
		}
		return response()->json($result);
	}

	public function pedidoEntregado(Request $request) {
		$validacion = Validator::make($request->all(), [
			"id" => ["required", "integer"]
		]);
		$result = [];
		if(!$validacion->fails()) {
			$delivery = $request->user()->driver->currentPedido();
			if ($delivery && $delivery->id==$request->id && 
					$delivery->estado==Delivery::STATUS_RECOGIDO) {
				$delivery->estado = Delivery::STATUS_ENTREGADO;
				$delivery->save();


				/**
				 * @var Database
				 */
				$db = app('firebase.database');
				$ref = $db->getReference('deliveries/'.$delivery->firebasekey);
				$delivery->load(['driver.user']);
				$ref->update([
					"estado"=>Delivery::STATUS_ENTREGADO,
				]);
				$ref->remove();
				$ref = $db->getReference('users/'.$request->user()->uid);
				$ref->update([
					'currpedido'=>null,
				]);
				$result["status"] = "success";
			}
		}
		return response()->json($result);
	}
}
