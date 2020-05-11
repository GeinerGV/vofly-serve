<?php

namespace App\Models\Destino;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    public function lugar() {
		return $this->morphOne('App\Place', 'ubicable');
	}

	public function deliveriesorigen() {
		return $this->morphMany('App\Delivery', 'recojo');
	}
}
