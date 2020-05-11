<?php

namespace App\Models\Carga;

use Illuminate\Database\Eloquent\Model;

class ListaCompra extends Model
{
	public function items() {
		return $this->hasMany('App\Models\Carga\ListaItem', 'lista_compra_id');
	}
}
