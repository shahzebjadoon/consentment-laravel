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


    public function sendInvitationEmail($invitation)
{
    $to = $invitation->email;
    $subject = "Invitation to join " . $invitation->company->name;
    $acceptUrl = route('invitations.accept', $invitation->token);

    Mail::send([], [], function ($message) use ($to, $subject, $invitation, $acceptUrl) {
        $message->to($to)
                ->subject($subject)
                ->setBody(
                    "You have been invited to join {$invitation->company->name} as {$invitation->role}.\n\n" .
                    "Accept your invitation here: {$acceptUrl}\n\n" .
                    "This invitation will expire in 7 days.",
                    'text/plain'
                );
    });

    return true;
}
}