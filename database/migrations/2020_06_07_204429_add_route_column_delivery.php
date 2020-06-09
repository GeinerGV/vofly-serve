<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRouteColumnDelivery extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('deliveries', function (Blueprint $table) {
			$table->json('trayectoria')
				->nullable()
				->default(null);
			$table->json('location')
				->nullable()
				->default(null);
				/* $table->removeColumn("trayectoria"); */
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}
}
