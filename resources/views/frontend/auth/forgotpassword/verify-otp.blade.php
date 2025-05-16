@extends('frontend.auth.forgotpassword.layout')
@section('content')
<div class="login-right">
    <div class="login-form-container">
        <h2 class="login-title">Verify OTP</h2>
        <p class="login-subtitle">Enter your OTP send to your email address.</p>
        
        
        <form method="POST" action="{{ route('password.verify-otp.submit') }}">
            @csrf
            <input type="number" name="otp" class="form-control" placeholder="OTP*" required>
            <input type="hidden" name="email" value="{{ $email }}">                  
            <button type="submit" class="btn-primary">Verify</button>
        </form>
        <br>
        @error('otp')
            <span style="color: red; font-size:14px">{{ $message }}</span>
        @enderror
        <p class="signup-text">
            Don't have an account? <a href="{{ route('frontend.new.register') }}" class="signup-link">Sign up</a>
        </p>
    </div>
</div>

@endsection