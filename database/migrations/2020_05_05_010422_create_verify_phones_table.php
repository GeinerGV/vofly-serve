<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVerifyPhonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('verify_phones', function (Blueprint $table) {
            $table->mediumIncrements("id");
            $table->string("phone", 13);
            $table->string("code", 6);
            $table->string("verify_id", 36);
            $table->tinyInteger("reintentos", false, true)->default(0);
			$table->string("ip", 45);
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
        Schema::dropIfExists('verify_phones');
    }
}
