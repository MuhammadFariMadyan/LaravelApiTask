<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Traits\ApiResponse;
use App\Utils\AppConstant;
use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class VerifyJWTToken
{
	use ApiResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
	public function handle($request, Closure $next)
	{
		$token = JWTAuth::getToken();
		if(!$token)
		{
			$this->setMeta('status', AppConstant::STATUS_FAIL);
			$this->setMeta('message', __('jwt.jwt_absent'));
			return response()->json($this->setResponse(), AppConstant::BAD_REQUEST);
		}
		try {
			$user = $this->auth($token);
			if (!$user) {
				$this->setMeta('status', AppConstant::STATUS_FAIL);
				$this->setMeta('message', __('jwt.jwt_invalid'));
				return response()->json($this->setResponse(), AppConstant::TOKEN_INVALID);
			}
		} catch (TokenExpiredException $e) {
			$this->setMeta('status', AppConstant::STATUS_FAIL);
			$this->setMeta('message', __('jwt.jwt_expire'));
			return response()->json($this->setResponse(), AppConstant::TOKEN_INVALID);
		} catch (TokenInvalidException $e) {
			$this->setMeta('status', AppConstant::STATUS_FAIL);
			$this->setMeta('message', __('jwt.jwt_invalid'));
			return response()->json($this->setResponse(), AppConstant::TOKEN_INVALID);
		} catch (JWTException $e) {
			$this->setMeta('status', AppConstant::STATUS_FAIL);
			$this->setMeta('message', __('jwt.jwt_invalid'));
			return response()->json($this->setResponse(), AppConstant::TOKEN_INVALID);
		}
		$request->merge(['user' => $user]);
		return $next($request);
	}

	public function auth($token = false)
	{
		$id = JWTAuth::getPayload($token)->get('sub');
		$user = User::where([
			'uuid'=> $id
		])->first();
		if($user){
			return $user;
		}
		return false;
	}

	/*if you have custom token then use this function*/

	/*public function auth($token = false)
	{
		$sub = JWTAuth::getPayload($token)->get('sub');
		if ($sub["user_type"] === user) {
			$id = $sub["id"];
			$user = User::where('id', $id)
			            ->where('status', AppConstant::STATUS_ACTIVE)
			            ->first();
			return $user;
		}
		return false;
	}*/
}
