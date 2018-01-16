<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Utils\AppConstant;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
	/*
	|--------------------------------------------------------------------------
	| Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles authenticating users for the application and
	| redirecting them to your home screen. The controller uses a trait
	| to conveniently provide its functionality to your applications.
	|
	*/

	use AuthenticatesUsers;

	/**
	 * Where to redirect users after login.
	 *
	 * @var string
	 */
	public $redirectTo = AppConstant::ADMIN_URL_PREFIX . 'dashboard';

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth.adminlogin')->except('logout');
	}

	public function showLoginForm()
	{
		return view('admin.pages.login');
	}

	public function username()
	{
		return 'adminEmail';
	}

	public function guard()
	{
		return Auth::guard('admin');
	}

	public function credentials(Request $request)
	{
		return ['adminEmail' => $request->adminEmail, 'password' => $request->adminPassword];
	}

	protected function validateLogin(Request $request)
	{
		$this->validate($request, [
			$this->username() => 'required|string',
			'adminPassword' => 'required|string',
		]);
	}

	public function logout(Request $request)
	{
		$this->guard()->logout();

		$request->session()->forget(AppConstant::GUARD_ADMIN);

		$request->session()->regenerate();

		return redirect(AppConstant::ADMIN_URL_PREFIX);
	}

	public function forgotPasswordView()
	{
		return view('admin.pages.forgotPassword');
	}

	/*public function forgotPassword(Request $request)
	{
		$validator = Validator::make(
			array(
				'adminEmail' => $request->adminEmail
			),
			array(
				'adminEmail' => 'required|email'
			)
		);
		if ($validator->fails()) {
			$errors = $validator->errors();
			if ($errors->first('adminEmail')) {
				$message = $errors->first('adminEmail');
				return redirect()->back()
				                 ->withInput($request->only('adminEmail'))
				                 ->withErrors([
					                 'adminEmail' => $message
				                 ]);
			}
		}
		try {
			$validateUser = Admin::where(array(
				'adminEmail' => $request->adminEmail,
				'adminStatus' => AppConstant::STATUS_ACTIVE))
			                     ->first();
			if (!$validateUser) {
				return redirect()->back()
				                 ->withInput($request->only('adminEmail'))
				                 ->withErrors([
					                 'adminEmail' => 'This email does not exists'
				                 ]);
			}
			$now = trim(Carbon::now()->timestamp);
			$userId = $validateUser->adminId;
			$code = $userId. '-' .$now;
			$token = trim($this->encryptText($code));

			$url = url('admin') . '/resetPassword/' . $token;
			$name= $validateUser->adminName;
			$subject = "Forgot Password";
			$msg = "Please click on the button below to reset your password :";

			$resetPasssword = new ResetPassword();
			$resetPasssword->email = $request->emailId;
			$resetPasssword->token= $code;
			$resetPasssword->role= AppConstant::STATUS_ACTIVE;
			$resetPasssword->linkStatus= AppConstant::STATUS_INACTIVE;
			$resetPasssword->save();
			$receiver = AppConstant::ADMIN_MAIL;


			Mail::to($receiver)->send(new ForgotPassword($receiver,$subject,$name,$msg,$url));
			Session::flash('email_sent', 'A reset password mail has been sent successfully.');
		} catch (QueryException $e) {
			Session::flash('email_fail', 'Server error.');
			return redirect()->back();
		}
		return view('admin.pages.login');
	}*/

	/*public function checkResetPassword($token)
	{
		$decryptTS = trim($this->decryptText($token));
		$getLinkStatus = ResetPassword::where('token', $decryptTS)
		                              ->where('role',Constant::STATUS_1)
		                              ->first()->linkStatus;
		$split = explode('-', $decryptTS, 2);

		$adminId = $split[0];
		$timeStamp = $split[1];

		$data = array('adminId'=> ltrim($adminId,"0"));

		$requestTimestamp=Carbon::createFromTimestampUTC($timeStamp);
		$now = Carbon::now();

		if($requestTimestamp->diffInHours($now) <=24 && $getLinkStatus==0)
		{
			return view('admin.pages.resetPassword')->withData($data);
		}
		else
		{
			echo "Invalid URL";
		}
	}*/

	/*public function updatePassword(Request $request)
	{
		$token = trim($this->decryptText($request->token));
		$getLinkStatus = ResetPassword::where('token', $token)
		                              ->where('role',Constant::STATUS_1)
		                              ->first()->linkStatus;
		if ($getLinkStatus==0) {
			$validator = Validator::make(
				array(
					'password' => $request->password,
					'password_confirmation' => $request->password_confirmation
				),
				array(
					'password' => 'required|confirmed',
					'password_confirmation' => 'required'
				)
			);
			if ($validator->fails()) {
				$errors = $validator->errors();
				if ($errors->first('password')) {
					$message = $errors->first('password');
					return redirect()->back()
					                 ->withInput($request->only('password'))
					                 ->withErrors([
						                 'password' => $message
					                 ]);
				}
			}
			$data = array(
				'adminPassword' => Hash::make( $request->password )
			);

			try {
				Admin::where( array( 'adminId' => $request->adminId ) )->update( $data );
				ResetPassword::where( 'token', $token )
				             ->where( 'role', Constant::STATUS_1 )->update( [
						'linkStatus' => Constant::STATUS_1
					] );
			} catch ( QueryException $e ) {
				Session::flash( 'password_fail', 'Server Error' );
			}
			Session::flash( 'password_success', 'Your Password Change Successfully' );

			return response()->redirectTo( Constant::ADMIN_URL_PREFIX );
		} else {
			echo "Invalid URL";
		}
	}*/
}
