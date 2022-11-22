<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\SendingCustomMessageToCustomerNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendingCustomMessageToCustomerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customer:message {message}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $customers = User::where('role_id', 2)->get();
        $message = $this->argument('message');
        Notification::send($customers, new SendingCustomMessageToCustomerNotification($message));
        return Command::SUCCESS;
    }
}
