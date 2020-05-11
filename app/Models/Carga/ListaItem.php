<?php

namespace App\Models\Carga;

use Illuminate\Database\Eloquent\Model;

class ListaItem extends Model
{
    public function lista() {
		return $this->belongsTo('App\Models\Carga\ListaCompra', 'lista_compra_id');
	}
}
