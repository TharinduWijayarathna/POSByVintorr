<?php

namespace App\Mail\SendCustomerOutstandingMail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendCustomerOutstandingMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $sendData;
    public $image;

    /**
     * Create a new message instance.
     */
    public function __construct($sendData, $image)
    {
        Log::info($sendData);
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
                address: $this->sendData['sender_email'],
                name: $this->sendData['business_name'],
            ),
        );
    }

    public function build()
    {
        $mail = $this->subject($this->sendData['subject'])
                    ->view('Mails.Pages.CustomerOutstandingReport.customer_outstanding_report')
                    ->with('sendData', $this->sendData);

        if(!empty($this->sendData['cc'])){
            $mail->cc($this->sendData['cc']);
        }

        return $mail;
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'Mails.Pages.CustomerOutstandingReport.customer_outstanding_report',
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
