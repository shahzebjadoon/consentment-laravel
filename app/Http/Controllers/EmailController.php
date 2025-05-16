<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;


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


    public function sendHtmlEmail($to, $subject, $otp)
    {
        $htmlContent = View::make('emails.otp_signin', ['otp' => $otp])->render();
        
        Mail::send([], [], function ($mail) use ($to, $subject, $htmlContent) {
            $mail->to($to)
                 ->subject($subject)
                 ->setBody($htmlContent, 'text/html');

            // $mail->embed(public_path('images/m1.png'), 'm1');
            // $mail->embed(public_path('images/f.png'), 'f');
            // $mail->embed(public_path('images/i.png'), 'i');  for attachments
            // $mail->embed(public_path('images/x.png'), 'x');
            // $mail->embed(public_path('images/l.png'), 'l');
        });

        return true;
    }

    public function sendHtmlEmailForgotPassword($to, $subject, $otp)
    {
        $htmlContent = View::make('emails.otp_forgot_password', ['otp' => $otp])->render();
        
        Mail::send([], [], function ($mail) use ($to, $subject, $htmlContent) {
            $mail->to($to)
                 ->subject($subject)
                 ->setBody($htmlContent, 'text/html');

            // $mail->embed(public_path('images/m1.png'), 'm1');
            // $mail->embed(public_path('images/f.png'), 'f');
            // $mail->embed(public_path('images/i.png'), 'i');  for attachments
            // $mail->embed(public_path('images/x.png'), 'x');
            // $mail->embed(public_path('images/l.png'), 'l');
        });

        return true;
    }

}