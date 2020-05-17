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
	}
}
