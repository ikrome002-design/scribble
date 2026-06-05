<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OptedOutMembers extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $members;
    public $previous;
    public $current;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $members, $previous, $current)
    {
        $this->name = $name;
        $this->members =  $members;
        $this->current = $current;
        $this->previous = $previous;
    }


    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Update on Team Link Membership Truncation',
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
            markdown: 'emails.team.opted-out-members',
            with: [
                'name' => $this->name,
                'members' => $this->members,
                'previous' => $this->previous,
                'current' => $this->current,
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
