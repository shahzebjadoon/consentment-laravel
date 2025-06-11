<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} | Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&family=Urbanist:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    {{-- <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet"> --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Urbanist', sans-serif;
        }
        
        body {
            background-color: #ffffff;
            color: #333;
        }
        
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 250px;
            background-color: #fff;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
            z-index: 10;
            position: fixed;
            height: 100vh;
        }
        
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            margin-left: 250px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.03);
            height: 70px;
            position: sticky;
            top: 0;
            z-index: 5;
        }
        
        .nav-link {
            padding: 10px 20px;
            display: flex;
            align-items: center;
            color: #555;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }
        
        .nav-link.active {
            background-color: rgba(29, 161, 242, 0.08);
            color: #1da1f2;
            border-left: 3px solid #1da1f2;
            font-weight: 800;
        }
        
        .nav-link:hover:not(.active) {
            background-color: rgba(0,0,0,0.02);
        }
        
        .nav-link-icon {
            margin-right: 12px;
            width: 20px;
            text-align: center;
            color: #666;
        }
        
        .nav-link.active .nav-link-icon {
            color: #1da1f2;
        }
        
        .content {
            padding: 30px;
            flex: 1;
        }
        
        .page-title {
            font-size: 26px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
        }
        
        .page-description {
            color: #666;
            margin-bottom: 25px;
            font-weight: 400;
        }
        
        .search-bar {
            margin-bottom: 25px;
            position: relative;
        }
        
        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
        }
        
        .search-input {
            width: 100%;
            padding: 14px 20px 14px 45px;
            border: 1px solid #e8e8e8;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        }
        
        .search-input:focus {
            outline: none;
            border-color: #1da1f2;
            box-shadow: 0 0 0 3px rgba(29, 161, 242, 0.1);
        }
        
        .company-list {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0,0,0,0.05);
            border: 1px solid #f0f0f0;
        }
        
        .company-item {
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #f5f5f5;
            transition: all 0.2s;
        }
        
        .company-item:hover {
            background-color: #f9f9f9;
        }
        
        .company-item:last-child {
            border-bottom: none;
        }
        
        .company-name {
            display: flex;
            align-items: center;
            font-weight: 500;
            font-size: 15px;
        }
        
        .company-badge {
            display: inline-block;
            background: rgba(29, 161, 242, 0.1);
            color: #1da1f2;
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 12px;
            margin-left: 12px;
            font-weight: 600;
        }
        
        .btn {
            padding: 10px 16px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
        }
        
        .btn-admin {
            background-color: #f5f5f5;
            color: #555;
        }
        
        .btn-admin:hover {
            background-color: #e9e9e9;
        }
        
        .btn-primary {
            background-color: #1da1f2;
            color: white;
            padding: 12px 20px;
        }
        
        .btn-primary:hover {
            background-color: #1a91da;
            box-shadow: 0 2px 8px rgba(29, 161, 242, 0.3);
        }
        
        .header-actions {
            display: flex;
            align-items: center;
        }
        
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
        
        .user-dropdown, .help-dropdown {
            position: relative;
        }
        
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            top: 50px;
            background-color: #fff;
            min-width: 240px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            z-index: 100;
            border-radius: 10px;
            overflow: hidden;
            opacity: 0;
            transform: translateY(-10px);
            transition: all 0.3s;
        }
        
        .dropdown-content.show {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }
        
        .dropdown-item {
            padding: 14px 16px;
            text-decoration: none;
            display: block;
            color: #333;
            transition: all 0.2s;
            font-size: 14px;
        }
        
        .dropdown-item:hover {
            background-color: #f8f9fa;
        }
        
        .dropdown-item i {
            margin-right: 10px;
            color: #666;
            width: 20px;
            text-align: center;
        }
        
        .user-info {
            padding: 20px;
            background-color: #f8f9fa;
            border-bottom: 1px solid #eee;
        }
        
        .user-email {
            font-weight: 500;
            margin-bottom: 5px;
        }
        
        .last-login {
            font-size: 12px;
            color: #666;
        }
        
        .logo-container {
            padding: 25px 20px;
            margin-bottom: 10px;
            border-bottom: 1px solid #f5f5f5;
        }
        
        .logo {
            max-width: 180px;
        }
        
        .add-company-btn {
            margin-left: 20px;
            display: flex;
            align-items: center;
        }
        
        .add-company-btn i {
            margin-right: 8px;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }
        
        .overview-text {
            font-weight: 600;
            font-size: 14px;
            letter-spacing: 0.5px;
            color: #666;
        }
        
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.3s;
        }
        
        .modal.show {
            opacity: 1;
        }
        
        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            width: 100%;
            max-width: 600px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            transform: translateY(-20px);
            transition: transform 0.3s;
            height: 80vh; /* or a fixed height like 600px */
            max-height: 80vh; /* prevents modal from growing beyond viewport */
            overflow: hidden; /* hides overflow of modal-content */
          
        }
        
        .modal.show .modal-content {
            transform: translateY(0);
        }
        
        .modal-header {
            background-color: #0052cc;
            color: white;
            padding: 20px 25px;
            font-weight: 500;
            font-size: 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-body {
            padding: 25px;
             
        }
        
        .modal-footer {
            padding: 20px 25px;
            text-align: right;
            border-top: 1px solid #eee;
        }
        
        .close {
            color: white;
            font-size: 22px;
            font-weight: bold;
            cursor: pointer;
            opacity: 0.8;
            transition: opacity 0.2s;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }
        
        .close:hover {
            opacity: 1;
            background-color: rgba(255,255,255,0.1);
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
            font-size: 14px;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
            background-color: #fff;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #1da1f2;
            box-shadow: 0 0 0 3px rgba(29, 161, 242, 0.1);
        }
        
        .required::after {
            content: "*";
            color: #e53935;
            margin-left: 3px;
        }
        
        .form-row {
            display: flex;
            margin: 0 -10px;
        }
        
        .form-col {
            flex: 1;
            padding: 0 10px;
        }
        
        .accordion {
            margin-top: 30px;
            border: 1px solid #eee;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .accordion-header {
            padding: 15px 20px;
            background-color: #f8f9fa;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .accordion-content {
            padding: 15px 20px;
            border-top: 1px solid #eee;
        }
        
        .checkbox-container {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .checkbox-container input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-right: 10px;
            accent-color: #1da1f2;
        }
        
        .error-message {
            color: #e53935;
            font-size: 12px;
            margin-top: 5px;
        }
        
        .success-message {
            color: #43a047;
            font-size: 12px;
            margin-top: 5px;
        }
        
        .user-access-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }
        
        .user-access-item:last-child {
            border-bottom: none;
        }
        
        .user-access-email {
            display: flex;
            align-items: center;
        }
        
        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #1da1f2;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-size: 14px;
            font-weight: 600;
        }
        
        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 25px;
        }
        
        .step {
            width: 200px;
            text-align: center;
            position: relative;
        }
        
        .step:not(:last-child)::after {
            content: "";
            position: absolute;
            top: 14px;
            right: -50%;
            width: 100%;
            height: 2px;
            background-color: #eee;
            z-index: 1;
        }
        
        .step.active:not(:last-child)::after {
            background-color: #1da1f2;
        }
        
        .step-number {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #eee;
            color: #666;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-weight: 600;
            position: relative;
            z-index: 2;
        }
        
        .step.active .step-number {
            background-color: #1da1f2;
            color: white;
        }
        
        .step-title {
            font-size: 14px;
            color: #666;
            font-weight: 500;
        }
        
        .step.active .step-title {
            color: #1da1f2;
            font-weight: 600;
        }
        
        .modal-step {
            display: none;
        }
        
        .modal-step.active {
            display: block;
            animation: fadeIn 0.3s;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .modal-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
        }
        
        .modal-subtitle {
            color: #666;
            margin-bottom: 25px;
            font-size: 14px;
        }
        
        .permission-link {
            color: #1da1f2;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
            font-size: 14px;
        }
        
        .permission-link i {
            margin-left: 5px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <div class="logo-container">
                <img src="{{ asset('img/brand/logo.svg') }}" alt="Consentment Logo" class="logo">
            </div>
            
            <a href="#" class="nav-link active">
                <span class="nav-link-icon"><i class="fas fa-building"></i></span>
                Companies
            </a>
            <!-- Additional sidebar links would go here -->
        </div>
        
        <div class="main-content">
            <div class="header">
                <div>
                    <span class="overview-text">OVERVIEW</span>
                </div>
                <div class="header-actions">
                    <div class="help-dropdown">
                        <div class="header-icon" id="helpIcon">
                            <i class="fas fa-question-circle"></i>
                        </div>
                        <div class="dropdown-content" id="helpDropdown">
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-book"></i> Knowledge Base
                            </a>
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-file-alt"></i> Documentation
                            </a>
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-headset"></i> Support
                            </a>
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-comment-dots"></i> Send Feedback
                            </a>
                        </div>
                    </div>
                    
                    <div class="user-dropdown">
                        <div class="header-icon" id="userIcon">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <div class="dropdown-content" id="userDropdown">
                            <div class="user-info">
                                <div class="user-email">{{ auth()->user()->email }}</div>
                                <div class="last-login">Last login: {{ now()->format('d/m/Y, H:i:s') }}</div>
                            </div>
                            {{-- <a href="#" class="dropdown-item">
                                <i class="fas fa-user-cog"></i> Account & Billing {{$companies[1]->id}}
                            </a> --}}
                            <a href="{{ route('frontend.auth.logout') }}" class="dropdown-item">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="content">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <div>
                        <h1 class="page-title">Companies</h1>
                        <p class="page-description">Below you will find an overview of all companies to which you have access.</p>
                    </div>
                    <button class="btn btn-primary add-company-btn" id="addCompanyBtn">
                        <i class="fas fa-plus"></i> Add Company
                    </button>
                </div>
                
                <div class="search-bar">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Search for Company Name or Configuration ID">
                </div>
                
                <div class="company-list">
                    @if(count($companies) > 0)
                        @foreach($companies as $company)
                        <div class="company-item" onclick="window.location.href='{{ route('frontend.companies.configurations', $company->id) }}'" style="cursor: pointer;">
                            <div class="company-name">
                                {{ $company->name }}
                                <span class="company-badge">1</span>
                            </div>
                            <div>
                                <button class="btn btn-admin" onclick="event.stopPropagation();">Admin</button>
                                <a href="{{ route('frontend.companies.configurations', $company->id) }}" class="btn" onclick="event.stopPropagation();"><i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <p>No companies found. Click "Add Company" to create your first company.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add Company Modal (with multi-step form) -->
    <div id="companyModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Create Company</h3>
                <span class="close" id="closeModal">&times;</span>
            </div>

            <div style="height: 80%; overflow-y:auto;">

         
            
            <form id="companyForm" method="POST" action="{{ route('frontend.companies.store') }}">
                @csrf
                <div class="modal-body">
                    <!-- Step indicators -->
                    <div class="step-indicator">
                        <div class="step active" data-step="1">
                            <div class="step-number">1</div>
                            <div class="step-title">Company Details</div>
                        </div>
                        <div class="step" data-step="2">
                            <div class="step-number">2</div>
                            <div class="step-title">Manage Access</div>
                        </div>
                    </div>
                    
                    <!-- Step 1: Company Details -->
                    <div class="modal-step active" data-step="1">
                        <h4 class="modal-title">Company Details</h4>
                        <p class="modal-subtitle">Which company do you want to add?</p>
                        
                        <div class="form-group">
                            <label class="form-label required">Billing Account</label>
                            <select class="form-control" name="billing_account" required>
                                <option value="">Select Billing Account</option>
                                <option value="main">Main Account</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label required">Company Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Company Name" required>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label class="form-label required">Street</label>
                                    <input type="text" class="form-control" name="street" placeholder="Street" required>
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label class="form-label required">ZIP Code</label>
                                    <input type="text" class="form-control" name="zip_code" placeholder="ZIP Code" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label class="form-label required">City</label>
                                    <input type="text" class="form-control" name="city" placeholder="City" required>
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label class="form-label required">Country</label>
                                    <select class="form-control" name="country" required>
                                        <option value="">Select Country</option>
                                        <option value="US">United States</option>
                                        <option value="UK">United Kingdom</option>
                                        <option value="CA">Canada</option>
                                        <!-- Add more countries here -->
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Step 2: Manage Access -->
                    <div class="modal-step" data-step="2">
                        <h4 class="modal-title">Manage Company Access</h4>
                        <p class="modal-subtitle">Add and manage users to grant access to all configurations within your company.</p>
                        
                        <div class="form-group">
                            <div class="form-row">
                                <div class="form-col">
                                    <label class="form-label required">Email</label>
                                    <input type="email" class="form-control" id="userEmail" placeholder="Email" required>
                                </div>
                                <div class="form-col">
                                    <label class="form-label required">Permission</label>
                                    <select class="form-control" id="userPermission">
                                        <option value="write">Write</option>
                                        <option value="read">Read</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                                <div style="margin-top: 30px; padding-left: 10px;">
                                    <button type="button" class="btn btn-primary" id="addUserBtn">Add</button>
                                </div>
                            </div>
                            
                            <div class="checkbox-container" style="margin-top: 10px;">
                                <input type="checkbox" id="notifyUser">
                                <label for="notifyUser">Notify user via email</label>
                            </div>
                            
                            <a href="#" class="permission-link">
                                See permission details <i class="fas fa-chevron-down"></i>
                            </a>
                        </div>
                        
                        <div class="accordion">
                            <div class="accordion-header">
                                <span>Company Access</span>
                                <span><i class="fas fa-chevron-down"></i></span>
                            </div>
                            <div class="accordion-content">
                                <div class="user-access-item">
                                    <div class="user-access-email">
                                        <div class="user-avatar">J</div>
                                        <span>{{ auth()->user()->email }}</span>
                                    </div>
                                    <div>
                                        <span class="btn btn-admin">Admin</span>
                                        <span class="btn"><i class="fas fa-ellipsis-v"></i></span>
                                    </div>
                                </div>
                                <div id="additionalUsers">
                                    <!-- Additional users will be added here by JavaScript -->
                                </div>
                            </div>
                        </div>
                        
                        <!-- Hidden fields to store user data -->
                        <input type="hidden" name="email" id="accessEmail">
                        <input type="hidden" name="permission" id="accessPermission">
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn" id="backBtn" style="display:none;">Back</button>
                    <button type="button" class="btn" id="cancelBtn">Cancel</button>
                    <button type="button" class="btn btn-primary" id="nextBtn">Next</button>
                    <button type="button" class="btn btn-primary" id="createCompanyBtn" style="display:none;">Create Company</button>
                </div>
            </form>
            </div>
        </div>
    </div>
    
    <script>
        // Toggle dropdowns
        document.getElementById('userIcon').addEventListener('click', function() {
            document.getElementById('userDropdown').classList.toggle('show');
            document.getElementById('helpDropdown').classList.remove('show');
        });
        
        document.getElementById('helpIcon').addEventListener('click', function() {
            document.getElementById('helpDropdown').classList.toggle('show');
            document.getElementById('userDropdown').classList.remove('show');
        });
        
        // Close dropdowns when clicking outside
        window.addEventListener('click', function(event) {
            if (!event.target.matches('.header-icon') && !event.target.matches('.header-icon *')) {
                const dropdowns = document.getElementsByClassName('dropdown-content');
                for (let i = 0; i < dropdowns.length; i++) {
                    const openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        });
        
        // Modal and multi-step form functionality
        const modal = document.getElementById('companyModal');
        const addCompanyBtn = document.getElementById('addCompanyBtn');
        const closeModal = document.getElementById('closeModal');
        const cancelBtn = document.getElementById('cancelBtn');
        const nextBtn = document.getElementById('nextBtn');
        const backBtn = document.getElementById('backBtn');
        const createCompanyBtn = document.getElementById('createCompanyBtn');
        
        // Step navigation
        let currentStep = 1;
        const totalSteps = 2;
        
        function showStep(step) {
            // Hide all steps
            document.querySelectorAll('.modal-step').forEach(el => {
                el.classList.remove('active');
            });
            
            // Show the current step
            document.querySelector(`.modal-step[data-step="${step}"]`).classList.add('active');
            
            // Update step indicators
            document.querySelectorAll('.step').forEach(el => {
                el.classList.remove('active');
            });
            for (let i = 1; i <= step; i++) {
                document.querySelector(`.step[data-step="${i}"]`).classList.add('active');
            }
            
            // Update buttons
            if (step === 1) {
                backBtn.style.display = 'none';
                nextBtn.style.display = 'inline-block';
                createCompanyBtn.style.display = 'none';
            } else if (step === totalSteps) {
                backBtn.style.display = 'inline-block';
                nextBtn.style.display = 'none';
                createCompanyBtn.style.display = 'inline-block';
            } else {
                backBtn.style.display = 'inline-block';
                nextBtn.style.display = 'inline-block';
                createCompanyBtn.style.display = 'none';
            }
            
            currentStep = step;
        }
        
        // Open modal
        addCompanyBtn.addEventListener('click', function() {
            modal.style.display = 'block';
            setTimeout(() => {
                modal.classList.add('show');
                showStep(1);
            }, 10);
        });
        
        // Close modal
        function closeModalFunc() {
            modal.classList.remove('show');
            setTimeout(() => {
                modal.style.display = 'none';
                document.getElementById('companyForm').reset();
                document.getElementById('additionalUsers').innerHTML = '';
            }, 300);
        }
        
        closeModal.addEventListener('click', closeModalFunc);
        cancelBtn.addEventListener('click', closeModalFunc);
        
        // Next button
        nextBtn.addEventListener('click', function() {
            if (validateStep(currentStep)) {
                showStep(currentStep + 1);
            }
        });
        
        // Back button
        backBtn.addEventListener('click', function() {
            showStep(currentStep - 1);
        });
        
        // Validate each step
        function validateStep(step) {
            if (step === 1) {
                const requiredFields = document.querySelectorAll('.modal-step[data-step="1"] [required]');
                let valid = true;
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.style.borderColor = '#e53935';
                        valid = false;
                    } else {
                        field.style.borderColor = '#ddd';
                    }
                });
                
                return valid;
            }
            
            return true;
        }
        
        // Add User functionality
        document.getElementById('addUserBtn').addEventListener('click', function() {
            const email = document.getElementById('userEmail').value.trim();
            const permission = document.getElementById('userPermission').value;
            
            if (!email) {
                alert('Please enter an email address');
                return;
            }
            
            // Set values in the hidden fields
            document.getElementById('accessEmail').value = email;
            document.getElementById('accessPermission').value = permission;
            
            // Add user to the list
            const userInitial = email.charAt(0).toUpperCase();
            const userContainer = document.getElementById('additionalUsers');
            
            const userElement = document.createElement('div');
            userElement.className = 'user-access-item';
            userElement.innerHTML = `
                <div class="user-access-email">
                    <div class="user-avatar">${userInitial}</div>
                    <span>${email}</span>
                </div>
                <div>
                    <span class="btn btn-admin">${permission.charAt(0).toUpperCase() + permission.slice(1)}</span>
                    <span class="btn"><i class="fas fa-ellipsis-v"></i></span>
                </div>
            `;
            
            userContainer.appendChild(userElement);
            
            // Clear the email input
            document.getElementById('userEmail').value = '';
        });
        
        // Create Company
        createCompanyBtn.addEventListener('click', function() {
            // Get form data
            const form = document.getElementById('companyForm');
            const formData = new FormData(form);
            
            // Submit the form via AJAX
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeModalFunc();
                    alert('Company created successfully!');
                    // Reload the page to show the new company
                    window.location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to create company'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while creating the company');
            });
        });
        
        // Close modals when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target == modal) {
                closeModalFunc();
            }
        });
        
        // Toggle accordion
        document.querySelector('.accordion-header').addEventListener('click', function() {
            this.querySelector('i').classList.toggle('fa-chevron-up');
            this.querySelector('i').classList.toggle('fa-chevron-down');
            const content = this.nextElementSibling;
            if (content.style.maxHeight) {
                content.style.maxHeight = null;
            } else {
                content.style.maxHeight = content.scrollHeight + "px";
            }
        });
        
        // Permission details toggle
        document.querySelector('.permission-link').addEventListener('click', function(e) {
            e.preventDefault();
            this.querySelector('i').classList.toggle('fa-chevron-up');
            this.querySelector('i').classList.toggle('fa-chevron-down');
            // Logic to show permission details would go here
        });
    </script>



{{-- @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: @json(session('success')),
                timer: 7000,
                showConfirmButton: false
            });
        });
    </script>
@endif --}}

{{-- @if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: @json(session('error')),
                timer: 7000,
                showConfirmButton: false
            });
        });
    </script>
@endif --}}




</body>
</html>