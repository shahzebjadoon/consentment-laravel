<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} | {{ $company->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Montserrat', sans-serif;
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
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
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
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.03);
            height: 70px;
            position: sticky;
            top: 0;
            z-index: 5;
        }

        .nav-link {
            padding: 14px 20px;
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
            font-weight: 500;
        }

        .nav-link:hover:not(.active) {
            background-color: rgba(0, 0, 0, 0.02);
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
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        }

        .search-input:focus {
            outline: none;
            border-color: #1da1f2;
            box-shadow: 0 0 0 3px rgba(29, 161, 242, 0.1);
        }

        .card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
            border: 1px solid #f0f0f0;
            margin-bottom: 20px;
        }

        .card-header {
            padding: 20px;
            border-bottom: 1px solid #f5f5f5;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }

        .card-body {
            padding: 20px;
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

        .btn-primary {
            background-color: #1da1f2;
            color: white;
            padding: 12px 20px;
        }

        .btn-primary:hover {
            background-color: #1a91da;
            box-shadow: 0 2px 8px rgba(29, 161, 242, 0.3);
        }

        .btn-secondary {
            background-color: #f5f5f5;
            color: #555;
        }

        .btn-secondary:hover {
            background-color: #e9e9e9;
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
            background-color: rgba(0, 0, 0, 0.04);
            color: #1da1f2;
        }

        .user-dropdown,
        .help-dropdown {
            position: relative;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            top: 50px;
            background-color: #fff;
            min-width: 240px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
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

        .logo-container {
            padding: 25px 20px;
            margin-bottom: 10px;
            border-bottom: 1px solid #f5f5f5;
        }

        .logo {
            max-width: 180px;
        }

        .overview-text {
            font-weight: 600;
            font-size: 14px;
            letter-spacing: 0.5px;
            color: #666;
        }

        .company-badge {
            display: inline-block;
            background-color: #f0f0f0;
            color: #666;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
            margin-left: 10px;
        }

        .company-badge.admin {
            background-color: #e6f7ff;
            color: #1da1f2;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #f0f0f0;
        }

        .table th {
            color: #666;
            font-weight: 600;
            font-size: 14px;
        }

        .table td {
            color: #333;
            font-size: 14px;
        }

        .table tr:hover {
            background-color: #f9f9f9;
        }

        .table-actions {
            display: flex;
            gap: 10px;
        }

        .action-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
            color: #666;
        }

        .action-icon:hover {
            background-color: #f0f0f0;
            color: #1da1f2;
        }

        .premium-badge {
            display: inline-flex;
            align-items: center;
            background-color: #fff8e1;
            color: #ffa000;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
            margin-left: 10px;
        }

        .premium-badge i {
            margin-right: 5px;
        }

        .form-row {
            display: flex;
            margin: 0 -10px 20px;
        }

        .form-column {
            flex: 1;
            padding: 0 10px;
        }

        .form-group {
            margin-bottom: 20px;
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
        }

        .form-control:focus {
            outline: none;
            border-color: #1da1f2;
            box-shadow: 0 0 0 3px rgba(29, 161, 242, 0.1);
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            font-size: 14px;
            color: #666;
        }

        .breadcrumb a {
            color: #666;
            text-decoration: none;
            transition: color 0.2s;
        }

        .breadcrumb a:hover {
            color: #1da1f2;
        }

        .breadcrumb i {
            margin: 0 8px;
            font-size: 12px;
        }

        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 20px;
            text-align: center;
        }

        .empty-state-icon {
            font-size: 48px;
            color: #ccc;
            margin-bottom: 20px;
        }

        .empty-state-text {
            max-width: 500px;
            margin-bottom: 20px;
            color: #666;
        }

        .map-container {
            height: 300px;
            background-color: #f0f0f0;
            border-radius: 8px;
            overflow: hidden;
            position: relative;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
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
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            transform: translateY(-20px);
            transition: transform 0.3s;
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
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar-item {
            display: block;
            justify-content: space-between;
            align-items: center;
            padding-right: 15px;
        }

        .premium-icon {
            background-color: #ffd600;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <div class="logo-container">
                <img src="{{ asset('img/brand/logo.png') }}" alt="Consentment Logo" class="logo">
            </div>

            @if (request()->routeIs('frontend.configurations.edit') ||
                    request()->routeIs('frontend.analytics.*') ||
                    request()->routeIs('frontend.service-settings.*') ||
                    request()->routeIs('frontend.appearance.*') ||
                    request()->routeIs('frontend.content.*') ||
                    request()->routeIs('frontend.implementation.*'))
                <!-- Configuration Edit Sidebar -->
                <a href="{{ route('frontend.companies.configurations', $company->id) }}" class="nav-link">
                    <span class="nav-link-icon"><i class="fas fa-arrow-left"></i></span>
                    Back to Main Menu
                </a>

                <div class="sidebar-item">
                    <a href="{{ route('frontend.analytics.index', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}"
                        class="nav-link {{ request()->routeIs('frontend.analytics.*') ? 'active' : '' }}">
                        <span class="nav-link-icon"><i class="fas fa-chart-bar"></i></span>
                        Analytics
                    </a>

                </div>

                <div class="sidebar-item">
                    <a href="{{ route('frontend.configurations.edit', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}"
                        class="nav-link {{ request()->routeIs('frontend.configurations.*') ? 'active' : '' }}">
                        <span class="nav-link-icon"><i class="fas fa-cog"></i></span>
                        Configuration
                    </a>

                </div>

                <div class="sidebar-item">
                    <a href="{{ route('frontend.service-settings.scanner', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}"
                        class="nav-link {{ request()->routeIs('frontend.service-settings.*') ? 'active' : '' }}">
                        <span class="nav-link-icon"><i class="fas fa-wrench"></i></span>
                        Service Settings
                    </a>
                </div>

                <div class="sidebar-item">
                    <a href="{{ route('frontend.appearance.layout', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}"
                        class="nav-link {{ request()->routeIs('frontend.appearance.*') ? 'active' : '' }}">
                        <span class="nav-link-icon"><i class="fas fa-palette"></i></span>
                        Appearance
                    </a>
                    {{-- <div class="premium-icon">
                            <i class="fas fa-bolt"></i>
                        </div> --}}
                </div>

                <div class="sidebar-item">
                    <a href="{{ route('frontend.content.first-layer', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}"
                        class="nav-link {{ request()->routeIs('frontend.content.*') ? 'active' : '' }}">
                        <span class="nav-link-icon"><i class="fas fa-file-alt"></i></span>
                        Content
                    </a>

                </div>

                <div class="sidebar-item">
                    <a href="#" class="nav-link">
                        <span class="nav-link-icon"><i class="fas fa-puzzle-piece"></i></span>
                        Integrations
                    </a>
                </div>

                <div class="sidebar-item">
                    <a href="{{ route('frontend.implementation.script-tag', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}"
                        class="nav-link {{ request()->routeIs('frontend.implementation.*') ? 'active' : '' }}">
                        <span class="nav-link-icon"><i class="fas fa-code"></i></span>
                        Implementation
                    </a>

                </div>
            @else
                <!-- Default Company Sidebar -->
                <a href="{{ route('frontend.dashboard') }}" class="nav-link">
                    <span class="nav-link-icon"><i class="fas fa-arrow-left"></i></span>
                    Back to Companies
                </a>

                <a href="{{ route('frontend.companies.configurations', $company->id) }}"
                    class="nav-link {{ $activeTab == 'configurations' ? 'active' : '' }}">
                    <span class="nav-link-icon"><i class="fas fa-cog"></i></span>
                    Configurations
                </a>

                <a href="{{ route('frontend.companies.geolocation', $company->id) }}"
                    class="nav-link {{ $activeTab == 'geolocation' ? 'active' : '' }}">
                    <span class="nav-link-icon"><i class="fas fa-globe"></i></span>
                    Geolocation Rulesets
                </a>

                <a href="{{ route('frontend.companies.users', $company->id) }}"
                    class="nav-link {{ $activeTab == 'users' ? 'active' : '' }}">
                    <span class="nav-link-icon"><i class="fas fa-users"></i></span>
                    User Management
                </a>

                <a href="{{ route('frontend.companies.details', $company->id) }}"
                    class="nav-link {{ $activeTab == 'details' ? 'active' : '' }}">
                    <span class="nav-link-icon"><i class="fas fa-building"></i></span>
                    Company Details
                </a>
            @endif
        </div>

        <div class="main-content">
            <div class="header">
                <div class="breadcrumb">
                    <a href="{{ route('frontend.dashboard') }}">OVERVIEW</a>
                    <i class="fas fa-chevron-right"></i>
                    <a href="#">{{ $company->name }}</a>
                    <span class="company-badge admin">Admin</span>
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
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-user-cog"></i> Account & Billing
                            </a>
                            <a href="{{ route('frontend.auth.logout') }}" class="dropdown-item">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content">
                @yield('content')
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
    </script>

    @yield('scripts')
</body>

</html>
