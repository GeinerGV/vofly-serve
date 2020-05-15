<?php

namespace App\Exceptions;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Exception;

class SMSValidateErrorException extends Exception
{
    public function __construct(string $message) {
		parent::__construct($message);
	}

	/**
	 * @param \Illuminate\Http\Request  $request
	 */
	public function render($request) {
		return $request->expectsJson()
                    ? response()->json([
							'message' => $this->getMessage(),
							'errors' => [
								'number' => 'Verifica tu número.'
							]
						],
						422
					)
                    : new Response('', 422);
	}
}
