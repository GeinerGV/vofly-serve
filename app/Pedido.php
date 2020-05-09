<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    public function user() {
		return $this->belongsTo('App\User');
	}

    public function driver() {
		return $this->belongsTo('App\Driver');
	}

    public function recojo() {
		return $this->belongsTo('App\PlaceWAgent');
	}

    public function entrega() {
		return $this->belongsTo('App\PlaceWAgent');
	}

    public function paquete() {
		return $this->belongsTo('App\Paquete');
	}

    public function pago() {
		return $this->belongsTo('App\PagoInfo');
	}
}
