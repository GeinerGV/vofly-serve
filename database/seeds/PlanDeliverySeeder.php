<?php

use App\Models\Pago\DeliveryPlan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
			'precio' => 23,
			'nombre' => "Económico",
			'descripcion' => 'Bla Bla'
		]);
	}
}
