<?php

namespace App\Http\Controllers\Frontend\Auth;



use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;

class ResetPasswordController extends Controller
{
    // Show reset password form
    public function showResetForm(Request $request)
    {

        $email = session('email');
        $otp = session('otp');
        
       
        if (!$email || !$otp) {
            return redirect()->route('password.verify-otp')->withErrors(['otp' => 'Email or OTP not found']);
        }

        return view('frontend.auth.forgotpassword.reset-password', compact('email', 'otp'));
    }

    // Handle password reset
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric',
            'password' => 'required|confirmed|min:8',
        ]);

      
        // echo  "rest post conctroller";



        $user = User::where('email', $request->email)->first();
        if (!$user) {
           
            return back()->withErrors(['error' => 'User not found']);
        }
        $user->password = bcrypt($request->password);
        $user->reset_otp = null; // Clear OTP after password reset  
        $user->otp_expires_at = null; // Clear OTP expiry time
        $user->save();

        return redirect()->route('frontend.new.login')->with('status', 'Password has been reset successfully. You can now log in.');
    }
}