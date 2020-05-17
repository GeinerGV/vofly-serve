<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaquetesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paquetes', function (Blueprint $table) {
            $table->integerIncrements('id');
			$table->smallInteger('alto')->unsigned()->nullable();
			$table->smallInteger('ancho')->unsigned()->nullable();
			$table->smallInteger('largo')->unsigned()->nullable();
			$table->smallInteger('peso')->unsigned()->nullable();
			$table->tinyInteger('fragil')->unsigned();
			$table->string('tipo', 50);
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
        Schema::dropIfExists('paquetes');
    }
}
