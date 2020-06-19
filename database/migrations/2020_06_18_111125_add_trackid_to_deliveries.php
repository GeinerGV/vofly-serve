<?php

use App\Delivery;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTrackidToDeliveries extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('deliveries', function (Blueprint $table) {
			$table->string('trackid', 16)
				->nullable()
				->default(null);
		});
		/**
		 * @var Delivery[]
		 */
		$deliveries = Delivery::whereNull("trackid")->get();
		foreach ($deliveries as $value) {
			$value->trackid = hash('adler32', $value->id) . hash('adler32', time());
			$value->save();
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('deliveries', function (Blueprint $table) {
			//
		});
	}
}
