<?php

namespace App\Http\Controllers;
use Mail;
use App\Mail\demoMail;
use Illuminate\Http\Request;

class mailSender extends Controller
{
    public function index(){
        $mailData=[
            'title'=>'mail from just Code',
            'body' => 'this is for testing'
        ];

        Mail::to('samarthmashruwala@gmail.com')->send(new demoMail($mailData));

        dd('mail sent successfully');
    }
}
