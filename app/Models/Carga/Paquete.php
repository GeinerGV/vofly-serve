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
			$data["volumen"]["hei"], $data["volumen"]["wid"], $data["volumen"]["len"]
		)) {
			$this->alto = $data["volumen"]["hei"];
			$this->ancho = $data["volumen"]["wid"];
			$this->largo = $data["volumen"]["len"];
		}
	}
}
