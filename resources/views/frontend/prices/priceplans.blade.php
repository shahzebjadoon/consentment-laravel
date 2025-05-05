<!doctype html>
<html lang="{{ htmlLang() }}" @langrtl dir="rtl" @endlangrtl>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ appName() }} | @yield('title')</title>
    <meta name="description" content="@yield('meta_description', appName())">
    <meta name="author" content="@yield('meta_author', 'Anthony Rappa')">
    @yield('meta')

    @stack('before-styles')
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="{{ mix('css/frontend.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <livewire:styles />
    @stack('after-styles')
</head>
<body>
    {{-- <h2>Welcome, {{ $user->name ?? 'Guest' }}</h2> --}}


    <div class="overlay" id="popupOverlay">
        <div class="popup-form">
          {{-- <h3> Consentment </h3> --}}
          <h2>Login</h2>
          <br>
          <form id="loginForm">
            @csrf
            <input type="email" name="email" placeholder="Email" required />
            <input type="password" name="password" placeholder="Password" required />
            <button type="submit">Login</button>
          </form>
          <div id="loginMessage"></div>
        </div>
      </div>



    <div class="container">
        <!-- Header with back button -->
        <header>
            <a href="#" class="back-button">← BACK</a>
            <div class="header-icons">
                {{-- <p  style="padding: 5px; color:#888; margin-top:4px; hover:var(--background-light); margin-right:-25px">{{ $user->name ?? 'Guest' }}</p> --}}
                <p  class="animated-pill">{{ $user->name ?? 'Guest' }}</p>
                <div class="header-icon" id="userIcon">
                    <i class="fas fa-user-circle"></i>
                </div>
        </header>


        <div class="pricing-container">
            <div class="row">
                <!-- Current Plan Section - Col 4 -->
                <div class="col-4">
                    <div class="current-plan">
                        <h3>Your current plan</h3>
                        <br>
                        @if($subscription != null)
                        <div class="">
                            <div class="plan-header">
                                @if($subscription->status=="trial")
                                <span class="plan-badge">Free</span>
                                <span class="trial-expired-badge">Trial Expired</span>
                                @else 
                                <span class="plan-badge">Paid</span>
                                <span class="trial-expired-badge">Subscription Expired</span>

                                @endif
                            </div>
                            @if($subscription->status=="trial")

                            <div class="plan-expired">
                                <span class="warning-icon">⚠</span>
                                <p>Your Premium Trial has ended</p>
                                <small>Upgrade your plan to regain full access to your account</small>
                            </div>
                            @endif
                            <div class="plan-details">
                                <div class="detail-row">
                                    <span>Price</span>
                                    <span>€ {{$subscription->price_paid}} / month</span>
                                </div>
                                <div class="detail-row">
                                    <span>Domains</span>
                                    <span>Incl. {{$plans[$subscription->price_plan_id-1]->domain_allowed}} domain</span>
                                </div>
                                <div class="detail-row">
                                    <span>Vistors</span>
                                    <span>{{$plans[$subscription->price_plan_id-1]->max_visitors}} visitors / month</span>
                                </div>
                                <div class="detail-row">
                                    <span>Languages</span>
                                    <span>{{$plans[$subscription->price_plan_id-1]->max_languages}} Languages</span>
                                </div>
                                <div class="detail-row">
                                    <span>Payment Method</span>
                                    <span>Not added</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        


                    </div>
                </div>

                <!-- Plan Selection Section - Col 8 -->
                <div class="col-8">
                    <div class="white-wrapper">
                        <!-- Header section outside scroll area -->
                        <div class="header-section">
                            <h2>Select a plan</h2>
                            <div class="currency-dropdown">
                                <button type="button" class="currency-button" id="currencyBtn">
                                    EUR € 
                                    <svg class="dropdown-icon" width="12" height="12" viewBox="0 0 24 24">
                                        <path d="M7 10l5 5 5-5z" fill="currentColor"/>
                                    </svg>
                                </button>
                                <div class="dropdown-content" id="currencyDropdown">
                                    <a href="#" class="dropdown-item">USD $</a>
                                    <a href="#" class="dropdown-item">GBP £</a>
                                    <a href="#" class="dropdown-item">JPY ¥</a>
                                    <a href="#" class="dropdown-item">AUD $</a>
                                </div>
                            </div>
                        </div>

                        <!-- Scrollable content -->
                        <div class="scroll-wrapper">
                            <div class="scrollable-content">
                                <div class="plans-grid">
                                    <!-- Essential Web Plan -->

                                    @foreach ($plans as $plan )
                                        
                                    <div class="plan-card">
                                        <h3>{{$plan->membership}}</h3>
                                        <p class="plan-description">{{$plan->description}}</p>
                                        @if($plan->is_custom == 0)
                                        <div class="price"> € {{ $plan->price_month }} <span>per month</span></div>
                                        <ul class="features">
                                            <li class="feature-item">{{$plan->domain_allowed}} domain</li>
                                            <li class="feature-item">{{$plan->max_visitors}} visitors / month</li>
                                            <li class="feature-item">1 privacy regulation</li>
                                            <li class="feature-item">Unlimited seats</li>
                                            <li class="feature-item">Unlimited subpages</li>
                                            <li class="feature-item">{{$plan->max_languages}} banner languages</li>
                                        </ul>
                                        @else
                                        <div class="price"> Custom Price <span></span></div>
                                        <ul class="features">
                                            <li class="feature-item">{{$plan->domain_allowed?? "custom"}} domain</li>
                                            <li class="feature-item">{{$plan->max_visitors ?? "custom"}} visitors / month</li>
                                            <li class="feature-item">1 privacy regulation</li>
                                            <li class="feature-item">Unlimited seats</li>
                                            <li class="feature-item">Unlimited subpages</li>
                                            <li class="feature-item">{{$plan->max_languages ?? "custom"}} banner languages</li>
                                        </ul>
                                        @endif
                                        <button class="view-details">View plan details</button>
                                    </div>

                                    @endforeach

                                    {{-- <div class="plan-card">
                                        <h3>CMP Plus Web</h3>
                                        <p class="plan-description">Easily manage privacy compliance while growing your online presence</p>
                                        <div class="price">€15 <span>per month</span></div>
                                        <ul class="features">
                                            <li class="feature-item">1 domain</li>
                                            <li class="feature-item">8,000 visitors / month</li>
                                            <li class="feature-item">Unlimited privacy regulations</li>
                                            <li class="feature-item">Unlimited seats</li>
                                            <li class="feature-item">Unlimited subpages</li>
                                            <li class="feature-item">5 banner languages</li>
                                        </ul>
                                        <button class="view-details">View plan details</button>
                                    </div>

                                    <div class="plan-card">
                                        <h3>CMP Pro Web</h3>
                                        <p class="plan-description">Robust consent management solutions designed for small teams</p>
                                        <div class="price">€30 <span>per month</span></div>
                                        <ul class="features">
                                            <li class="feature-item">3 domains</li>
                                            <li class="feature-item">15,000 visitors / month</li>
                                            <li class="feature-item">Unlimited privacy regulations</li>
                                            <li class="feature-item">Unlimited seats</li>
                                            <li class="feature-item">Unlimited subpages</li>
                                            <li class="feature-item">5 banner languages</li>
                                        </ul>
                                        <button class="view-details">View plan details</button>
                                    </div>

                                    <div class="plan-card">
                                        <h3>CMP Business Web</h3>
                                        <p class="plan-description">Optimize for scale with advanced data-driven insights</p>
                                        <div class="price">€50 <span>per month</span></div>
                                        <ul class="features">
                                            <li class="feature-item">10 domains</li>
                                            <li class="feature-item">50,000 visitors / month</li>
                                            <li class="feature-item">Unlimited privacy regulations</li>
                                            <li class="feature-item">Unlimited seats</li>
                                            <li class="feature-item">Unlimited subpages</li>
                                            <li class="feature-item">Unlimited banner languages</li>
                                        </ul>
                                        <button class="view-details">View plan details</button>
                                    </div> --}}
                                </div>
                            </div>
                        </div>

                        <!-- Static footer -->
                        <div class="static-footer">
                            <div class="or-section">
                                <div class="or-divider">
                                    <span class="or-text">OR</span>
                                </div>
                                <p class="downgrade-text">Downgrade to our <a href="#">Free Plan</a></p>
                            </div>
                        </div>
                    </div>

                    <!-- New bottom footer bar -->
                    <div class="bottom-footer">
                        <div class="price-note">
                            Shown prices are in Euro (EUR) excl. VAT
                        </div>
                        <button class="continue-button">
                            Continue to Payment
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<style>
:root {
    --primary-color: #000;
    --secondary-color: #666;
    --border-color: #e0e0e0;
    --background-light: #f5f5f5;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
}

