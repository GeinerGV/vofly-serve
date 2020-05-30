<?php

namespace App\Models\Destino;

use App\Place;

use Illuminate\Database\Eloquent\Model;

class Agente extends Model
{
    public function place() {
		return $this->belongsTo('App\Place');
	}

	public function delivery() {
		return $this->morphOne('App\Delivery', strtolower($this->destino_type));
	}

	public function updateData($data, string $type) {
		$this->fullname = $data["fullname"];
		$this->landmark = $data["landmark"];
		$this->phone = $data["phone"];
		$this->destino_type = $type;
		
		if (isset($data["lugar"]["id"])) {
			$lugar = Place::find($data["lugar"]["id"]);
		} else {
			$lugar = new Place;
			$lugar->updateData($data["lugar"]);
			$lugar->save();
		}
		$this->place()->associate($lugar);
	}
}
