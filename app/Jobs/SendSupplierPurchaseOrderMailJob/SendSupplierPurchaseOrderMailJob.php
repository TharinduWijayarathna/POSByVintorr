<?php

namespace App\Jobs\SendSupplierPurchaseOrderMailJob;

use App\Mail\SendSupplierPurchaseOrderMail\SendSupplierPurchaseOrderMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendSupplierPurchaseOrderMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $sendData;
    public $email;
    public $filePath;
    public $image;

    /**
     * Create a new job instance.
     */
    public function __construct($sendData, $email, $filePath, $image)
    {
        $this->sendData = $sendData;
        $this->email = $email;
        $this->filePath = $filePath;
        $this->image = $image;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $pdfContent = file_get_contents($this->filePath);

        Mail::to($this->email)->send(new SendSupplierPurchaseOrderMail($this->sendData, $pdfContent, $this->image));

        unlink($this->filePath);
    }
}
