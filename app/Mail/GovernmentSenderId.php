<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class GovernmentSenderId extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $subject, $message, $files)
    {
        $this->email = $email;
        $this->subject = $subject;
        $this->message = $message;
        $this->files = $files;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this->from(['address' => app_config('Email'), 'name' => app_config('AppName')])
            ->subject($this->subject)
            ->markdown('emails.senderid.government-senderid')
            ->with([
                'message' => $this->message,
            ]);
        foreach ($this->files as $file) {
            $mail->attach(
                storage_path('/app/private/senderid/' . $file['filename']),
                [
                    'as' => $file['originalname'],

                ]
            );
        }
        return $mail;
    }
}