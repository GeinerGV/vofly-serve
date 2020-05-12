<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateListaItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::create('lista_items', function (Blueprint $table) {
            $table->integerIncrements('id');
			$table->string('nombre', 255);
			$table->tinyInteger('cantidad')->unsigned();
			$table->string('unidad', 45)->nullable();
			$table->integer('lista_compra_id')->unsigned();
            $table->timestamps();

			$table->foreign('lista_compra_id')->references('lista_compras')->on('id');
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lista_items');
    }
}
