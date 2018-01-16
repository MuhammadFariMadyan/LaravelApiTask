<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendVerificationEmails extends Mailable {
	public $user, $uuid;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public function __construct( $user, $uuid ) {
		$this->user   = $user;
		$this->uuid = $uuid;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build() {
		return $this->markdown( 'emails.register.verfication' )->with( [
			'verificationToken' => $this->user->emailVerificationCode,
			'uuid'            => $this->uuid
		] )->subject( 'Account Verification' );
	}
}