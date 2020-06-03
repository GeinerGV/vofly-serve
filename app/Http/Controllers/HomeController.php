<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Delivery;
use App\User;
use App\Driver;
use App\Models\Pago\DeliveryPlan;
use Illuminate\Support\Facades\View;

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
                                $plan = DeliveryPlan::find($request->id);
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
                if (!$pagination) $pagination = $data::paginate($len, ['*'], 'pag', $pag);
                if ($len!=15) $pagination->appends(['len' => $len]);
                break;
        }
        $result["pagination"] = $pagination;
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
}
