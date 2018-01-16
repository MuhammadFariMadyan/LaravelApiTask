<?php

namespace App\Jobs;

use App\Mail\ForgotPasswordMail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendForgotPasswordEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $user,$uuid;

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
	    $email = new ForgotPasswordMail($this->user, $this->uuid);
	    Mail::to($this->user->email)->send($email);
    }
}
