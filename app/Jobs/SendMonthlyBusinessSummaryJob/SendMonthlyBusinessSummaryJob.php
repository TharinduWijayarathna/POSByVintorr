<?php

namespace App\Jobs\SendMonthlyBusinessSummaryJob;

use App\Mail\SendMonthlyBusinessSummaryMail\SendMonthlyBusinessSummaryMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMonthlyBusinessSummaryJob implements ShouldQueue
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
            ->send(new SendMonthlyBusinessSummaryMail($this->sendData, $this->image));
    }
}
