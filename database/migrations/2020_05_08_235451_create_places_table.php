<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::create('places', function (Blueprint $table) {
            $table->id();
			$table->string('latitud');
			$table->string('longitud');
			$table->string('place_id', 255);
			$table->string('nombre', 255);
			$table->string('direccion', 255)->nullable();
			$table->integer('ubicable_id')->unsigned();
			$table->string('ubicable_type');
            $table->timestamps();
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('registered_places');
    }
}
