<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Session\TokenMismatchException;

class ApiFromApplications
{

	/**
	 * Private token from any extern app
	 * 
	 * @var string
	 */
	protected $fromToken;

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$from = $request->input("from") ?$request->input("from"): $request->query("from", "*");
		switch ($from) {
			case 'app':
				$this->fromToken = "d8696ea5f64f1aba7d128500df6c7a766e3400ec";
				break;
			default:
				$this->fromToken = "";
				break;
		}
		if (!empty($this->fromToken)) {
			$token = $request->input("token") ?$request->input("token"): $request->query("token", "");
			$salt = !empty($token) && strlen($token)==40 ? substr($token,32, 8) : "";
			if (!empty($salt)) {
				$confirmed = hash('md5', $salt . $this->fromToken) . $salt === $token;
				if ($confirmed) return $next($request);
			}
		}
		throw new TokenMismatchException('Consulta no autorizada');
	}
}
