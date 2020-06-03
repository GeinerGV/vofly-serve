<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('delivery_plans');
        Schema::create('delivery_plans', function (Blueprint $table) {
            $table->tinyIncrements('id');
			$table->decimal('precio', 8, 2, true);
			$table->string('nombre', 50);
			$table->string('descripcion', 300);
			$table->decimal('limite', 8, 2, true)->nullable();
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
        Schema::dropIfExists('delivery_plans');
    }
}