body {
    background-color: #fff;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

/* Header Styles */
header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.back-button {
    text-decoration: none;
    color: var(--primary-color);
    font-weight: 500;
}

.header-icons {
    display: flex;
    gap: 10px;
}

.icon-button {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 18px;
}

/* Current Plan Styles */
.current-plan {
    background-color: white;
    border-radius: 8px;
    padding: 24px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    margin-bottom: 40px;
}

.current-plan .plan-card {
    background-color: white;
    border: none;
    padding: 7px;
    box-shadow: none;
}

.plan-card {
    width: 280px;
    flex: 0 0 280px;
    background-color: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 24px;
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
}

/* Hover effect */
.plan-card:hover {
    border-color: #144B8C; /* Dark blue border */
    box-shadow: 0 4px 12px rgba(20, 75, 140, 0.15); /* Subtle blue shadow */
    transform: translateY(-2px); /* Slight lift effect */
}

/* Make the plan title transition smoothly */
.plan-card h3 {
    color: #333;
    margin-bottom: 10px;
    transition: color 0.3s ease;
}

.plan-card:hover h3 {
    color: #144B8C; /* Dark blue on hover */
}

/* Optional: Make the "View plan details" link more prominent on hover */
.view-details {
    color: #2196F3;
    text-decoration: none;
    transition: color 0.3s ease;
}

.plan-card:hover .view-details {
    color: #144B8C;
    text-decoration: underline;
}

.plan-header {
    
    align-items: center;
    gap: 12px;
    margin-bottom: 15px;
}

.plan-badge {
    display: inline-block;
    background-color: #ff6b6b;
    color: white;
    padding: 4px 12px;
    border-radius: 4px;
}

.trial-expired-badge {
    display: inline-flex;
    align-items: center;
    background-color: rgba(255, 107, 107, 0.1); /* Light red transparent background */
    color: #ff6b6b; /* Same red as plan-badge */
    padding: 4px 12px;
    border-radius: 4px;
    font-size: 14px;
    float: right;
}

.plan-expired {
    background-color: #fff3f3;
    padding: 15px;
    border-radius: 6px;
    margin-bottom: 20px;
}

.warning-icon {
    color: #ff6b6b;
    margin-right: 5px;
}

.detail-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid var(--border-color);
}

