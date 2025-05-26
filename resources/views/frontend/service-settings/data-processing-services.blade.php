@extends('frontend.company.layout')



@section('content')

<div class="card" style="margin-bottom: 30px; width: 100%;">

    <div class="card-header" style="border-bottom: none; padding-bottom: 0;">

        <h3 class="page-title">Service Settings <i class="" style="color: #ccc; font-size: 16px; vertical-align: middle;"></i></h3>

    </div>

    <div class="card-body" style="padding-top: 0;">

        <!-- Tabs Navigation -->

        <div style="display: flex; margin-bottom: 20px;">

            <a href="{{ route('frontend.service-settings.scanner', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}" 

               class="tab-link {{ $activeTab == 'scanner' ? 'active' : '' }}" 

               style="padding: 12px 20px; border: 1px solid #dee2e6; border-bottom: none; border-radius: 4px 4px 0 0; text-decoration: none;font-weight: 800; {{ $activeTab == 'scanner' ? 'background-color: white; color: #333; font-weight: 800;' : 'background-color: #f8f9fa; color: #666;' }} margin-right: 5px;">

               Data Processing Service Scanner

            </a>

            <a href="{{ route('frontend.service-settings.services', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}" 

               class="tab-link {{ $activeTab == 'services' ? 'active' : '' }}" 

               style="padding: 12px 20px; border: 1px solid #dee2e6; border-bottom: none; border-radius: 4px 4px 0 0; text-decoration: none; font-weight: 800;{{ $activeTab == 'services' ? 'background-color: white; color: #333; font-weight: 800;' : 'background-color: #f8f9fa; color: #666;' }} margin-right: 5px;">

                Data Processing Services Detected

            </a>

            <a href="{{ route('frontend.service-settings.categories', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}" 

               class="tab-link {{ $activeTab == 'categories' ? 'active' : '' }}" 

               style="padding: 12px 20px; border: 1px solid #dee2e6; border-bottom: none; border-radius: 4px 4px 0 0; text-decoration: none; font-weight: 800;{{ $activeTab == 'categories' ? 'background-color: white; color: #333; font-weight: 800;' : 'background-color: #f8f9fa; color: #666;' }}">

                Categories

            </a>

        </div>

        <div style="border-top: 1px solid #dee2e6; margin-top: -1px;"></div>



        <!-- Data Processing Services Content -->

        <div class="tab-content">

            <div style="margin-top: 20px;">

                <h3 style="margin-bottom: 20px;">Detected Data Processing Services<i class="" style="color: #ccc; font-size: 16px; vertical-align: middle;"></i></h3>

                

<!-- Services List -->

<div class="services-list">

    @forelse($services as $service)

        @php

            $serviceName = $service->name ?? $service->display_name ?? null;

        @endphp

        @if(!empty($serviceName))

        <div class="service-item">

            <div class="service-header">

                <div class="service-info">

                    <h4>{{ $serviceName }}</h4>

                    <span class="service-category">{{ ucfirst($service->category ?? 'Functional') }}</span>

                </div>

                <div class="service-controls">

                    <button class="btn-eye"></button>

                    <span class="service-status live">Live</span>

                    

                </div>

            </div>

        </div>

        @endif

    @empty

    <div style="text-align: center; padding: 20px; color: #666; border: 1px dashed #ddd; border-radius: 6px; margin-bottom: 15px;">

        <p>No data processing services added yet.</p>

        <p>Visit the <a href="{{ route('frontend.service-settings.scanner', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}" style="color: #0066cc; text-decoration: none;">DPS Scanner</a> to detect services or add services manually.</p>

    </div>

    @endforelse

</div>

               

                

               

            

            </div>

        </div>

    </div>

</div>

@endsection



@section('scripts')

