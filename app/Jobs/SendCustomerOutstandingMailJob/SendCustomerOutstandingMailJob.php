<?php

namespace App\Jobs\SendCustomerOutstandingMailJob;

use App\Mail\SendCustomerOutstandingMail\SendCustomerOutstandingMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendCustomerOutstandingMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $sendData;

    public $email;

    public $image;

    /**
     * Create a new job instance.
     */
    public function __construct($sendData, $email, $image)
    {
        $this->sendData = $sendData;
        $this->email = $email;
        $this->image = $image;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->email)
            ->send(new SendCustomerOutstandingMail($this->sendData, $this->image));

    }
}