/* Plan Selection Styles */
.plan-selection h2 {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.currency-selector {
    display: flex;
    align-items: center;
    gap: 5px;
}

.toggle-button {
    background: none;
    border: 1px solid var(--border-color);
    border-radius: 4px;
    padding: 2px 8px;
    cursor: pointer;
}

.plans-scroll-container {
    overflow-x: auto;
    padding-bottom: 20px; /* Space for scrollbar */
    margin-bottom: 20px;
    -webkit-overflow-scrolling: touch; /* Smooth scrolling on iOS */
}

.plans-grid {
    display: flex; /* Change from grid to flex */
    gap: 20px;
    min-width: min-content; /* Ensures cards don't shrink below their minimum width */
}

.plan-card {
    min-width: 280px; /* Fixed minimum width for each card */
    flex: 0 0 auto; /* Prevent cards from growing or shrinking */
}

.plan-card h3 {
    margin-bottom: 10px;
}

.plan-description {
    color: var(--secondary-color);
    margin-bottom: 20px;
    font-size: 14px;
}

.price {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 20px;
}

.price span {
    font-size: 14px;
    font-weight: normal;
    color: var(--secondary-color);
}

.features {
    list-style: none;
    margin-bottom: 20px;
}

.feature-item {
    padding: 8px 0;
    display: flex;
    align-items: center;
    font-size: 14px;
}

.feature-item::before {
    content: "✓";
    display: inline-block;
    width: 16px;
    height: 16px;
    margin-right: 12px;
    color: #144B8C; /* Dark blue color */
    font-weight: bold;
    text-align: center;
    line-height: 16px;
}

.view-details {
    background: none;
    border: none;
    color: #2196F3;
    cursor: pointer;
    text-decoration: underline;
    padding: 5px 0;
}

/* Footer Styles */
.footer-text {
    text-align: center;
    margin: 30px 0;
}

.or-text {
    margin-bottom: 10px;
    color: var(--secondary-color);
}

.downgrade-text a {
    color: #2196F3;
    text-decoration: none;
}

.price-note {
   
    font-size: 12px;
    margin-top: 5px;
}




/* Add these new styles and modify the pricing-container */
.pricing-container {
    width: 100%;
    padding: 20px;
}

.row {
    display: flex;
    margin: 0 -15px;
    background-color: #f5f5f5;
    padding: 30px 15px;
    border-radius: 8px;
}

.white-wrapper {
    background-color: white;
    border-radius: 8px;
    padding: 24px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.col-4 {
    width: 33.333%;
    padding: 0;
    flex-shrink: 0;
}

.col-8 {
    width: 66.667%;
    padding: 0 15px;
}

.scroll-wrapper {
    overflow-x: auto;
    padding-bottom: 20px;
    -webkit-overflow-scrolling: touch;
    margin-bottom: 20px;
    /* Add subtle fade effect to indicate scrollable content */
    background: 
        linear-gradient(to right, white 0%, rgba(255,255,255,0) 2%),
        linear-gradient(to left, white 98%, rgba(255,255,255,0) 100%);
    background-repeat: no-repeat;
}

.scrollable-content {
    min-width: 1200px;
}

.plans-grid {
    display: flex;
    gap: 20px;
    padding: 4px; /* Add slight padding for shadow visibility */
}

/* Header section inside scroll area */
.scrollable-content h2 {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.currency-selector {
    display: flex;
    align-items: center;
    gap: 5px;
}

/* Static footer styles */
.static-footer {
    border-top: 1px solid #e0e0e0;
    padding-top: 20px;
    margin-top: 20px;
    text-align: center;
}

.or-section {
    margin-bottom: 20px;
}

.or-text {
    margin-bottom: 10px;
    color: #666;
}

.downgrade-text a {
    color: #2196F3;
    text-decoration: none;
}

/* New bottom footer styles */
.bottom-footer {
    background-color: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0; /* Remove padding from footer */
}

.price-note {
    color: #666;
    font-size: 14px;
    padding: 16px 24px; /* Move padding to price note */
}

.continue-button {
    background-color: #000;
    color: white;
    border: none;
    border-radius: 0 8px 8px 0; /* Round only right corners */
    padding: 16px 24px;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s ease;
    margin: 5px; /* Push button to the right */
    height: 100%; /* Make button full height */
}

.continue-button:hover {
    background-color: #333;
}

/* Scrollbar styling */
.scroll-wrapper::-webkit-scrollbar {
    height: 6px;
}

.scroll-wrapper::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.scroll-wrapper::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 3px;
}

.scroll-wrapper::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Responsive styles */
@media (max-width: 992px) {
    .row {
        flex-direction: column;
    }
    
    .col-4, .col-8 {
        width: 100%;
        margin-bottom: 20px;
    }
} 


/* Modify plans-grid to work better in the new layout */
.plans-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
} 

/* Static footer styles */
.static-footer {
    border-top: 1px solid #e0e0e0;
    padding-top: 20px;
    margin-top: 20px;
}

.footer-text {
    text-align: center;
    margin-bottom: 30px;
}

.or-text {
    margin-bottom: 10px;
    color: #666;
}

.downgrade-text {
    margin-bottom: 15px;
}

.downgrade-text a {
    color: #2196F3;
    text-decoration: none;
}

.price-note {
    color: #666;
    font-size: 12px;
}

.continue-button {
    display: block;
    width: 200px;
    margin: 5px;
    padding: 12px 24px;
    background-color: #000;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-weight: 500;
}

.header-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    padding: 0 4px; /* Optional: align with scrollable content */
}

