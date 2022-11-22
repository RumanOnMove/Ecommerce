<?php

namespace App\Notifications;

use App\Mail\OrderCompleteMail;
use App\Mail\UserListMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Mail;

class GeneralNotification extends Notification
{
    use Queueable;

    public $customers;
    public $admin;

    public function __construct($admin, $customers)
    {
        $this->admin = $admin;
        $this->customers = $customers;
    }


    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        Mail::to($this->admin->email)->send(new UserListMail($this->admin, $this->customers));
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
