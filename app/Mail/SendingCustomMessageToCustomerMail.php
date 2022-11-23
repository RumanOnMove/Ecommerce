<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendingCustomMessageToCustomerMail extends Mailable
{
    use Queueable, SerializesModels;

    public $message;
    public $customer;

    public function __construct($message, $customer)
    {
        $this->message = $message;
        $this->customer = $customer;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'General Mail',
        );
    }

    public function content()
    {
        return new Content(
            view: 'message_mail',
        );
    }


    public function attachments()
    {
        return [];
    }
}
