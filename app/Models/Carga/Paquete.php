<?php

namespace App\Models\Carga;

use Illuminate\Database\Eloquent\Model;

class Paquete extends Model
{
	public function delivery() {
		return $this->morphOne('App\Delivery', 'carga');
	}

	public function updateData($data) {
		//$recojo = new static;
		$this->fragil = $data["fragil"];
		$this->tipo = $data["tipo"];
		if (isset($data["peso"]) && $data["peso"]>0) $this->peso = $data["peso"];
		if (isset($data["volumen"],
			$data["volumen"]["alto"], $data["volumen"]["ancho"], $data["volumen"]["largo"]
		)) {
			$this->alto = $data["volumen"]["alto"];
			$this->ancho = $data["volumen"]["ancho"];
			$this->largo = $data["volumen"]["largo"];
		}
		$this->precio = $data["precio"];
		$this->nombre = $data["nombre"];
	}
}
