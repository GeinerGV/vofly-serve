<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PlaceUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::dropIfExists('place_user');
        Schema::create('place_user', function (Blueprint $table) {
            $table->id();
            $table->integer("place_id")->unsigned();
            $table->unsignedBigInteger("user_id")->unsigned();
            $table->string('landmark', 255)->nullable();
            $table->string('fullname')->nullable();
            $table->string('phone', 13)->nullable();
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
        Schema::dropIfExists('place_user');
    }
}
