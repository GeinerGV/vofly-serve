<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
	/*protected $appends = ['recojo'];

	public function getRecojoAttribute() {
		return $this->recojo_model;
	}*/

    public function user() {
		return $this->belongsTo('App\User');
	}

	//public function 

    public function driver() {
		return $this->belongsTo('App\Driver');
	}

    public function recojo() {
		return $this->morphTo('recogible');
	}

    public function entrega() {
		return $this->morphTo('entregable');
	}

    public function carga() {
		return $this->morphTo('cargable');
	}

    public function plan() {
		return $this->belongsTo('App\Models\Pago\DeliveryPlan');
	}

    /*public function user() {
		return $this->belongsTo('App\User');
	}*/
}

