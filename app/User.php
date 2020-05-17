<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable //implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', "phone", "direccion", "email"
    ];
    
    //protected $guarded = ["api_token"];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', "api_token"
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

	public function savedPlaces() {
		return $this->morphMany('App\Place', 'ubicable');
	}



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

    public const EMAIL_BASIC_VALIDATE_RULES = ['email', 'max:255'];

    public const PHONE_BASIC_VALIDATE_RULES = ['digits_between:9,11'];

    public const NAME_BASIC_VALIDATE_RULES = ['string', 'max:255'];

    public const 	DIRECCION_BASIC_VALIDATE_RULES = ['string', 'max:255'];
}
