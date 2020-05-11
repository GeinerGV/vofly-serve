<?php

namespace App\Models\Pago;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    public function pagos() {
		return $this->hasMany('App\Models\Pago\PagoInfo');
	}
}
