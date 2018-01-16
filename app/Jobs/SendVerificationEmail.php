<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendVerificationEmails;

class SendVerificationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
	protected $user, $uuid;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user,$uuid)
    {
	    $this->user = $user;
	    $this->uuid = $uuid;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
	    $email = new SendVerificationEmails($this->user, $this->uuid);
	    Mail::to($this->user->email)->send($email);
    }
}
