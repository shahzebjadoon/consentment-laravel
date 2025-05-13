<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;

class EmailController extends Controller

{
    public function sendSimpleEmail($to, $subject, $message)
    {
        Mail::raw($message, function ($mail) use ($to, $subject) {
            $mail->to($to)
                 ->subject($subject);
        });

        return true;
    }
}
