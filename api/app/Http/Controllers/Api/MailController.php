<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\Api\ContactNotification;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function sendEmail(Request $request)
    {
        $request->validate([
           'name' => 'required',
           'email' => 'email',
           'text' => 'required',
        ]);

        $user = User::first();

        Mail::to($user->email)->send(new ContactNotification(['email' => $request->email,
            'name' => $request->name, 'text' => $request->text,
            'theme' => 'Contact notification'

        ]));
    }
}
