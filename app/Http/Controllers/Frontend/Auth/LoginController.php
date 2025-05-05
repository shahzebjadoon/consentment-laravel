<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Subscription;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        return $this->sendFailedLoginResponse($request);
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect('/new/login');
    }


    // function override due to json response expected by ajax, fetch or api 

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Login successful',
                'user' => $this->guard()->user()
            ]);
        }

       return $this->checkSubscriptionAndRedirect();
    }



    public function checkSubscriptionAndRedirect()
{
    $user = Auth::user();

    $subscription = Subscription::where('user_id', $user->id)
        ->orderByDesc('expire_at')
        ->first();

    if (!$subscription || ($subscription->expire_at && Carbon::parse($subscription->expire_at)->isPast())) {
        return redirect()->route('price.plans')->with('message', 'Your subscription has expired.');
    }

    return redirect()->intended($this->redirectPath());
    
}
}