<style>

    /* Service Item Styling */

    .services-list {

        margin-bottom: 20px;

    }

    

    .service-item {

        border: 1px solid #e6e8eb;

        border-radius: 6px;

        margin-bottom: 15px;

        overflow: hidden;

    }

    

    .service-header {

        display: flex;

        justify-content: space-between;

        align-items: center;

        padding: 15px 20px;

        background-color: #fff;

        cursor: pointer;

    }

    

    .service-info {

        display: flex;

        flex-direction: column;

    }

    

    .service-info h4 {

        margin: 0;

        font-size: 16px;

        font-weight: 500;

    }

    

    .service-category {

        font-size: 13px;

        color: #666;

    }

    

    .service-controls {

        display: flex;

        align-items: center;

        gap: 15px;

    }

    

    .service-status {

        padding: 5px 10px;

        border-radius: 4px;

        font-size: 12px;

        font-weight: 500;

    }

    

    .service-status.live {

        background-color: #e8f5e9;

        color: #2e7d32;

    }

    

    .btn-toggle, .btn-eye {

        background: none;

        border: none;

        cursor: pointer;

        width: 30px;

        height: 30px;

        display: flex;

        align-items: center;

        justify-content: center;

        color: #666;

        border-radius: 4px;

    }

    

    .btn-toggle:hover, .btn-eye:hover {

        background-color: #f5f5f5;

    }

    

    /* Expanded Service Details */

    .service-details {

        padding: 0 20px 20px;

        border-top: 1px solid #e6e8eb;

        background-color: #f8f9fa;

        display: none;

    }

    

    .service-item.expanded .service-details {

        display: block;

    }

    

    .detail-row {

        padding: 15px 0;

        border-bottom: 1px solid #e6e8eb;

        display: flex;

        justify-content: space-between;

        align-items: flex-start;

    }

    

    .detail-row:last-child {

        border-bottom: none;

    }

    

    .detail-label {

        font-size: 14px;

        color: #333;

    }

    

    .detail-actions {

        display: flex;

        gap: 10px;

        align-items: center;

    }

    

    .btn-action {

        background: none;

        border: none;

        cursor: pointer;

        color: #666;

        padding: 5px 8px;

        border-radius: 4px;

        font-size: 14px;

        display: flex;

        align-items: center;

        gap: 5px;

    }

    

    .btn-action:hover {

        background-color: #f0f0f0;

    }

    

    .view-details {

        color: #0066cc;

    }

    

    .detail-group {

        display: flex;

        flex-direction: column;

        gap: 8px;

        width: 100%;

    }

    

    .detail-group.wide {

        width: 100%;

    }

    

    .detail-group label {

        font-size: 14px;

        color: #333;

        font-weight: 500;

        display: flex;

        align-items: center;

        gap: 5px;

    }

    

    .detail-group label i {

        color: #ccc;

        font-size: 14px;

    }

    

    .dropdown {

        position: relative;

    }

    

    .dropdown-toggle {

        width: 100%;

        padding: 10px 15px;

        border-radius: 4px;

        border: 1px solid #ddd;

        background-color: white;

        text-align: left;

        font-size: 14px;

        display: flex;

        justify-content: space-between;

        align-items: center;

        cursor: pointer;

    }

    

    .dropdown-toggle i {

        font-size: 12px;

    }

    

    .toggle-group {

        display: flex;

        justify-content: space-between;

        align-items: center;

    }

    

    /* Switch Toggle */

    .switch {

        position: relative;

        display: inline-block;

        width: 44px;

        height: 22px;

    }

    

    .switch input {

        opacity: 0;

        width: 0;

        height: 0;

    }

    

    .slider {

        position: absolute;

        cursor: pointer;

        top: 0;

        left: 0;

        right: 0;

        bottom: 0;

        background-color: #ccc;

        -webkit-transition: .4s;

        transition: .4s;

        border-radius: 22px;

    }

    

    .slider:before {

        position: absolute;

        content: "";

        height: 18px;

        width: 18px;

        left: 2px;

        bottom: 2px;

        background-color: white;

        -webkit-transition: .4s;

        transition: .4s;

        border-radius: 50%;

    }

    

    input:checked + .slider {

        background-color: #0066cc;

    }

    

    input:checked + .slider:before {

        -webkit-transform: translateX(22px);

        -ms-transform: translateX(22px);

        transform: translateX(22px);

    }

    

    .version-text {

        font-size: 14px;

        color: #666;

    }

    

    .no-subservices {

        padding: 15px;

        background-color: white;

        border-radius: 4px;

        border: 1px solid #ddd;

        font-size: 14px;

        color: #666;

    }

    

    /* Settings Options */

    .settings-options {

        display: flex;

        flex-direction: column;

        gap: 12px;

    }

    

    .checkbox-group {

        display: flex;

        align-items: center;

        gap: 8px;

    }

    

    .checkbox-group label {

        font-weight: 400;

        font-size: 14px;

        display: flex;

        align-items: center;

        gap: 5px;

    }

    

    /* Add buttons */

    .btn-add-subservice {

        color: #0066cc;

        background: none;

        border: none;

        cursor: pointer;

        padding: 5px 0;

        font-size: 14px;

        display: flex;

        align-items: center;

        gap: 5px;

    }

    

    .btn-add-dps {

        margin-top: 20px;

        padding: 10px 15px;

        border: 1px solid #ddd;

        border-radius: 4px;

        background-color: white;

        font-size: 14px;

        cursor: pointer;

        display: flex;

        align-items: center;

        gap: 8px;

        color: #333;

    }

    

    /* Custom Services Section */

    .custom-services-section {

        margin-top: 40px;

    }

    

    .custom-services-section h3 {

        margin-bottom: 20px;

        display: flex;

        align-items: center;

        gap: 5px;

    }

    

    .custom-buttons {

        display: flex;

        gap: 15px;

    }

    

    .btn-custom-action {

        padding: 10px 15px;

        border: 1px solid #ddd;

        border-radius: 4px;

        background-color: white;

        font-size: 14px;

        cursor: pointer;

        display: flex;

        align-items: center;

        gap: 8px;

        color: #333;

    }

</style>



<script>

    // Toggle service details

    document.querySelectorAll('.service-header').forEach(function(header) {

        header.addEventListener('click', function() {

            const item = this.closest('.service-item');

            const isExpanded = item.classList.contains('expanded');

            

            // Close all other expanded items

            document.querySelectorAll('.service-item.expanded').forEach(function(expandedItem) {

                if (expandedItem !== item) {

                    expandedItem.classList.remove('expanded');

                    const toggleBtn = expandedItem.querySelector('.btn-toggle i');

                    toggleBtn.classList.remove('fa-chevron-up');

                    toggleBtn.classList.add('fa-chevron-down');

                }

            });

            

            // Toggle current item

            if (isExpanded) {

                item.classList.remove('expanded');

                const toggleBtn = item.querySelector('.btn-toggle i');

                toggleBtn.classList.remove('fa-chevron-up');

                toggleBtn.classList.add('fa-chevron-down');

            } else {

                item.classList.add('expanded');

                const toggleBtn = item.querySelector('.btn-toggle i');

                toggleBtn.classList.remove('fa-chevron-down');

                toggleBtn.classList.add('fa-chevron-up');

            }

        });

    });

    

    // Prevent propagation for control buttons

    document.querySelectorAll('.btn-eye').forEach(function(btn) {

        btn.addEventListener('click', function(e) {

            e.stopPropagation();

        });

    });

</script>

@endsection