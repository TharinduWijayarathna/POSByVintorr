<?php

namespace App\Mail\SendAccountResetTokenMail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendAccountResetTokenMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $sendData;

    /**
     * Create a new message instance.
     */
    public function __construct($sendData)
    {
        $this->sendData = $sendData;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Account Reset Token',
            from: new Address(
                address: 'POSByVintorr@Vintorr.com',
                name: 'POSByVintorr',
            ),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'Mails.Pages.AccountResetToken.account_reset_token',
            with: ['sendData' => $this->sendData],
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
