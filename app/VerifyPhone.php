<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;

class VerifyPhone extends Model
{
	use SoftDeletes;
	
	public static function generate6Code() {
		return rand(10, 99) . '' . rand(1000, 9999);
	}

    protected $fillable = [
        "verify_id", "phone", "code", "reintentos"
    ];

    //protected $table = 'verify_phones';
    protected $hidden = [
        "code", "phone", "id", "ip", "deleted_at"
    ];

	protected $attributes = [
		'reintentos' => 0
	];

	protected function sendVerifationMessage() {
		$nexmo = app('Nexmo\Client');
		return $nexmo->message()->send([
			'to' => $this->phone,
			'from' => 'Vofly',
			'text' => 'Tu codigo Vofly es: ' . $this->code . '. Valido por 5 minutos.'
		]);
	}

	public function send($phone) {
		$this->phone = $phone;
		$this->verify_id = Str::uuid();
		$this->code = static::generate6Code();
		#$message = $this->sendVerifationMessage();
		$this->save();
		return $this;
	}

	public function resend() {
		Validator::make($this->getAttributes(), [
			'phone' => ['required', 'digits_between:9,11'],
			'reintentos' =>['required', 'integer', 'max:3']
		])->validate();
		$this->reintentos += 1;
		/*$this->verify_id = Str::uuid();
		$this->code = static::generate6Code();
		#$message = $this->sendVerifationMessage();
		$this->save();
		return $this # $message;*/
		return $this->send($this->phone);
	}

	public static function confirmPhone($verify_id, $code) {
		if ($verify_phone = static::where('verify_id', $verify_id)->where('code', $code)->first()) {
			$result = ( strtotime($verify_phone->updated_at)>time()-5*60) ? $verify_phone->phone : false ;
			$verify_phone->delete();
			return $result;
		}
		return null;
	}
}