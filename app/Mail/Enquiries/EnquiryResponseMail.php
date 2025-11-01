<?php

namespace App\Mail\Enquiries;

use App\Models\Enquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EnquiryResponseMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $enquiry;
    public $responseSubject;
    public $responseMessage;


    /**
     * Create a new message instance.
     */
    public function __construct(Enquiry $enquiry, $responseSubject, $responseMessage)
    {
        $this->enquiry = $enquiry;
        $this->responseSubject = $responseSubject;
        $this->responseMessage = $responseMessage;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->responseSubject ?? 'Enquiry Response Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.enquiries.enquiry-response',
            with: [
                'enquiry' => $this->enquiry,
                'responseSubject' => $this->responseSubject,
                'responseMessage' => $this->responseMessage,
            ]
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
