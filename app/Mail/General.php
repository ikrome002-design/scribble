<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class General extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $name;
    public $message;
    public $anchor;
    public $code;
    public $url;
    public $files;
    public $userData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $name, $message, $url = null, $anchor = null, $code = null, $files = [], $userData = [])
    {
        $this->subject = $subject;
        $this->name = $name;
        $this->message =  $message;
        $this->anchor = $anchor;
        $this->code = $code;
        $this->url = $url;
        $this->userData = $userData;
        $this->files = $files;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            markdown: 'emails.general',
            with: [
                'name' => $this->name,
                'message' => $this->message,
                'anchor' => $this->anchor,
                'url' => $this->url,
                'code' => $this->code,
                'userData' => $this->userData,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}