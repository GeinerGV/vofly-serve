<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->integerIncrements('id');
			$table->string('type');
			#user(User): belongsTo
			$table->integer('user_id')->unsigned(); // Delivery
			#driver(Driver): belongsTo
			$table->integer('driver_id')->unsigned()->nullable(); // Delivery
			#recojo(Agente): belongsTo
			$table->integer('recogible_id')->unsigned();
			$table->string('recogible_type');
			#entrega(Agente): belongsTo
			$table->integer('entregable_id')->unsigned();
			$table->string('entregable_type');
			#carga(Paquete): belongsTo
			$table->integer('cargable_id');
			$table->string('cargable_type');
			#pago(PagoInfo):
			$table->integer('plan_id')->unsigned();
			#extradata
			$table->string('distancia')->nullable();
			$table->string('detalles_carga', 300)->nullable(); // Courier detail
			$table->string('estado', 25)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deliveries');
    }
}
