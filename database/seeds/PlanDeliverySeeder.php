<?php

use App\Models\Pago\DeliveryPlan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;

class PlanDeliverySeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		if (!DeliveryPlan::where("nombre", "Económico")->first()) DB::table('delivery_plans')->insert([
			'precio' => 8.6,
			'nombre' => "Económico",
			'descripcion' => '2 a 3 días',
			"created_at" => Date::now(),
			"updated_at" => Date::now(),
		]);
		if (!DeliveryPlan::where("nombre", "Delux")->first()) DB::table('delivery_plans')->insert([
			'precio' => 12.5,
			'limite' => 2000,
			'nombre' => "Delux",
			'descripcion' => '2 a 3 días',
			"created_at" => Date::now(),
			"updated_at" => Date::now(),
		]);
		if (!DeliveryPlan::where("nombre", "Premium")->first()) DB::table('delivery_plans')->insert([
			'precio' => 20,
			'limite' => 2000,
			'nombre' => "Premium",
			'descripcion' => '2 a 3 días',
			"created_at" => Date::now(),
			"updated_at" => Date::now(),
		]);
	}
}
