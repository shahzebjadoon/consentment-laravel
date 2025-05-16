<?php

namespace App\Http\Controllers\Frontend\Auth;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Services\OtpService;
use App\Models\User;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{

    

    // Show forgot password form
    public function showForgotPasswordForm()
    {
        // echo "hello";
        return view('frontend.auth.forgotpassword.forgot');
    }

    // Send OTP to email
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        
        // Generate and send OTP
        $otp = rand(100000, 999999);

         

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Email not found']);
        }
        //send otp to email
       
        $user->update([
            'reset_otp' => $otp,
            'otp_expires_at' => now()->addMinutes(20),
        ]);

            // Send OTP to user's email 
            $this->emailOtpToUser($request->email, $otp);

            session()->put('email', $request->email);

            return redirect()->route('password.verify-otp');
        
        
    }
 
    // Show OTP verification form
    public function showVerifyOtpForm()
    {
       
        $email = session('email');
        // session()->put('email', $email);

        return view('frontend.auth.forgotpassword.verify-otp', ['email' => $email]);
    }

    // Verify OTP
    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|digits:6']);
        
      

        $user = User::where('email', $request->email)->first();


        if (!$user || $user->reset_otp !== $request->otp) {
            return back()->withErrors(['otp' => 'Invalid OTP'.$request->email]);
        }

        if (Carbon::now()->greaterThan($user->otp_expires_at)) {
            return back()->withErrors(['otp' => 'OTP has expired']);
        }


        
        // Generate token and redirect to password reset
        session()->put('email', $request->email);
        session()->put('otp', $request->otp);
        
        return redirect()->route('password.reset');
    }



    public function emailOtpToUser($email, $otp)
    {
        // Here you would send the OTP to the user's email
        // For example, using Laravel's Mail facade
        // Mail::to($email)->send(new OtpMail($otp));

        // From controller method
        $emailController = new \App\Http\Controllers\EmailController();
        $emailController->sendHtmlEmailForgotPassword($email, 'Consentment -- Your One-Time Password (OTP)', $otp );

        return response()->json(['message' => 'OTP sent to email']);
    }
}