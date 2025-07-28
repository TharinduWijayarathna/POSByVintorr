<?php

namespace App\Mail\SendSupplierPurchaseOrderMail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendSupplierPurchaseOrderMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $sendData;
    public $pdf;
    public $image;

    /**
     * Create a new message instance.
     */
    public function __construct($sendData, $pdf, $image)
    {
        $this->sendData = $sendData;
        $this->pdf = $pdf;
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

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'Mails.Pages.PurchaseOrder.purchase-order',
            with: ['sendData' => $this->sendData],
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
            ->view('Mails.Pages.PurchaseOrder.purchase-order')
            ->with('sendData', $this->sendData)
            ->attachData($this->pdf, 'PurchaseOrder.pdf', [
                'mime' => 'application/pdf',
            ]);

        if (!empty($this->sendData['cc'])) {
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
            Attachment::fromData(fn () => $this->pdf, 'PurchaseOrder.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
