<?php

namespace App\Jobs\SendAccountResetTokenMailJob;

use App\Mail\SendAccountResetTokenMail\SendAccountResetTokenMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendAccountResetTokenMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $sendData;

    public $email;

    /**
     * Create a new job instance.
     */
    public function __construct($sendData, $email)
    {
        $this->sendData = $sendData;
        $this->email = $email;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->email)
            ->send(new SendAccountResetTokenMail($this->sendData));

    }
}
