<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Delivery;
use App\User;
use App\Driver;
use App\Models\Pago\DeliveryPlan;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\View;
use Kreait\Firebase\Database;

class HomeController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Contracts\Support\Renderable
	 */
	public function index()
	{
		$pagination = null;
		$view = "home";
		$result = [];
		$len = null;
		$withPagination = true;
		switch (request()->path()) {
			case 'dashboard':
				break;
			default:
				$request = request();
				# code...request()->has(("pag"))
				Validator::make($request->all(), [
					"len" => ["integer", "min:1"],
					"pag" => ["integer", "min:1"],
				])->validate();
				$len = isset($request->len) ? intval($request->len) : 15;
				$pag = isset($request->pag) ? intval($request->pag) : 1;
				if ($request->isMethod('post') && (!$request->has("delete") && !$request->has("create"))) $withPagination = false;
				switch (request()->path()) {
					case 'pedidos':
						$data = Delivery::class;
						if ($request->isMethod('post')) {
							$result["alert"] = $this->pedidoPost($request);
						}
						$pagination = $data::latest()->paginate($len, ['*'], 'pag', $pag);
						foreach ($pagination as &$item) {
							$item->load("user", "driver", "carga", "recojo.place", "plan", "entrega.place");
						}
						break;
					case 'usuarios':
						$data = User::class;
						if ($request->isMethod('post')) {
							$result["alert"] = $this->usuarioPost($request);
						}
						if ($withPagination) $pagination = User::doesntHave("admin")->doesntHave("driver")
							->latest()->paginate($len, ['*'], 'pag', $pag);
						break;
					case 'drivers':
						$data = Driver::class;
						if ($request->isMethod('post')) {
							$result["alert"] = $this->driversPost($request);
						}
						$pagination = $data::latest()->paginate($len, ['*'], 'pag', $pag);
						foreach ($pagination as &$item) {
							$item->load("user");
						}
						break;
					case 'pagos':
						$data = DeliveryPlan::class;
						if ($request->isMethod('post')) {
							$result["alert"] = $this->pagosPost($request);
						}
						break;
				}
				if ($withPagination && !$pagination) $pagination = $data::latest()->paginate($len, ['*'], 'pag', $pag);
				if ($len!=15) $pagination->appends(['len' => $len]);
				break;
		}
		if ($withPagination && $pagination) {
			$result["pagination"] = $pagination;
			$result['row'] = ($pagination->currentPage()-1)*$len;
		}
		if ($len) $result["maxlen"] = $len;
		if (View::exists('dashboard.'.request()->path())) $view = 'dashboard.'.request()->path();
		if (!request()->wantsJson()) {
			$result["pagination"] = $pagination;
			return view($view, $result);
		} else {
			if (isset($result['alert'])) $result['status'] = $result['alert'][0];
			return response()->json($result);
		}
		/* return !request()->wantsJson() ? view('home')
			: response()->json([]);
		; */
	}

	public function dontSupportDelete() {
		return ["danger", "Este registro aún no está habilitado para su eliminación"];
	}

	public function dontSupportCreate() {
		return ["danger", "Aún no está habilitado"];
	}

	public function pedidoPost(Request $request) {
		if ($request->has('create')) {
			return $this->dontSupportCreate();
		} else if ($request->has('delete')) {
			$validator = Validator::make($request->all(), [
				"id" => ["required", "exists:deliveries"]
			]);
			try {
				if (!$validator->fails()) {
					$delivery = Delivery::find($request->id);
					$delivery->delete();
					return ["success", "Se eliminó correctamente"];
				} else {
					return ["danger", "Error con los datos", $validator->errors()];
				}
			} catch (\Throwable $th) {
				$delivery = Delivery::find($request->id);
				if ($delivery) {
					return ["danger", "El registro no se logró eliminar"];
				} else {
					return ["success", "Se eliminó el registro"];
				}
			}
		} else {
			$validator = Validator::make($request->all(), [
				"trackid" => ["string", "min:4", "max:16", "unique:deliveries"],
				'user' => array_merge(User::PHONE_BASIC_VALIDATE_RULES),
				'driver' => $request->driver ? array_merge(Driver::DNI_BASIC_VALIDATE_RULES, ['exists:drivers,dni']) : [],
				"id" => ["required", "exists:deliveries"]
			]);
			$phone = "+51" . $request->user;
			$validator2 = Validator::make(["user" => $phone], $request->has("user") ? [
				"user" => ['exists:users,phone']
			] : []);
			if (!$validator->fails() && !$validator2->fails()) {
				$delivery = Delivery::find($request->id);
				if ($request->has('trackid')) {
					$delivery->trackid = $request->trackid;
				}
				if ($request->has('driver')) {
					if ($request->driver) {
						$driver = Driver::where("dni", $request->driver)->first();
						if ($driver) {
							$delivery->driver_id = $driver->id;
						}
					} else {
						$delivery->driver_id = null;
					}
					$delivery->load("driver");
				}
				if ($request->has('user')) {
					$user = User::where("phone", $phone)->first();
					if ($user) {
						$delivery->user_id = $user->id;
						$delivery->load("user");
					}
				}
				$delivery->save();
				return ["success", "Actualización exitosa", $delivery];
			} else {
				return ["danger", "Error con los datos", array_merge(
					$validator->errors()->toArray(), $validator2->errors()->toArray()
				)];
			}
		}
	}

	public function pagosPost(Request $request) {
		if ($request->has('create')) {
			$validator = Validator::make($request->all(), [
				"precio" => ["required", "numeric", "gt:0.005"],
				"nombre" => ["required", "string", "unique:delivery_plans"],
				"descripcion" => ["required", "string"],
			]);
			if (!$validator->fails()) {
				$plan = new DeliveryPlan;
				$plan->nombre = $request->nombre;
				$plan->precio = $request->precio;
				$plan->limite = floatval($request->limite)>=0.005 ? floatval($request->limite) : null;
				$plan->descripcion = $request->descripcion;
				$plan->save();
				return ["success", "Registro exitoso", $plan];
			} else {
				return ["danger", "Error con los datos", $validator->errors()];
			}
		} else if ($request->has('delete')) {
			//return ["danger", "Todavía no se puede eliminar la sección de planes de pago"];
			$validator = Validator::make($request->all(), [
				"id" => ["required", "exists:delivery_plans"]
			]);
			try {
				if (!$validator->fails()) {
					$plan = DeliveryPlan::find($request->id);
					$plan->delete();
					return ["success", "Se eliminó correctamente"];
				} else {
					return ["danger", "Error con los datos", $validator->errors()];
				}
			} catch (\Throwable $th) {
				$plan = DeliveryPlan::find($request->id);
				if ($plan) {
					return ["danger", "El registro no se logró eliminar"];
				} else {
					return ["success", "Se eliminó el registro"];
				}
			}
		} else {
			$validator = Validator::make($request->all(), [
				"precio" => ["numeric", "gt:0.005"],
				"nombre" => ["string", "unique:delivery_plans"],
				"descripcion" => ["string"],
				"id" => ["required", "exists:delivery_plans"]
			]);
			if (!$validator->fails()) {
				$plan = DeliveryPlan::find($request->id);
				if($request->has('nombre')) $plan->nombre = $request->nombre;
				if($request->has('precio')) $plan->precio = $request->precio;
				if($request->has('limite')) $plan->limite = floatval($request->limite)>=0.005 ? floatval($request->limite) : null;
				if($request->has('descripcion')) $plan->descripcion = $request->descripcion;
				$plan->save();
				return ["success", "Actualización exitosa"];
			} else {
				return ["danger", "Error con los datos", $validator->errors()];
			}
		}
	}

	static function usuarioCreateValidators(array $data) {
		$phone = "+51" . (isset($data["phone"]) ? $data["phone"] : "");
		return [
			Validator::make($data, [
				'name' => array_merge(["required"], User::NAME_BASIC_VALIDATE_RULES),
				'phone' => array_merge(["required"], User::PHONE_BASIC_VALIDATE_RULES),
				'direccion' => array_merge(["required"], User::DIRECCION_BASIC_VALIDATE_RULES),
				'email' => array_merge(["required"], User::EMAIL_BASIC_VALIDATE_RULES, ['unique:users']),
			]),
			Validator::make(["phone" => $phone], [
				"phone" => ['unique:users']
			])
		];
	}

	public function usuarioPost(Request $request) {
		if ($request->has('create')) {
			$validators = static::usuarioCreateValidators($request->all());
			$validator =  $validators[0];
			$validator2 = $validators[1];
			if (!$validator->fails() && !$validator2->fails()) {
				$user = null;
				try {
					$auth = app('firebase.auth');
					$phone = "+51" . $request->phone;
					$userProperties = [
						'email' => $request->email,
						'emailVerified' => false,
						'phoneNumber' => $phone,
						'displayName' => $request->name,
						'disabled' => false,
					];
					$firebaseUser = $auth->createUser($userProperties);
					$user = new User;
					$user->email = $request->email;
					$user->phone = $phone;
					$user->name = $request->name;
					$user->direccion = $request->direccion;
					$user->uid = $firebaseUser->uid;
					$user->api_token = $firebaseUser->uid;
					$user->save();
				
					event(new Registered($user));
					$this->guard()->login($user);
					return ["success", "Se registró un nuevo usuario", $user];
				} catch (\Throwable $th) {
					if (isset($user) && isset($user->id)) {
						return ["success", "Se registró un nuevo usuario", $user];
					} else {
						return ["danger", "Hubo inconvenientes al momento de registrar",null, $th];
					}
				}
			} else {
				return ["danger", "Error con los datos", array_merge(
					$validator->errors()->toArray(), $validator2->errors()->toArray()
				)];
			}
		} else if ($request->has('delete')) {
			$validator = Validator::make($request->all(), [
				"id" => ["required", "exists:users"]
			]);
			try {
				if (!$validator->fails()) {
					try {
						/**
						 * @var User
						 */
						$user = User::find($request->id);
						if ($user->driver) {
							$user->driver->delete();
						}
						$uid = $user->uid;
						$auth = app('firebase.auth');
						$user->delete();
						$auth->deleteUser($uid);
						return ["success", "Se eliminó correctamente"];
					} catch (\Throwable $th) {
						return ["danger", "Hubo problemas durante en el intento."];
					}
				} else {
					return ["danger", "Error con los datos", $validator->errors()];
				}
			} catch (\Throwable $th) {
				$user = User::find($request->id);
				if ($user) {
					return ["danger", "El registro no se logró eliminar"];
				} else {
					return ["success", "Se eliminó el registro"];
				}
			}
		} else {
			$validator = Validator::make($request->all(), [
				'name' => array_merge(User::NAME_BASIC_VALIDATE_RULES),
				'phone' => array_merge(User::PHONE_BASIC_VALIDATE_RULES),
				'direccion' => array_merge(User::DIRECCION_BASIC_VALIDATE_RULES),
				'email' => array_merge(User::EMAIL_BASIC_VALIDATE_RULES, ['unique:users']),
				"id" => ["required", "exists:users"]
			]);
			$phone = "+51" . $request->phone;
			$validator2 = Validator::make(["phone" => $phone], $request->filled("phone") ? [
				"phone" => ['unique:users']
			] : []);
			// , ['unique:users']
			if (!$validator->fails()) {
				$user = User::find($request->id);
				$prevUser = array_merge([], $user->toArray());
				$result = null;
				$updateFirebase = [];
				try {
					if ($request->has('name')) {
						$user->name = $request->name;
						$updateFirebase['displayName'] = $request->name;
					}
					if ($request->has('phone')) {
						$phone = "+51".$request->phone;
						$user->phone = $phone;
						$updateFirebase['phoneNumber'] = $phone;
					}
					if ($request->has('direccion')) {
						$user->direccion = $request->direccion;
					}
					if ($request->has('email')) {
						$user->email = $request->email;
						$updateFirebase['email'] = $request->email;
					}
					$user->save();
					$auth = app('firebase.auth');
					$auth->updateUser($user->uid, $updateFirebase);
					$result = ["success", "Actualización exitosa"];
				} catch (\Throwable $th) {
					/* if ($request->has('name')) {
						$user->name = $request->name;
						$updateFirebase['displayName'] = $request->name;
					}
					if ($request->has('phone')) {
						$phone = "+51".$request->phone;
						$user->phone = $phone;
						$updateFirebase['phoneNumber'] = $phone;
					} */
					if ($request->has('email')) {
						$user->email = $prevUser["email"];
						$result = ["danger", "Error con los datos", ["email"=>["Email no válido"]]];
					}
					$user->save();
					
				}
				return $result;
			} else {
				$errors = $validator->errors();
				//if ($validator2->fails()) $errors = $validator2->errors();
				return ["danger", "Error con los datos", $errors];
			}
		}
	}

	public function driversPost(Request $request) {
		if ($request->has('create')) {
			$validators = static::usuarioCreateValidators($request->all());
			$validator =  $validators[0];
			$validator2 = $validators[1];
			$validator3 = Validator::make($request->all(), [
				'dni' => array_merge(["required"], Driver::DNI_BASIC_VALIDATE_RULES, ['unique:drivers']),
			]);
			if (!$validator->fails() && !$validator2->fails() && !$validator3->fails()) {
				$driver = null;
				$alert = null;
				$user = null;
				try {
					$alert = $this->usuarioPost($request);
					if ($alert[0]=="success") {
						$user = $alert[2];
						$driver = new Driver;
						$driver->dni = $request->dni;
						$driver->verified_at = $request->boolean("verified_at") ? Date::now() : null;
						$driver->user()->associate($user);
						$driver->push();
						$driver->id = $user->id;
						$driver->save();
						$driver->load("user");
						return ["success", "Se registró un nuevo driver", $driver];
					} else {
						return $alert;
					}
				} catch (\Throwable $th) {
					if (isset($driver) && isset($driver->id)) {
						return ["success", "Se registró un nuevo usuario", $driver];
					} else {
						if (isset($user) && isset($user->id)) {
							$user->delete();
						}
						return ["danger", "Hubo inconvenientes al momento de registrar",null, $th];
					}
				}
			} else {
				return ["danger", "Error con los datos", array_merge(
					$validator->errors()->toArray(), $validator2->errors()->toArray(), $validator3->errors()->toArray()
				)];
			}
		} else if ($request->has('delete')) {
			return $this->usuarioPost($request);
		} else {
			$validator = Validator::make($request->all(), [
				'name' => array_merge(User::NAME_BASIC_VALIDATE_RULES),
				'phone' => array_merge(User::PHONE_BASIC_VALIDATE_RULES, ['unique:users']),
				'direccion' => array_merge(User::DIRECCION_BASIC_VALIDATE_RULES),
				'email' => array_merge(User::EMAIL_BASIC_VALIDATE_RULES, ['unique:users']),
				'verified_at' => ["boolean"],
				'dni' => Driver::DNI_BASIC_VALIDATE_RULES,
				"id" => ["required", "exists:drivers"]
			]);
			if (!$validator->fails()) {
				$alert = $this->usuarioPost($request);
				if ($alert[0]=="success") {
					$ele = Driver::find($request->id);
					if ($request->has("dni")) $ele->dni = $request->dni;
					if ($request->has("verified_at")) {
						/**
						 * @var Database
						 */
						$db = app('firebase.database');
						$userRef = $db->getReference('users/'.$ele->user->uid);
						if ($ele->verified_at && !$request->verified_at) {
							$ele->verified_at = null;
							$ele->activo = false;
							$userRef->update([
								'driverHabilitado'=>false,
								'driverActive'=>false,
							]);
						} else if (!$ele->verified_at && $request->verified_at) {
							$ele->verified_at = Date::now();
							$ele->activo = false;
							$userRef->update([
								'driverHabilitado'=>true,
								'driverActive'=>false,
							]);
						}
					}
					$ele->save();
					return ["success", "Actualización exitosa"];
				} else {
					return $alert;
				}
			} else {
				return ["danger", "Error con los datos", $validator->errors()];
			}
		}
	}

}
