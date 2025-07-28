<?php

namespace App\Mail\SendCustomerInvoiceMail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendCustomerInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $sendData;

    public $image;

    public $pdf;
    // public $pngUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($sendData, $pdf, $image)
    {
        $this->sendData = $sendData;
        $this->pdf = $pdf;
        $this->image = $image;
        // $this->pngUrl = $pngUrl;
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

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'Mails.Pages.Invoice.invoice',
            with: [
                'sendData' => $this->sendData,
            ],
        );
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this->subject($this->sendData['subject'])
            ->view('Mails.Pages.Invoice.invoice')
            ->with([
                'sendData' => $this->sendData,
            ])
            ->attachData($this->pdf, 'Invoice.pdf', [
                'mime' => 'application/pdf',
            ]);

        if (! empty($this->sendData['cc'])) {
            $mail->cc($this->sendData['cc']);
        }

        return $mail;
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->pdf, 'Invoice.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
