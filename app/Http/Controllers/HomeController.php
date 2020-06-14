<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Delivery;
use App\User;
use App\Driver;
use App\Models\Pago\DeliveryPlan;
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
        switch (request()->path()) {
            case 'dashboard':
                break;
            default:
                $request = request();
                # code...request()->filled(("pag"))
                Validator::make($request->all(), [
                    "len" => ["integer", "min:1"],
                    "pag" => ["integer", "min:1"],
                ])->validate();
                $len = isset($request->len) ? intval($request->len) : 15;
                $pag = isset($request->pag) ? intval($request->pag) : 1;
                switch (request()->path()) {
                    case 'pedidos':
                        $data = Delivery::class;
                        break;
                    case 'usuarios':
                        $data = User::class;
                        $pagination = User::doesntHave("admin")->doesntHave("driver")->paginate($len, ['*'], 'pag', $pag);
                        break;
                    case 'drivers':
                        $data = Driver::class;
                        if ($request->isMethod('post')) {
                            $result["alert"] = $this->driversPost($request);
                        }
                        break;
                    case 'pagos':
                        $data = DeliveryPlan::class;
                        if ($request->isMethod('post')) {
                            $validator = Validator::make($request->all(), [
                                "precio" => ["required"],
                                "nombre" => ["required"],
                                "descripcion" => ["required"],
                                "id" => ["required", "exists:delivery_plans"]
                            ]);
                            if (!$validator->fails()) {
                                $plan = $data::find($request->id);
                                $plan->nombre = $request->nombre;
                                $plan->precio = $request->precio;
                                $plan->limite = $request->limite;
                                $plan->descripcion = $request->descripcion;
                                $plan->save();
                                $result["alert"] = ["success", "Actualización exitosa"];
                            } else {
                                $result["alert"] = ["danger", "Error con los datos"];
                            }
                        }
                        break;
                }
                if (!$pagination) $pagination = $data::latest()->paginate($len, ['*'], 'pag', $pag);
                if ($len!=15) $pagination->appends(['len' => $len]);
                break;
        }
        $result["pagination"] = $pagination;
        if ($len) $result["maxlen"] = $len;
        if (View::exists('dashboard.'.request()->path())) $view = 'dashboard.'.request()->path();
        if (!request()->wantsJson()) {
            return view($view, $result);
        } else {
            return response()->json($result);
        }
        /* return !request()->wantsJson() ? view('home')
            : response()->json([]);
        ; */
    }

    public function driversPost(Request $request) {
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
                    'driverHabilitado'=>false,
                ]);
            }
            $ele->save();
            return ["success", "Actualización exitosa"];
        } else {
            return ["danger", "Error con los datos", $validator->errors()];
        }
    }

}
