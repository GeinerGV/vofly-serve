<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->integerIncrements('id'); // Delivery
			#user(User): belongsTo
			$table->integer('user_id')->unsigned(); // Delivery
			#driver(Driver): belongsTo
			$table->integer('driver_id')->unsigned()->nullable(); // Delivery
			#recojo(Agente): belongsTo
			$table->integer('recojo_id')->unsigned();
			#entrega(Agente): belongsTo
			$table->integer('entrega_id')->unsigned();
			#carga(Paquete): belongsTo
			$table->integer('paquete_id')->unsigned();
			#pago(PagoInfo):
			$table->integer('pago_id')->unsigned();
			#extradata
			$table->string('distancia')->nullable();
			$table->string('detalles', 300)->nullable(); // Courier detail
			#$table->tinyInteger('confirmed')->unsigned();
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
        Schema::dropIfExists('pedidos');
    }
}
