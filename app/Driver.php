<?php

namespace App;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'dni', 'id'
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'location' => Json::class,
	];
	
	public function user() {
		return $this->belongsTo('App\User', "id");
	}
	
	public function deliveries() {
		return $this->hasMany('App\Delivery');
	}

	public function enPedido() {
		$current = $this->deliveries()->where(function ($query) {
			$query->where("estado", '<>', "Enviado")->orWhereNull("estado");
		})->first();
		return !!$current;
	}

	public function currentPedido() {
		$current = $this->deliveries()->where(function ($query) {
			$query->where("estado", '<>', "Enviado")->orWhereNull("estado");
		})->first();
		return $current;
	}
	
	public const DNI_BASIC_VALIDATE_RULES = ["digits:8"];
}
