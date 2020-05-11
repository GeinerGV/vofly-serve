<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('implemented')->unsigned()->nullable();
            $table->timestamps();
			#Constraints
			$table->primary('user_id');
			$table->foreign('user_id')->references('id')->on('users');
			$table->foreign('implemented')->references('user_id')->on('admins');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
