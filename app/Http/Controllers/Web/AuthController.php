<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use UrlSigner;
class AuthController extends Controller
{
    public function resetPasswordView()
    {
    	$url = url()->full();
	    $isValid = UrlSigner::validate($url);
	    if($isValid == 1)
	    {
		    return 'Forgot Password Page!';
	    } else {
	    	return 'Forbidden!';
	    }
    }
}
