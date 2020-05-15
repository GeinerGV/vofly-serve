<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    public function user() {
		return $this->belongsTo('App\User');
	}

    public function driver() {
		return $this->belongsTo('App\Driver');
	}

    public function recojo() {
		return $this->morphTo();
	}

    public function entrega() {
		return $this->morphTo();
	}

    public function paquete() {
		return $this->morphTo();
	}

    /*public function pago() {
		//return $this->belongsTo('App\PagoInfo');
	}*/
}