.currency-dropdown {
    position: relative;
    display: inline-block;
}

.currency-button {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background-color: white;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    color: #333;
}

.currency-button:hover {
    background-color: #f5f5f5;
}

.dropdown-icon {
    transition: transform 0.2s ease;
}

.currency-button.active .dropdown-icon {
    transform: rotate(180deg);
}

.dropdown-content {
    display: none;
    position: absolute;
    right: 0;
    top: calc(100% + 4px);
    min-width: 120px;
    background-color: white;
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    z-index: 1000;
}

.dropdown-content.show {
    display: block;
}

.dropdown-item {
    color: #333;
    padding: 10px 16px;
    text-decoration: none;
    display: block;
}

.dropdown-item:hover {
    background-color: #f5f5f5;
}

.or-divider {
    display: flex;
    align-items: center;
    text-align: center;
    margin: 20px 0;
}

.or-text {
    color: #666;
    padding: 0 16px;
    position: relative;
}

.or-divider::before,
.or-divider::after {
    content: "";
    flex: 1;
    border-top: 1px solid #e0e0e0;
}

.downgrade-text {
    margin-top: 15px;
} 

/* font awesome */
.header-icon {
            margin-left: 15px;
            cursor: pointer;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.2s;
            color: #555;
            font-size: 18px;
        }
        
