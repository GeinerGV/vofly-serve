<?php

namespace App\Models\Pago;

use Illuminate\Database\Eloquent\Model;

class DeliveryPlan extends Model
{
    public function deliveries() {
		return $this->hasMany('App\Delivery');
	}
}
