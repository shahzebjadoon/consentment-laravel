<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Domains\Auth\Services\UserService;
use App\Models\Invitation;
use App\Models\User;


class NewRegisterController extends Controller
{
    
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    
    public function showRegistrationForm()
    {

        return view('frontend.new.register');
    }


    public function create(Request $request)
    {
        // Validate the request
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Register user using service
        $user = $this->userService->registerUser($request->all());


         if (isset($request->invitation_token)) {
        $invitation = Invitation::where('token', $request->invitation_token)
            ->where('expires_at', '>', now())
            ->first();

        if ($invitation) {
            $invitation->company->users()->attach($user->id, [
                'role' => $invitation->role
            ]);
            $invitation->delete();
        }
    }

        // Log the user in
        Auth::login($user);

        // Redirect to intended page or dashboard
        return redirect()->intended('/dashboard')->with('success', 'Registration successful!');
    }


      protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }


    
}