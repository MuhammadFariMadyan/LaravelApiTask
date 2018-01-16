<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use UrlSigner;

class ForgotPasswordMail extends Mailable
{
    use Queueable, SerializesModels;
	public $user, $uuid;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user,$uuid)
    {
	    $this->user   = $user;
	    $this->uuid = $uuid;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
    	$url = UrlSigner::sign(url('/forgotPassword/'.$this->uuid.'/'.$this->user->forgotPasswordCode),Carbon::now()->addHours(24));
	    return $this->markdown( 'emails.forgotPassword.forgotPassword' )->with( [
		    'url' => $url
	    ] )->subject( 'Reset Password' );
    }
}
