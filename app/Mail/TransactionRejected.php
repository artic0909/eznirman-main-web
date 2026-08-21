<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TransactionRejected extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $transactionDetails;
    public $rejectionReason;
    public $transactionType; // Petty Cash or Unauthorized Purchase

    /**
     * Create a new message instance.
     */
    public function __construct($userName, $transactionDetails, $rejectionReason, $transactionType = 'Transaction')
    {
        $this->userName = $userName;
        $this->transactionDetails = $transactionDetails;
        $this->rejectionReason = $rejectionReason;
        $this->transactionType = $transactionType;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->transactionType . ' Rejected',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.transaction_rejected',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
