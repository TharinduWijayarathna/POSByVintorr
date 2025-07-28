<?php

namespace App\Jobs;

use App\Mail\SendWelcomeMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendWelcomeMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $sendData;

    /**
     * Create a new job instance.
     */
    public function __construct($sendData)
    {
        $this->sendData = $sendData;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->sendData['email'])->send(new SendWelcomeMail($this->sendData));
    }
}
