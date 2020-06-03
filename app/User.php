<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\Rule;

class User extends Authenticatable //implements MustVerifyEmail
{
	use Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', "phone", "direccion", "email", "uid"
	];
	
	//protected $guarded = ["api_token"];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token', "api_token", "uid"
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
	];

	/* public function savedPlaces() {
		return $this->morphMany('App\Place', 'ubicable');
	} */



	protected const AvatarDirStorage = "avatars/";

	/**
	 * Get Avatar pathname
	 * 
	 * @return string
	 */
	public function getAvatarPathname() {
		return static::AvatarDirStorage . $this->id;
	}

	public function deliveries() {
		return $this->hasMany('App\Delivery');
	}

	public function places() {
		return $this->belongsToMany('App\Place');
	}
	
	public function admin() {
		return $this->hasOne('App\Admin');
	}
	
	public function driver() {
		return $this->hasOne('App\Driver');
	}

	public const EMAIL_BASIC_VALIDATE_RULES = ['email', 'max:255'];

	public const PHONE_BASIC_VALIDATE_RULES = ['digits:9', 'starts_with:9'];

	public const NAME_BASIC_VALIDATE_RULES = ['string', 'max:255'];

	public const PAIS_BASIC_VALIDATE_RULES = ['digits:2', 'starts_with:51'];

	public const UID_BASIC_VALIDATE_RULES = ['string', 'max:128'];

	public const DIRECCION_BASIC_VALIDATE_RULES = ['string', 'max:255'];
}
