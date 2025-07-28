<?php

namespace App\Mail\SendEmployeePayrollMail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendEmployeePayrollMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $sendData;

    public $image;

    public $pdf;

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
            view: 'Mails.Pages.Payroll.payroll',
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
            ->view('Mails.Pages.Payroll.payroll')
            ->with('sendData', $this->sendData)
            ->attachData($this->pdf, 'SalarySlip.pdf', [
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
            Attachment::fromData(fn () => $this->pdf, 'SalarySlip.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
