<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\SendingCustomMessageToCustomerNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendingCustomMessageToCustomerCommand extends Command
{

    protected $signature = 'general:message {customers} {bodyContent}';
    protected $description = 'Command description';

    public function handle()
    {
        $customers = $this->argument('customers');
        $cIds = array_map(function ($item){return $item['id'];}, $this->argument('customers'));
        $customers = User::whereIn('id', $cIds)->get();
        $bodyContent = $this->argument('bodyContent');
        Notification::send($customers, new SendingCustomMessageToCustomerNotification($bodyContent));
    }
}
