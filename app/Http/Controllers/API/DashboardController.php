<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

use App\Delivery;
use App\User;
use App\Driver;

class DashboardController extends Controller
{
	function lapsoTiempoData(Request $request) {
		Validator::make($request->all(), [
			"len" => ["required", "integer", "min:1"],
			"time_type" => ["required", "string", "in:".implode(",", ["dia", "mes"])],
			"data_type" => ["required", "string", "in:".implode(",", ["pedidos", 'drivers', 'usuarios'])]
		])->validate();
		switch ($request->data_type) {
			case 'pedidos':
				$data = Delivery::class;
				break;
			case 'usuarios':
				$data = User::class;
				break;
			case 'drivers':
				$data = Driver::class;
				break;
		}
		$result = [];

		switch($request->time_type) {
			case "dia":
				$dateInit = Date::now()->subDays($request->len);
				$rawsSelect = [
					DB::raw('DATE(created_at) AS date'),
					DB::raw('COUNT(*) AS count'),
				];
			break;
			case "mes":
				$dateInit = Date::now()->subMonths($request->len);
				$rawsSelect = [
					DB::raw('COUNT(*) AS count'),
					DB::raw('YEAR(created_at) AS year'),
					DB::raw('MONTH(created_at) AS month'),
				];

			break;
			default:
				$dateInit = Date::now();
		}

		$query = $data::select($rawsSelect)
		 ->whereBetween('created_at', [$dateInit, Date::now()]);
		switch ($request->time_type) {
			case "dia":
				$query = $query
					->groupBy('date')
					->orderBy('date', 'ASC');
			break;
			case "mes":
				$query = $query
					->groupBy('year', 'month')
					->orderBy('year', 'ASC')
					->orderBy('month', 'ASC');
			break;
		}
		$result["data"] = $query
			->get()
			->toArray();
		return response()->json($result);
	}

	function pagination(Request $request) {
		Validator::make($request->all(), [
			"len" => ["integer", "min:1"],
			"pag" => ["required", "integer", "min:1"],
			"data_type" => ["string", "in:".implode(",", ["pedidos", 'drivers', 'usuarios'])]
		])->validate();
		$data_type = isset($request->data_type) ? $request->data_type : "pedidos";
		$len = isset($request->len) ? $request->len : 15;
		switch ($data_type) {
			case 'pedidos':
				$data = Delivery::class;
				break;
			case 'usuarios':
				$data = User::class;
				break;
			case 'drivers':
				$data = Driver::class;
				break;
		}

		$result = [];
		$pagination = $data::paginate($len, ['*'], 'pag', $request->pag);
		/* for ($i=1; $i < $request->pag; $i++) { 
			# code...
		} */
		$result["data"] = $pagination;
		return response()->json($result);
	}
}
