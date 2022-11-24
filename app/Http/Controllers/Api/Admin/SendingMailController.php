<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SendingMailController extends Controller
{
    public function send_mail(Request $request){
        try {
            $emailTemplate = EmailTemplate::create([
                'body' => $request->input('template')
            ]);
            if (empty($emailTemplate)){
                throw new Exception('Could not create email template');
            }
            Artisan::call('general:message', ['customers'=> $request->input('customers'), 'bodyContent'=>$emailTemplate]);
        } catch (Exception $exception){
            dd($exception->getMessage());
        }
    }
}
