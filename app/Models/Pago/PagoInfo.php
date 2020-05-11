<?php

namespace App\Models\Pago;

use Illuminate\Database\Eloquent\Model;

class PagoInfo extends Model
{
    public function plan() {
		return $this->belongsTo('App\Models\Pago\Plan');
	}
}
