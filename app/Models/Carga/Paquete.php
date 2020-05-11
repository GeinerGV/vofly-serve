<?php

namespace App\Models\Carga;

use Illuminate\Database\Eloquent\Model;

class Paquete extends Model
{
	public function delivery() {
		return $this->morphOne('App\Delivery', 'carga');
	}
}