.header-icon:hover {
            background-color: rgba(0,0,0,0.04);
            color: #1da1f2;
        }

</style>

<style>
   
    #openPopupBtn {
      padding: 12px 20px;
      font-size: 16px;
      background-color: #007BFF;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
    }

    #openPopupBtn:hover {
      background-color: #0056b3;
    }

    .overlay {
      display: none;
    }

    .overlay.show {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      backdrop-filter: blur(8px);
      background: rgba(0, 0, 0, 0.3);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 1000;
    }

    .popup-form {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 12px;
      padding: 30px 25px;
      width: 100%;
      max-width: 350px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
      text-align: center;
    }

    .logo {
      width: 80px;
      margin-bottom: 15px;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    form input {
      padding: 10px 14px;
      border: 1px solid #ccc;
      border-radius: 8px;
      background: #f9f9f9;
      font-size: 15px;
      width: 100%;
      box-sizing: border-box;
    }

    form input:focus {
      border-color: #007BFF;
      outline: none;
    }

    form button {
      padding: 12px;
      background-color: #007BFF;
      color: white;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s;
      width: 100%;
      box-sizing: border-box;
    }

    form button:hover {
      background-color: #0056b3;
    }


    /* Pill Span Styles */
    .animated-pill {
      display: inline-block;
      position: relative;
      padding: 10px 24px;
      border-radius: 50px;
      background: rgba(255, 255, 255, 0.1);
      color: #007BFF;
      font-weight: bold;
      overflow: hidden;
      border: 1px solid rgba(0, 123, 255, 0.3);
      backdrop-filter: blur(4px);
      z-index: 1;
    }

    .animated-pill::before {
      content: "";
      position: absolute;
      top: 0;
      left: -150%;
      width: 300%;
      height: 100%;
      background: linear-gradient(
        120deg,
        transparent 30%,
        rgba(0, 123, 255, 0.3),
        transparent 70%
      );
      animation: slide-pulse 3s linear infinite;
      z-index: -1;
    }

    @keyframes slide-pulse {
      0% {
        left: -150%;
      }
      100% {
        left: 100%;
      }
    }
  </style>

<script>
function toggleDropdown() {
    const dropdown = document.getElementById('currencyDropdown');
    const button = document.querySelector('.currency-button');
    dropdown.classList.toggle('show');
    button.classList.toggle('active');
}

// Close dropdown when clicking outside
window.onclick = function(event) {
    if (!event.target.matches('.currency-button') && 
        !event.target.matches('.dropdown-icon')) {
        const dropdowns = document.getElementsByClassName('dropdown-content');
        const buttons = document.getElementsByClassName('currency-button');
        
        for (let dropdown of dropdowns) {
            if (dropdown.classList.contains('show')) {
                dropdown.classList.remove('show');
            }
        }
        
        for (let button of buttons) {
            if (button.classList.contains('active')) {
                button.classList.remove('active');
            }
        }
    }
}

// Add this script at the end of your body tag or in your script file
document.addEventListener('DOMContentLoaded', function() {
    const currencyBtn = document.getElementById('currencyBtn');
    const currencyDropdown = document.getElementById('currencyDropdown');

    // Toggle dropdown on button click
    currencyBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        currencyBtn.classList.toggle('active');
        currencyDropdown.classList.toggle('show');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!currencyBtn.contains(e.target)) {
            currencyBtn.classList.remove('active');
            currencyDropdown.classList.remove('show');
        }
    });

    // Handle dropdown item clicks
    const dropdownItems = document.querySelectorAll('.dropdown-item');
    dropdownItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            currencyBtn.textContent = this.textContent;
            currencyBtn.appendChild(createDropdownIcon());
            currencyDropdown.classList.remove('show');
            currencyBtn.classList.remove('active');
        });
    });
});

