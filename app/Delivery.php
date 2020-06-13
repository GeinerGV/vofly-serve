<?php

namespace App;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
	/*protected $appends = ['recojo'];

	public function getRecojoAttribute() {
		return $this->recojo_model;
	}*/

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'location' => Json::class,
		'trayectoria' => Json::class,
	];

	public const STATUS_INICIADO = "Iniciado";

	public const STATUS_ENTREGADO = "Enviado";
	public const STATUS_RECOGIDO = "Recogido";

	public const DELAY_UPDATE_LOCATION = 15000;

    public function user() {
		return $this->belongsTo('App\User');
	}

	//public function 

    public function driver() {
		return $this->belongsTo('App\Driver');
	}

    public function recojo() {
		return $this->morphTo('recogible');
	}

    public function entrega() {
		return $this->morphTo('entregable');
	}

    public function carga() {
		return $this->morphTo('cargable');
	}

    public function plan() {
		return $this->belongsTo('App\Models\Pago\DeliveryPlan');
	}
	
	public function getEstado() {
		if (!$this->estado) {
			return "NO INICIADO";
		}
		return $this->estado;
	}
	

	public function distanciaFormatoStr() {
		if (!$this->distancia) return "";
		if ($this->distancia<1000) {
			return ($this->distancia . " m");
		} else {
			return (round($this->distancia / 1000, 1) . " km");
		}
	}
}
