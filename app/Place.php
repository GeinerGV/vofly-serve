<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    /*public function ubicable() {
		return $this->morphTo();
	}*/

	public function updateData($data) {
		$this->direccion = $data["direccion"];
		$this->latitud = $data["region"]["latitude"];
		$this->longitud = $data["region"]["longitude"];
		$this->latitudDelta = $data["region"]["latitudeDelta"];
		$this->longitudDelta = $data["region"]["longitudeDelta"];
		$this->identificador = $data["identificador"];
		if (isset($data["nombre"])) $this->nombre = $data["nombre"];
	}
}