// Helper function to create dropdown icon
function createDropdownIcon() {
    const svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
    svg.setAttribute('class', 'dropdown-icon');
    svg.setAttribute('width', '12');
    svg.setAttribute('height', '12');
    svg.setAttribute('viewBox', '0 0 24 24');
    
    const path = document.createElementNS("http://www.w3.org/2000/svg", "path");
    path.setAttribute('d', 'M7 10l5 5 5-5z');
    path.setAttribute('fill', 'currentColor');
    
    svg.appendChild(path);
    return svg;
} 
</script>

<script>

let userIsLoggedIn = {{ auth()->check() ? 'true' : 'false' }};

if (userIsLoggedIn) {
    console.log("User is logged in.");

} else {
    console.log("User is not logged in.");
    openPopup();
}

function openPopup() {
    const overlay = document.getElementById('popupOverlay');
    overlay.classList.add('show');
}

    

    
  </script>

<script>
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault();
    
        fetch('{{ route("frontend.auth.login") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                email: this.email.value,
                password: this.password.value
            })
        })
        .then(response => {
            if (response.ok) return response.json();
            return response.json().then(err => Promise.reject(err));
        })
        .then(data => {
            document.getElementById('loginMessage').innerText = 'Login successful!';
            // You can optionally update UI or fetch user data here
            const overlay = document.getElementById('popupOverlay');
            overlay.classList.remove('show');
            window.location.reload();

        })
        .catch(error => {
            document.getElementById('loginMessage').innerText = error.message || 'Login failed';
        });
    });
    </script>
    
</html>