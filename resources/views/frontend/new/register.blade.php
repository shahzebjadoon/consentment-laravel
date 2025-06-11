<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} | Sign Up</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .login-container {
            display: flex;
            min-height: 100vh;
        }

        .login-left {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background-color: white;
        }

        .login-right {
            flex: 1;
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }

        .app-logo {
            max-width: 250px;
            margin-bottom: 20px;
        }

        .welcome-text {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: #212529;
        }

        .welcome-subtext {
            font-size: 1rem;
            color: #6c757d;
            margin-bottom: 30px;
        }

        .login-form-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 400px;
        }

        .login-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .login-subtitle {
            color: #6c757d;
            margin-bottom: 20px;
        }

        .social-login-btn {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            font-family: 'Montserrat', sans-serif;
            cursor: pointer;
            font-weight: 500;
        }

        .social-login-btn img {
            margin-right: 10px;
            height: 20px;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 20px 0;
            color: #6c757d;
        }

        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid #ddd;
        }

        .divider span {
            margin: 0 10px;
            font-size: 0.9rem;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: 'Montserrat', sans-serif;
        }

        .btn-primary {
            width: 100%;
            padding: 12px;
            background-color: #1da1f2;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            font-family: 'Montserrat', sans-serif;
        }

        .btn-primary:hover {
            background-color: #0c8cd0;
        }

        .signup-text {
            text-align: center;
            margin-top: 20px;
            color: #6c757d;
        }

        .signup-link {
            color: #1da1f2;
            text-decoration: none;
            font-weight: 600;
        }

        .awards-section {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 60px;
        }

        .award-img {
            height: 50px;
            margin: 0 10px;
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }

            .login-left {
                padding: 20px;
            }

            .welcome-text {
                font-size: 2rem;
            }
        }
    </style>
</head>

<body>
@php
    $token = request('invitation');
@endphp
    <div class="login-container">
        <div class="login-left">
            <img src="{{ asset('img/brand/logo.svg') }}" alt="Consentment Logo" class="app-logo">
            <h1 class="welcome-text">Get started</h1>
            <p class="welcome-subtext">Create your Consentment account to start managing consents for your websites.</p>

            <div class="awards-section">
                <img src="https://img.usercentrics.eu/auth0/certifiedCMP.svg" alt="Certified Badge" class="award-img">
                <img src="https://img.usercentrics.eu/auth0/leader23.svg" alt="Leader Badge" class="award-img">
                <img src="https://img.usercentrics.eu/auth0/Badge-IAB@2x.png" alt="IAB Badge" class="award-img">
                <img src="https://img.usercentrics.eu/auth0/reviews.svg" alt="Reviews" class="award-img">
            </div>
        </div>

        <div class="login-right">
            <div class="login-form-container">
                <h2 class="login-title">Sign up</h2>
                <p class="login-subtitle">Create your new account</p>

                <button class="social-login-btn">
                    <img src="https://img.icons8.com/color/48/000000/google-logo.png" alt="Google">
                    Continue with Google
                </button>

                <button class="social-login-btn">
                    <img src="https://img.icons8.com/color/48/000000/microsoft.png" alt="Microsoft">
                    Continue with Microsoft Account
                </button>

                <div class="divider">
                    <span>OR</span>
                </div>

                <form method="POST" action="{{ route('frontend.new.create') }}">
                    @csrf

                 
                    @if (isset($token))
                        <input type="hidden" name="invitation_token" value="{{ $token }}">
                        <p>You were invited. (Invitation token detected)</p> {{-- Optional: display a message --}}
                    @endif
                    <input type="text" name="name" class="form-control" placeholder="Full Name*" required>
                    <input type="email" name="email" class="form-control" placeholder="Email address*" required>
                    <input type="password" name="password" class="form-control" placeholder="Password*" required>
                    <input type="password" name="password_confirmation" class="form-control"
                        placeholder="Confirm Password*" required>
                    <button type="submit" class="btn-primary">Sign Up</button>
                </form>

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
                    Already have an account? <a href="{{ route('frontend.new.login') }}" class="signup-link">Login</a>
                </p>
            </div>
        </div>
    </div>
</body>

</html>
