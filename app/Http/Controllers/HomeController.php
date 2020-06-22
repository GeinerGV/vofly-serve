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
		return ["danger", "Aún no está habilitado"];
	}

	public function pagosPost(Request $request) {
		if ($request->has('create')) {
			return $this->dontSupportCreate();
		} else if ($request->has('delete')) {
			return $this->dontSupportDelete();
		} else if ($request->has('delete')) {
			return ["danger", "Todavía no se puede eliminar la sección de planes de pago"];
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
				"precio" => ["numeric"],
				"nombre" => ["string"],
				"descripcion" => ["string"],
				"id" => ["required", "exists:delivery_plans"]
			]);
			if (!$validator->fails()) {
				$plan = DeliveryPlan::find($request->id);
				if($request->has('nombre')) $plan->nombre = $request->nombre;
				if($request->has('precio')) $plan->precio = $request->precio;
				if($request->has('limite')) $plan->limite = $request->limite;
				if($request->has('descripcion')) $plan->descripcion = $request->descripcion;
				$plan->save();
				return ["success", "Actualización exitosa"];
			} else {
				return ["danger", "Error con los datos", $validator->errors()];
			}
		}
	}

	public function usuarioPost(Request $request) {
		if ($request->has('create')) {
			$validator =  Validator::make(
				$request->all()
			, [
				'name' => array_merge(User::NAME_BASIC_VALIDATE_RULES),
				'phone' => array_merge( User::PHONE_BASIC_VALIDATE_RULES),
				'direccion' => array_merge(User::DIRECCION_BASIC_VALIDATE_RULES),
				'email' => array_merge(User::EMAIL_BASIC_VALIDATE_RULES, ['unique:users']),
			]);
			$phone = "+51" . $request->phone;
			$validator2 = Validator::make(["phone" => $phone], [
				"phone" => ['unique:users']
			]);
			if (!$validator->fails() && !$validator2->fails()) {
				$user = null;
				try {
					$auth = app('firebase.auth');
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
					$user->direccion = $user->direccion;
					$user->uid = $firebaseUser->uid;
					$user->api_token = $firebaseUser->uid;
					$user->save();
				
					event(new Registered($user));
					$this->guard()->login($user);
					return ["success", "Se registró un nuevo usuario", $user];
				} catch (\Throwable $th) {
					if ($user && isset($user->id)) {
						return ["success", "Se registró un nuevo usuario", $user];
					} else {
						return ["danger", "Hubo inconvenientes al momento de registrar"];
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
						$user = User::find($request->id);
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
				'phone' => array_merge(User::PHONE_BASIC_VALIDATE_RULES, ['unique:users']),
				'direccion' => array_merge(User::DIRECCION_BASIC_VALIDATE_RULES),
				'email' => array_merge(User::EMAIL_BASIC_VALIDATE_RULES, ['unique:users']),
				"id" => ["required", "exists:users"]
			]);
			$user = User::find($request->id);
			if (!$validator->fails()) {
				$userPrev = array_merge($user->toArray());
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
					/*$user->name = $userPrev['name'];
					$updateFirebase['displayName'] = $userPrev['name'];
					$user->phone = $userPrev['phone'];
					$updateFirebase['phoneNumber'] = $userPrev['phone'];
					$user->direccion = $userPrev['direccion'];
					$user->email = $userPrev['email'];
					$updateFirebase['email'] = $userPrev['email'];*/
					$result = ["danger", "Error con los datos", "El teléfono o email pertenecen a otros usuarios"];
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
			return $this->dontSupportCreate();
		} else if ($request->has('delete')) {
			return $this->dontSupportDelete();
		}
		$validator = Validator::make($request->all(), [
			"dni" => ["required", "digits:8"],
			"id" => ["required", "exists:drivers"]
		]);
		if (!$validator->fails()) {
			$ele = Driver::find($request->id);
			$ele->dni = $request->dni;
			if ($ele->verified_at && !$request->habilitado) {
				$ele->verified_at = null;
				$ele->activo = false;
				/**
				 * @var Database
				 */
				$db = app('firebase.database');
				$userRef = $db->getReference('users/'.$ele->user->uid);
				$userRef->update([
					'driverHabilitado'=>false,
					'driverActive'=>false,
				]);
			} else if (!$ele->verified_at && $request->habilitado) {
				$ele->verified_at = Date::now();
				$ele->activo = false;
				/**
				 * @var Database
				 */
				$db = app('firebase.database');
				$userRef = $db->getReference('users/'.$ele->user->uid);
				$userRef->update([
					'driverHabilitado'=>true,
					'driverActive'=>false,
				]);
			}
			$ele->save();
			return ["success", "Actualización exitosa"];
		} else {
			return ["danger", "Error con los datos", $validator->errors()];
		}
	}

}
