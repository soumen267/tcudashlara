<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendEmail($email){
        $reveiverEmailAddress = "soumen8412@gmail.com";
        $mail = Mail::to($reveiverEmailAddress)->send(new WelcomeMail);
        if ($mail) {
            return "Email has been sent successfully.";
        }
        return "Oops! There was some error sending the email.";
    }
}
