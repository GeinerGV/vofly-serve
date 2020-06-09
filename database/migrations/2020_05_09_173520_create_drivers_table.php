<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('drivers');
        Schema::create('drivers', function (Blueprint $table) {
            $table->integer('id')->unsigned();
			$table->string('dni', 8);
			$table->boolean('activo')->nullable();
            $table->timestamps();
			#Constraints
			$table->primary('id');
			$table->foreign('id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('drivers');
    }
}
