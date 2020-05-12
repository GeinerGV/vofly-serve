<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * show off @property
 * 
 * @property mixed $phone numero de celular
 * @property string $verify_id uuid - identificar de verificación
 */
class VerifyPhone extends Model
{
	use SoftDeletes;

	# STATUS KEY: VALUES

	public const STATUS_WAIT_YOUR_CONFIRMATION = 'WAIT_YOUR_CONFIRMATION';

	public const STATUS_NEW_CODE_SENDED = 'NEW_CODE_SENDED';

	public const STATUS_EXPIRED_CODE = 'EXPIRED_CODE';

	public const STATUS_CANT_RESEND_NOW = 'CANT_RESEND_NOW';

	public const STATUS_PHONE_CONFIRMED = 'PHONE_CONFIRMED';

	public const STATUS_INVALID_CODE = 'INVALID_CODE';

	public const CODE_LIFETIME = 5*60;

	# GENERAL DATA

	public const MAX_REGISTER_TRIES_PER_DAY_PER_PHONE = 10;

	private $status = "";

	/**
	 * get current $status
	 * @return string
	 */
	public function getStatus() {
		return $this->status;
	}

	public function getStatusAttribute() {
		return $this->getStatus();
	}

	public function getAvailableAttribute() {
		return $this->isAvailableCode();
	}

	protected $appends = ['status', 'available'];
	
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
		'reintentos' => 0,
	];

	protected function sendVerifationMessage() {
		$nexmo = app('Nexmo\Client');
		return $nexmo->message()->send([
			'to' => $this->phone,
			'from' => 'Vofly',
			'text' => 'Tu codigo Vofly es: ' . $this->code . '. Valido por 5 minutos.'
		]);
	}

	public function sendAndSave($phone) {
		$this->phone = $phone;
		$this->verify_id = Str::uuid();
		$this->code = static::generate6Code();
		$message = $this->sendVerifationMessage();
		$this->status = static::STATUS_NEW_CODE_SENDED;
		$this->save();
		return $this;
	}
	
	static function send(string $phone, string $ip) {
		$verify = new VerifyPhone;
		$verify->ip = $ip;
		return $verify->sendAndSave($phone);

	}

	public function resend(string $ip) {
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
		$this->status = static::STATUS_NEW_CODE_SENDED;
		return $this->send($this->phone, $ip);
	}

	private function generateStatus() {
		if ($this->isAvailableCode() && !$this->status) {
			$this->status = static::STATUS_WAIT_YOUR_CONFIRMATION;
		}
	}

	/**
	 * @return bool
	 */
	public function isExpiredCode() {
		return !$this->isAvailableCode();
	}

	/**
	 * @return bool
	 */
	public function isAvailableCode() {
		$result = strtotime($this->updated_at) > time() - static::CODE_LIFETIME;
		if (!$result && !$this->status) {
			$this->status = static::STATUS_EXPIRED_CODE;
		}
		return $result;
	}

	public function isConfirmed() {
		return $this->getStatus() == static::STATUS_PHONE_CONFIRMED;
	}

	/**
	 * 
	 * @return VerifyPhone|null
	 */
	public static function getVerifyModelConfirmed(string $verify_id, string $code) {
		if ($result = static::where('verify_id', $verify_id)->where('code', $code)->first()) {
			if ($result->isAvailableCode()) {
				$result->status = static::STATUS_PHONE_CONFIRMED;
			} else {
				$result->status = static::STATUS_EXPIRED_CODE;
			}
			return $result;
		}
		return null;
	}

	public static function getConfirmedPhoneNumber($verify_id, $code) {
		if ($verify_phone = static::getVerifyModelConfirmed($verify_id, $code)) {
			$result = $verify_phone->isConfirmed() ? $verify_phone->phone : "" ;
			$verify_phone->delete();
			return $result;
		}
		return null;
	}

	/**
	 * get row data
	 * @param string $verify_id
	 * @return VerifyPhone
	 */
	public static function getVerifyPhone(string $verify_id) {
		$phone = VerifyPhone::firstWhere('verify_id', $verify_id);
		if ($phone) $phone->generateStatus();
		return $phone;
	}
	

	# ATTRIBUTES -> VALIDATE RULES
	## GENERAL RULES
	public const VERIFY_ID_BASIC_VALIDATE_RULES = ['uuid'];

	public const VERIFY_ID_EXITS_VALIDATE_RULES = ['exists:verify_phones,verify_id,deleted_at,NULL'];

	public const REINTENTOS_BASIC_VALIDATE_RULES = ['integer', 'max:3'];

	public const CODE_BASIC_VALIDATE_RULES = ['digits:6'];

	## REPETICIONES DEL MISMO NÚMERO POR UN DÍA PARA EL REGISTRO DEL TELÉFONO

	public const KEYRULE_REGISTER_TRIES_PHONE_DAY = 'phone_verifying_tries';

	public const REGISTER_TRIES_PHONE_DAY_BASIC_VALIDATE_RULES = ['integer'];
}
