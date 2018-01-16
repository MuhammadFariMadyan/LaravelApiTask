<?php

namespace App\Jobs;

use App\Models\LoginDetail;
use App\Models\User;
use App\Utils\AppConstant;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendSamplePush implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
	    $this->data= $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
	    Log::info('Received Request Push Job Started');
	    $receiver = User::where('uuid', $this->data['receiveruuid'])->first();
	    if ($receiver == null) {
		    Log::info("JOB Receiver is null");
		    return;
	    }
	    $sender = User::where('uuid', $this->data['uuid'])->first();
	    if ($sender == null) {
		    Log::info("JOB Sender is null");
		    return;
	    }
	    $userAccessTokens = LoginDetail::where('userId', $receiver->userId)->where('fcmToken', '!=', '')->get();
	    $message = $sender->firstName .' '. $sender->lastName. ' has sent you a notification';
	    $status = AppConstant::REQ_PUSH;
	    foreach ($userAccessTokens as $userAccessToken) {
		    $this->sendToSingle($userAccessToken->fcmToken, $message, $status, $userAccessToken->deviceType);
		    Log::info('Push Sent');
	    }
	    Log::info('Push Job Ended');
    }
}
