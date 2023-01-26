<?php

namespace App\Http\Controllers\Email;

use App\Http\Controllers\Controller;
use App\Mail\MailNotify;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function index()
    {
        $data = [
        "subject"=>"Legal Ace",
        "body"=>"Hello users, Welcome to Legal Mail Delivery!"
        ];

        // MailNotify class that is extend from Mailable class.
        try
        {
            Mail::to('amarabjamal.personal@gmail.com')->send(new MailNotify($data));
            return response()->json(['Great! Successfully send in your mail']);
        }
        catch(Exception $e)
        {
            return response()->json(['Sorry! Please try again latter' . $e]);
        }
    } 
}
