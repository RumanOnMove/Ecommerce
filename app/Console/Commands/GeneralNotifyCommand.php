<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\GeneralNotification;
use Illuminate\Console\Command;

class GeneralNotifyCommand extends Command
{
    protected $signature = 'notify:general';

    protected $description = 'Sending general message';


    public function handle()
    {
        $admin = User::where('role_id', 1)->first();
        $customers = User::where('role_id', 2)->get();
        $admin->notify(new GeneralNotification($admin, $customers));
    }
}
