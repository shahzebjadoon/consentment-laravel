<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Domains\Auth\Services\UserService;
use App\Rules\Captcha;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use LangleyFoxall\LaravelNISTPasswordRules\PasswordRules;
use Illuminate\Http\Request;
use App\Models\Invitation;

/**
 * Class RegisterController.
 */
class RegisterController
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * @var UserService
     */
    protected $userService;

    /**
     * RegisterController constructor.
     *
     * @param  UserService  $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Where to redirect users after registration.
     *
     * @return string
     */
    public function redirectPath()
    {
        return route(homeRoute());
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showRegistrationForm(Request $request)
    {
        abort_unless(config('boilerplate.access.user.registration'), 404);

        $invitation = "laiba";

        if ($request->has('invitation')) {
            $invitation = Invitation::where('token', $request->invitation)
                ->where('expires_at', '>', now())
                ->first();
        }

        return view('frontend.new.register', compact('invitation'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')],
            'password' => array_merge(['max:100'], PasswordRules::register($data['email'] ?? null)),
            'terms' => ['required', 'in:1'],
            'g-recaptcha-response' => ['required_if:captcha_status,true', new Captcha],
        ], [
            'terms.required' => __('You must accept the Terms & Conditions.'),
            'g-recaptcha-response.required_if' => __('validation.required', ['attribute' => 'captcha']),
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Domains\Auth\Models\User|mixed
     *
     * @throws \App\Domains\Auth\Exceptions\RegisterException
     */
    // public function create(Request $request)
    // {
    //     abort_unless(config('boilerplate.access.user.registration'), 404);

    //     // Validate the request data
    //     $data = $request->all();
    //     $data['terms'] = $request->has('terms') ? 1 : 0;

    //     // Validate the data using the validator method

    //   $validator = $this->validator($request->all());

    //     if ($validator->fails()) {
    //         return redirect()->back()
    //             ->withErrors($validator)
    //             ->withInput();
    //     }

    //     // Register user using service
    //     $user = $this->userService->registerUser($request->all());

    //     echo "User registered successfully!";
    //     // If user is already logged in, redirect to home
      

        
        

    //     // Handle invitation if exists
    //     if (isset($data['invitation_token'])) {
    //         $invitation = Invitation::where('token', $data['invitation_token'])
    //             ->where('expires_at', '>', now())
    //             ->first();

    //         if ($invitation) {
    //             $invitation->company->users()->attach($user->id, [
    //                 'role' => $invitation->role
    //             ]);
    //             $invitation->delete();
    //         }
    //     }

    //     dd($user);
    //     return $user;
    // }
}
