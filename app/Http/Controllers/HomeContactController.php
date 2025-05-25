<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class HomeContactController extends Controller
{
    public function html_email(Request $request)
    {

        $sitename = env('APP_NAME');

        $email = get_setting('contact_email');

        $from_email = $request->email;


        $data = array('name' => $request->name, 'email' => $request->email, 'phone' => $request->phone, 'message' => $request->message);
        Mail::send('homeContactmail', $data, function ($message) use ($sitename, $email, $from_email) {
            $message->to($email, $sitename)->subject('Mou Gallery');
            $message->from($from_email, $sitename);
        });

        flash(translate('Message send successfully'))->error();
        return back();
    }
    public function sendMailAdmin(Request $request)
    {

        $sitename = env('APP_NAME');

        $email = get_setting('contact_email');

        $from_email = $request->email;


        $data = array('name' => $request->name, 'email' => $request->email, 'phone' => $request->phone, 'message' => $request->message);
        Mail::send('homeContactmail', $data, function ($message) use ($sitename, $email, $from_email) {
            $message->to($email, $sitename)->subject('Mou Gallery');
            $message->from($from_email, $sitename);
        });

        flash(translate('Message send successfully'))->error();
        return back();
    }
}