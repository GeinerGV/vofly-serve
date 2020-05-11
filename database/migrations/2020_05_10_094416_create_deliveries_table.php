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
			#user(User): belongsTo
			$table->integer('user_id')->unsigned(); // Delivery
			#driver(Driver): belongsTo
			$table->integer('driver_id')->unsigned()->nullable(); // Delivery
			#recojo(Agente): belongsTo
			$table->integer('recojo_id')->unsigned();
			$table->integer('recojo_type')->unsigned();
			#entrega(Agente): belongsTo
			$table->integer('entrega_id')->unsigned();
			$table->integer('entrega_type')->unsigned();
			#carga(Paquete): belongsTo
			$table->integer('paquete_id')->unsigned();
			$table->integer('paquete_type')->unsigned();
			#pago(PagoInfo):
			$table->integer('pago_id')->unsigned();
			#extradata
			$table->string('distancia')->nullable();
			$table->string('detalles', 300)->nullable(); // Courier detail
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
