@extends('frontend.auth.forgotpassword.layout')
@section('content')
<div class="login-right">
    <div class="login-form-container">
        <h2 class="login-title">Reset Your Password?</h2>
        <p class="login-subtitle">Enter your New Password.</p>
        
        
        <form method="POST" action="{{ route('password.reset') }}">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            <input type="hidden" name="otp" value="{{ $otp }}">
            <input type="password" name="password" class="form-control"  required>
            <input type="password" name="password_confirmation" class="form-control" required>
                   
            <button type="submit" class="btn-primary">Continue</button>
        </form>
        <br>
        @if ($errors->any())
        <div style="color: red; font-size:14px">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
        <p class="signup-text">
            Don't have an account? <a href="{{ route('frontend.new.register') }}" class="signup-link">Sign up</a>
        </p>
    </div>
</div>



@endsection