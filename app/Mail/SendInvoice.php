<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendInvoice extends Mailable
{
    use Queueable, SerializesModels;

    protected $name;
    protected $mail_subject;
    protected $message;
    protected $attachment;
    protected $file_path;
    protected $file_name;
    protected $invoice;
    protected $type;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $mail_subject, $message, $invoice, $type, $attachment, $file_path, $file_name)
    {
        $this->name         = $name;
        $this->mail_subject = $mail_subject;
        $this->message      = $message;
        $this->invoice     = $invoice;
        $this->attachment   = $attachment;
        $this->file_name    = $file_name;
        $this->file_path    = $file_path;
        $this->type    = $type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(['address' => app_config('Email'), 'name' => app_config('AppName')])
            ->subject($this->mail_subject)
            ->markdown('emails.invoice.send-invoice')
            ->with([
                'name' => $this->name,
                'message' => $this->message,
                'invoice' => $this->invoice,
                'attachment' => $this->attachment,
                'type' => $this->type,
            ])
            ->attachData(
                $this->file_path,
                $this->file_name,
                [
                    'mime' => 'application/pdf',
                ]
            );
    }
}
