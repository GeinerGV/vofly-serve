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
		/*if (is_string($data)) {
			$data = json_encode($data)
		}*/
		//$recojo = new static;
		$this->fullname = $data["fullname"];
		$this->landmark = $data["landmark"];
		$this->phone = $data["phone"];
		$this->destino_type = $type;
		
		if (isset($data["place"]["id"])) {
			$lugar = Place::find($data["place"]["id"]);
		} else {
			$lugar = new Place;
			$lugar->updateData($data["place"]);
			$lugar->save();
		}
		$this->place()->associate($lugar);
	}
}
