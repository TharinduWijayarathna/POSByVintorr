<?php

namespace App\Mail\SendMonthlyBusinessSummaryMail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendMonthlyBusinessSummaryMail extends Mailable
{
    use Queueable, SerializesModels;

    public $sendData;
    public $image;

    /**
     * Create a new message instance.
     */
    public function __construct($sendData, $image)
    {
        $this->sendData = $sendData;
        $this->image = $image;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->sendData['subject'],
            from: new Address(
                address: 'sparkpos@emergentspark.com',
                name: 'SparkPOS',
            ),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'Mails.Pages.MonthlySummaryReport.monthly_summary_report',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
