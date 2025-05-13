<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use phpDocumentor\Reflection\Types\Null_;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller

{
    // Get all users
    public function index()
    {
        return response()->json(User::all());
    }

    // Create a new user
    public function store(Request $request)
    {
        $validated = $request->validate([

            'email' => 'required|email|unique:users,email',
        ]);

        $password = rand(10000098, 99990090); // Generate a random password
        $otp = rand(100000, 999999);
        // return response()->json($request->all());

        $user = User::create([
            'name' => $validated['email'],
            'email' => $validated['email'],
            'password' => bcrypt($password), // Important to hash password
            'type' => $request->input('type', 'user'), // Default to 'user' if not provided
            'active' => $request->input('active', 0), // Default to true if not provided
            'reset_otp' => $otp,
            'otp_expires_at' => now()->addMinutes(20), // Set expiry time for OTP
        ]);

        // Send OTP to user's email 
        $this->emailOtpToUser($validated['email'], $otp);

        return response()->json($user, 201);
    }

    // Get a specific user
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    // Update user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update($request->all());

        return response()->json($user);
    }


    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|digits:6',
        ]);

        // return response($request->all());

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $user = User::where('email', $request->email)->first();


        if (!$user || $user->reset_otp !== $request->otp) {
            return response()->json(['error' => 'Invalid OTP'], 422);
        }

        if (Carbon::now()->greaterThan($user->otp_expires_at)) {
            return response()->json(['error' => 'OTP has expired'], 422);
        }



        return response()->json(['message' => 'OTP Verfied: You can now reset your password']);
    }

    public function emailOtpToUser($email, $otp)
    {
        // Here you would send the OTP to the user's email
        // For example, using Laravel's Mail facade
        // Mail::to($email)->send(new OtpMail($otp));

        // From controller method
        $emailController = new \App\Http\Controllers\EmailController();
        $emailController->sendSimpleEmail($email, 'Consentment -- Your One-Time Password (OTP)', 'Hi,

        Your verification code is:

        ' . $otp . '

        This OTP is valid for 20 minutes.

        Please don\'t share this code with anyone.

        If you didn\'t request this, please ignore this email or contact support.

        Thanks,
        Consetment Team');

        return response()->json(['message' => 'OTP sent to email']);
    }

    public function updatePassword(Request $request)
    {





        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }


        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $user->password = bcrypt($request->password);
        $user->reset_otp = null; // Clear OTP after password reset  
        $user->otp_expires_at = null; // Clear OTP expiry time
        $user->active = 1; // Set user as active
        $user->last_login_at = now(); // Update last login time
        $user->email_verified_at = now(); // Set email as verified
        $user->save();
        return response()->json(['message' => 'Password updated successfully', 'user' => $user], 200);
    }
}
