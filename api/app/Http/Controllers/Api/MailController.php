<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\Api\ContactNotification;
use App\Models\Company;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\NewCompanyNotification;
use App\Notifications\NewMessageContactNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Psy\Util\Str;

class MailController extends Controller
{

    public function contact(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'email',
            'text' => 'required',
        ]);

        $user =  User::first();

        $user->notify(new NewMessageContactNotification($request->name, $request->email, $request->text));
        return response(['success' => true]);
    }

    public function sendEmail(Request $request)
    {
        $request->validate([
           'name' => 'required',
           'email' => 'email',
           'text' => 'required',
        ]);

        $user =  User::first();

        Mail::to($user->email)->send(new ContactNotification([
            'email' => $request->email,
            'name' => $request->name, 'text' => $request->text,
            'theme' => 'Contact notification'

        ]));
        return response(['success' => true]);

    }
    public function transport(Request $request)
    {
        $request->validate([
           'name' => 'required',
           'email' => 'email|unique:users,email',
           'phone' => 'required',
           'company_name' => 'required',
        ]);

        $password = \Illuminate\Support\Str::random(10);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($password),
        ]);
        $role = Role::company()->first();
        $user->setRole($role->id);
        $user->setActive();
        $user->save();
          Company::create([
            'name' => $request->company_name,
            'email' => $user->email,
            'tax' => 10,
            'user_id' => $user->id,
            'active' => !Company::ACTIVE,
            'phone' => $request->phone,
            'agree_terms' => Company::AGREE_TERMS,

        ]);

        $user->notify(new NewCompanyNotification($user, $password));
        return response(['success' => true]);

    }
    public function corporate(Request $request)
    {
        $request->validate([
           'name' => 'required',
           'email' => 'email|unique:users,email',
           'phone' => 'required',
           'company_name' => 'required',
        ]);

        $password = \Illuminate\Support\Str::random(10);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($password),
        ]);
        $role = Role::travel()->first();
        $user->setRole($role->id);
        $user->setActive();
        $user->save();
          Company::create([
            'name' => $request->company_name,
            'email' => $user->email,
            'tax' => 10,
            'user_id' => $user->id,
            'active' => !Company::ACTIVE,
            'phone' => $request->phone,
            'agree_terms' => Company::AGREE_TERMS,

        ]);

        $user->notify(new NewCompanyNotification($user, $password));
        return response(['success' => true]);

    }
}
