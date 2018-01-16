<?php

namespace App\Http\Controllers\Verification;

use App\Models\User;
use App\Utils\AppConstant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function verifyEmail($uuid,$emailToken)
    {
    	$validator = Validator::make([
    		'uuid' => $uuid,
    		'emailToken' => $emailToken
	    ],[
		    'uuid'=> 'required',
	    	'emailToken'=> 'required|max:10|min:10'
	    ]);
	    if ($validator->fails()) {
		    return $validator->messages()->first();
	    }
	    $user = User::where([
	    	'uuid'=>$uuid,
	    	'status'=>AppConstant::STATUS_ACTIVE,
		    ])->first();
	    if($user)
	    {
	    	if($user->isEmailVerified === AppConstant::STATUS_INACTIVE && $user->emailVerificationCode === $emailToken){
			    $user->isEmailVerified = AppConstant::STATUS_ACTIVE;
			    $user->emailVerificationCode = NULL;
			    $user->save();
			    $msg = __('auth.email_verified_success');
			    return view('verification.status')->with('msg',$msg);
		    }
		    $msg = __('auth.email_already_verified');
		    return view('verification.status')->with('msg',$msg);
	    }
	    $msg = __('auth.email_verified_failed');
	    return view('verification.status')->with('msg',$msg);
    }
}
