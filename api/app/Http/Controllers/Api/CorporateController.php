<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\CorporateNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CorporateController extends Controller
{
    public function send(Request $request)
    {
        $std = new \stdClass();
        $std->address = $request->address;
        $std->country = $request->country;
        $std->city = $request->city;
        $std->email = $request->email;
        $std->phone = $request->phone;
        Mail::to(env('SUPER_ADMIN_EMAIL'))->send(new CorporateNotification($std));
    }
}
