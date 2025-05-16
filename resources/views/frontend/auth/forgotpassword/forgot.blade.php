@extends('frontend.auth.forgotpassword.layout')
@section('content')
<div class="login-right">
    <div class="login-form-container">
        <h2 class="login-title">Forgot Your Password?</h2>
        <p class="login-subtitle">Enter your Email address and we will send you instructions to reset your password.</p>
        
        
        
        <form method="POST" action="{{ route('password.email-otp') }}">
            @csrf
            <input type="email" name="email" class="form-control" placeholder="Email address*" required>
                    
            <button type="submit" class="btn-primary">Continue</button>
        </form>
        
        <p class="signup-text">
            Don't have an account? <a href="{{ route('frontend.new.register') }}" class="signup-link">Sign up</a>
        </p>
    </div>
</div>

@endsection