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
               style="padding: 12px 20px; border: 1px solid #dee2e6; border-bottom: none; border-radius: 4px 4px 0 0; text-decoration: none; font-weight: 800;{{ $activeTab == 'scanner' ? 'background-color: white; color: #333; font-weight: 800;' : 'background-color: #f8f9fa; color: #666;' }} margin-right: 5px;">
               Data Processing Service Scanner
            </a>
            <a href="{{ route('frontend.service-settings.services', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}" 
               class="tab-link {{ $activeTab == 'services' ? 'active' : '' }}" 
               style="padding: 12px 20px; border: 1px solid #dee2e6; border-bottom: none; border-radius: 4px 4px 0 0; text-decoration: none; font-weight: 800; {{ $activeTab == 'services' ? 'background-color: white; color: #333; font-weight: 800;' : 'background-color: #f8f9fa; color: #666;' }} margin-right: 5px;">
                Data Processing Services Detected
            </a>
            <a href="{{ route('frontend.service-settings.categories', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}" 
               class="tab-link {{ $activeTab == 'categories' ? 'active' : '' }}" 
               style="padding: 12px 20px; border: 1px solid #dee2e6; border-bottom: none; border-radius: 4px 4px 0 0; text-decoration: none; font-weight: 800;{{ $activeTab == 'categories' ? 'background-color: white; color: #333; font-weight: 800;' : 'background-color: #f8f9fa; color: #666;' }}">
                Categories
            </a>
        </div>
        <div style="border-top: 1px solid #dee2e6; margin-top: -1px;"></div>

        <!-- DPS Scanner Content -->
        <div class="tab-content">
            <div style="margin-top: 20px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="margin: 0;">Data Processing Service Scanner</h3>
                <div style="display: flex; gap: 10px;">
                    <button id="scanSettingsBtn" class="btn btn-secondary">Scan Settings</button>
                    <button id="startScanBtn" class="btn btn-primary">Start Scan</button>
                </div>
            </div>
            <p style="color: #666; margin-bottom: 20px;">Here you can get an overview of third-party services that were identified on your domains. We recommend including all legally relevant results in your consent banner to obtain user consent.</p>
            <div>

                
                <!-- Domain Statistics -->
                <div style="display: flex; gap: 20px; margin-bottom: 30px;">
                   <div style="flex: 1; border: 1px solid #e6e8eb; border-radius: 8px; padding: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <h4 style="margin: 0;">Domains</h4>
        @php
            $domains = $configuration->domain ? json_decode($configuration->domain, true) : [];
            if (!is_array($domains)) {
                $domains = [$configuration->domain]; // Handle non-JSON data for backward compatibility
            }
            $domainCount = count($domains);
        @endphp
        <span style="background: #f0f0f0; border-radius: 50%; width: 24px; height: 24px; display: flex; justify-content: center; align-items: center; font-size: 14px;">{{ $domainCount }}</span>
    </div>
    <div style="margin-bottom: 10px; max-height: 150px; overflow-y: auto;">
        @if($domainCount > 0)
            @foreach($domains as $domain)
                <div style="margin-bottom: 5px; padding: 8px; background-color: #f8f9fa; border-radius: 4px; display: flex; justify-content: space-between; align-items: center;">
                    <p style="margin: 0; color: #333; font-weight: 500;">{{ $domain }}</p>
                </div>
            @endforeach
        @else
            <p style="margin: 0; color: #666;">No domains added yet</p>
        @endif
    </div>
</div>
                    
                    <div style="flex: 1; border: 1px solid #e6e8eb; border-radius: 8px; padding: 20px;">
                        <h4 style="margin: 0; margin-bottom: 15px;">Last Scan</h4>
                        <p style="margin: 0; color: #666;">
                            @if($lastScanDate)
                                {{ \Carbon\Carbon::parse($lastScanDate)->format('d.m.Y, H:i') }}
                            @else
                                The last scan date is not available
                            @endif
                        </p>
                    </div>
                    
                    <div style="flex: 1; border: 1px solid #e6e8eb; border-radius: 8px; padding: 20px;">
                        <h4 style="margin: 0; margin-bottom: 15px;">Scan Frequency</h4>
                        <p style="margin: 0; color: #333; font-weight: 500;">{{ ucfirst($scanFrequency) }}</p>
                    </div>
                </div>
                
                <!-- Controls -->
                <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                    <div style="display: flex; gap: 10px;">
                        <div class="dropdown" style="position: relative; display: inline-block;">
                            <button class="btn btn-light dropdown-toggle" type="button" style="display: flex; align-items: center; border: 1px solid #dee2e6;">
                                <span style="margin-right: 5px;">Services</span>
                                <i class="fas fa-chevron-down" style="font-size: 12px;"></i>
                            </button>
                        </div>
                        
                        <div class="dropdown" style="position: relative; display: inline-block;">
                            <button class="btn btn-light dropdown-toggle" type="button" style="display: flex; align-items: center; border: 1px solid #dee2e6;">
                                <span style="margin-right: 5px;">Todo</span>
                                <span style="background-color: #f8d7da; color: #721c24; border-radius: 50%; width: 20px; height: 20px; display: flex; justify-content: center; align-items: center; font-size: 12px; margin-right: 5px;">{{ $todoCount }}</span>
                                <i class="fas fa-chevron-down" style="font-size: 12px;"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search" style="border-right: none;">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" style="background-color: white; border-left: none;">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
              


                <!-- Services Table -->

   <div class="wrapper"> 
    <table class="table">
        <thead style="background-color: #f8f9fa;">
            <tr>
                <th style=" white-space: nowrap !important;">
                    Status 
                    <i class="fas fa-sort"></i>
                </th>
                <th>
                    Service
                    <i class="fas fa-sort"></i>
                </th>
                <th style=" white-space: nowrap !important;">
                    Category
                    <i class=""></i>
                </th>
                <th>Domain</th>
                <th>Source</th>
                <th>
                    Date
                    <i class="fas fa-sort"></i>
                </th>
                <th style=" white-space: nowrap !important;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($scans as $scan)
            <tr data-scan-id="{{ $scan->id }}">
                <td>
                    <span style= "background-color: {{ $scan->status == 'todo' ? '#f8d7da' : ($scan->status == 'added' ? '#d4edda' : '#fff3cd') }}; 
                                              color: {{ $scan->status == 'todo' ? '#721c24' : ($scan->status == 'added' ? '#155724' : '#856404') }};">
                        {{ ucfirst($scan->status) }}
                    </span>
                </td>
                <td>
                    <span>
                        {{ $scan->service_name ?? 'unknown' }}
                        <i class=""></i>
                    </span>
                </td>
                <td style=" white-space: nowrap !important;">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light dropdown-toggle" type="button">
                            <span>{{ $scan->category ?? 'Category' }}</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                </td>
                <td>
                    <a href="{{ $scan->service_url }}">{{ $scan->service_url }}</a>
                </td>
                <td>
                    <a href="{{ $scan->source_domain }}">{{ $scan->source_domain }}</a>
                </td>
                <td>
                    {{ $scan->scan_date ? \Carbon\Carbon::parse($scan->scan_date)->format('d.m.Y, H:i') : '-' }}
                </td>
                <td style=" white-space: nowrap !important;">
                    <div>
                        @if($scan->status == 'todo')
                        <div class="dropdown">
                            <button class="btn btn-sm btn-primary dropdown-toggle" type="button">
                                <span>Add Service</span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light dropdown-toggle" type="button">
                                <span>Ignore</span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                        </div>
                        @elseif($scan->status == 'added')
                        <button class="btn btn-sm btn-light" type="button">
                            View Service
                        </button>
                        @else
                        <button class="btn btn-sm btn-light" type="button">
                            Unignore
                        </button>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7">
                    No scan data available. Click "Start Scan" to begin scanning your domain.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
          




                <!-- Pagination -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px;">
                    <div>
                        <a href="#" class="btn btn-sm" style="color: #0066cc; text-decoration: none; display: flex; align-items: center;">
                            <i class="fas fa-download" style="margin-right: 5px;"></i> Download CSV
                        </a>
                    </div>
                    
                    <div style="display: flex; align-items: center;">
                        <span style="margin-right: 15px; color: #666;">
                            {{ $scans->count() > 0 ? '1-'.$scans->count().' of '.$scans->count().' items' : '0 items' }}
                        </span>
                        @if($scans->count() > 0)
                        <div style="display: flex; align-items: center;">
                            <a href="#" class="btn btn-sm" style="color: #666; text-decoration: none; border: 1px solid #dee2e6; padding: 5px 10px; border-radius: 4px; margin-right: 5px;">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                            <a href="#" class="btn btn-sm" style="color: white; text-decoration: none; background-color: #0066cc; border: 1px solid #0066cc; padding: 5px 10px; border-radius: 4px; margin-right: 5px;">
                                1
                            </a>
                            <a href="#" class="btn btn-sm" style="color: #666; text-decoration: none; border: 1px solid #dee2e6; padding: 5px 10px; border-radius: 4px;">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                
                
            </div>
        </div>
    </div>
</div>

<!-- Manual Scan Modal -->
<div id="manualScanModal" class="modal">
    <div class="modal-content" style="max-width: 500px; font-size:20px;">
        <div class="modal-header" style="background-color: #0052cc;">
            <div style="font-size: 20px; font-weight: 500;">Manual Scan</div>
            <span class="close" id="closeManualScanModal">&times;</span>
        </div>
        <div class="modal-body">
            <p>This action will start scanning for all domains. Do you want to proceed?</p>
        </div>
        <div class="modal-footer" style="justify-content: flex-end; display: flex; gap: 10px;">
            <button id="cancelScanBtn" class="btn btn-light" style="background-color: #f8f9fa; border: 1px solid #dee2e6;">Cancel</button>
            <button id="confirmScanBtn" class="btn btn-primary" style="background-color: #0052cc; color: white;">Scan</button>
        </div>
    </div>
</div>

<!-- Scan Settings Modal -->
<div id="scanSettingsModal" class="modal">
    <div class="modal-content" style="max-width: 700px;">
        <div class="modal-header" style="background-color: #0052cc;">
            <div style="font-size: 20px; font-weight: 500;">Scan Settings</div>
            <span class="close" id="closeScanSettingsModal">&times;</span>
        </div>
        <div class="modal-body">
            <h4 style="margin-top: 0; margin-bottom: 10px;">Scanner Settings</h4>
            <p style="color: #666; margin-bottom: 20px;">Your settings are applied to all domains added to the configuration.</p>
            
            <div class="form-group">
                <label for="scanFrequency" class="form-label" style="font-size: 20px;">Scan Frequency</label>
                <div class="dropdown">
                    <button class="form-control dropdown-toggle" type="button" style="display: flex; justify-content: space-between; align-items: center; text-align: left; background-color: white; font-size:20px;">
                        <span>{{ ucfirst($scanFrequency) }} Scan</span>
                        <i class="fas fa-chevron-down" style="font-size: 12px;"></i>
                    </button>
                </div>
            </div>
            
            <div class="form-group">
                <label for="dpsManagement" class="form-label" style="font-size: 20px;">DPS Management</label>
                <div class="dropdown">
                    <button class="form-control dropdown-toggle" type="button" style="display: flex; justify-content: space-between; align-items: center; text-align: left; background-color: white; font-size:20px;">
                        <span>Auto-populate (i.e. automatically add identified DPS)</span>
                        <i class="fas fa-chevron-down" style="font-size: 12px;"></i>
                    </button>
                </div>
            </div>
            <p style="color: #999; font-size: 13px; margin-bottom: 20px; font-size:20px">Auto-population will work from the next completed scan.</p>
            
            <div class="form-group">
                <label class="form-label">Include trackers found when scanning</label>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <p style="color: #666; margin: 0; font-size: 20px; width: 80%;">
                        The Storage Information section within the DPS template will display tracker data (e.g., HTTP cookies) detected via website scans. You can manage these trackers in the Tracker Management settings.
                    </p>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span style="background-color: #fff8e1; color: #ffa000; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 500; display: flex; align-items: center;">
                            <i class="fas fa-bolt" style="margin-right: 5px;"></i> Premium Feature
                        </span>
                        <label class="switch">
                            <input type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer" style="justify-content: space-between; display: flex;">
            <button id="cancelSettingsBtn" class="btn btn-light" style="background-color: #f8f9fa; border: 1px solid #dee2e6; font-size:20px;">Cancel</button>
            <button id="saveSettingsBtn" class="btn btn-light" style="background-color: #f8f9fa; border: 1px solid #dee2e6; font-size:20px;">Save</button>
        </div>
    </div>
</div>

<!-- Success Scan Modal -->
<div id="successScanModal" class="modal">
    <div class="modal-content" style="max-width: 500px;">
        <div class="modal-header" style="background-color: #28a745;">
            <div style="font-size: 18px; font-weight: 500; color: white;">Scan Completed</div>
            <span class="close" id="closeSuccessModal">&times;</span>
        </div>
        <div class="modal-body">
            <p>The domain scan has been completed successfully.</p>
        </div>
        <div class="modal-footer" style="justify-content: flex-end; display: flex; gap: 10px;">
            <button id="okBtn" class="btn btn-primary" style="background-color: #28a745; color: white;">OK</button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<style>
    /* Modal styling */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        z-index: 1000;
    }
    
    .modal.show {
        display: block;
    }
    
    .modal-content {
        background-color: #fff;
        /* margin: 10% auto; */
        border-radius: 6px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        overflow: hidden;
    }
    
    .modal-header {
        padding: 15px 20px;
        font-size: 20px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .modal-body {
        padding: 20px;
        font-size: 20px;
    }
    
    .modal-footer {
        padding: 15px 20px;
        font-size: 20px;
        border-top: 1px solid #e9ecef;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }
    
    .close {
        color: white;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }
    
    /* Toggle switch */
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
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
        transition: .4s;
    }
    
    .slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
    }
    
    input:checked + .slider {
        background-color: #2196F3;
    }
    
    input:checked + .slider:before {
        transform: translateX(26px);
    }
    
    .slider.round {
        border-radius: 24px;
    }
    
    .slider.round:before {
        border-radius: 50%;
    }


    .wrapper {
      margin-bottom: 20px;
      border: 1px solid #dee2e6;
      border-radius: 4px;
      max-width: 100%;
      max-height: 80%; /* Added max-height to enable vertical scroll */
      display: block;
      overflow: auto; /* Changed from overflow-x to full scroll */


    
    }

    .wrapper table {
      width: 100%;
      border-collapse: collapse;
      min-width: 600px;
      table-layout: auto;
    }

    .wrapper i {
        color: #ccc; 
        font-size: 12px; 
        margin-left: 5px;
    }

    .wrapper th,
    .wrapper td {
      /* padding: 8px; */
      text-align: left;
    
      border: 1px solid #dee2e6;
      word-break: break-word; /* Added to allow word wrapping */

      max-width: 700px; /* adjust as needed */
    overflow-wrap: anywhere; /* breaks only long words */
    white-space: normal;

    /* padding: 12px 15px;  */
    border-bottom: 1px solid #dee2e6;
    }
    .wrapper a {
        color: #0066cc; 
        text-decoration: none;
    }
    .wrapper button {
        border: 1px solid #dee2e6; 
        /* padding: 5px 10px;  */
        font-size: 12px;
    }



</style>

<script>
// Get modal elements
const manualScanModal = document.getElementById('manualScanModal');
const scanSettingsModal = document.getElementById('scanSettingsModal');
const successScanModal = document.getElementById('successScanModal');

// Get buttons
const startScanBtn = document.getElementById('startScanBtn');
const scanSettingsBtn = document.getElementById('scanSettingsBtn');
const closeManualScanModal = document.getElementById('closeManualScanModal');
const closeScanSettingsModal = document.getElementById('closeScanSettingsModal');
const closeSuccessModal = document.getElementById('closeSuccessModal');
const cancelScanBtn = document.getElementById('cancelScanBtn');
const confirmScanBtn = document.getElementById('confirmScanBtn');
const cancelSettingsBtn = document.getElementById('cancelSettingsBtn');
const saveSettingsBtn = document.getElementById('saveSettingsBtn');
const okBtn = document.getElementById('okBtn');

// Open Manual Scan Modal
startScanBtn.addEventListener('click', function() {
    manualScanModal.classList.add('show');
});

// Open Scan Settings Modal
scanSettingsBtn.addEventListener('click', function() {
    scanSettingsModal.classList.add('show');
});

// Close Modals
closeManualScanModal.addEventListener('click', function() {
    manualScanModal.classList.remove('show');
});

closeScanSettingsModal.addEventListener('click', function() {
    scanSettingsModal.classList.remove('show');
});

cancelScanBtn.addEventListener('click', function() {
    manualScanModal.classList.remove('show');
});

cancelSettingsBtn.addEventListener('click', function() {
    scanSettingsModal.classList.remove('show');
});

// Handle Scan button click
confirmScanBtn.addEventListener('click', function() {
    // Show a loading indicator
    confirmScanBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Scanning...';
    confirmScanBtn.disabled = true;
    
    // Create a form data object to send the CSRF token
    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    
    // Make AJAX call to the scanner endpoint
    fetch('{{ route("frontend.service-settings.scan", ["company_id" => $company->id, "config_id" => $configuration->id]) }}', {
        method: 'POST',
        body: formData,
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        // Hide manual scan modal
        manualScanModal.classList.remove('show');
        
        // Reset button text
        confirmScanBtn.innerHTML = 'Scan';
        confirmScanBtn.disabled = false;
        
        // Show success modal
        successScanModal.classList.add('show');
    })
    .catch(error => {
        manualScanModal.classList.remove('show');
        alert('Error scanning domain');
        confirmScanBtn.innerHTML = 'Scan';
        confirmScanBtn.disabled = false;
    });
});

// Handle success modal close button
closeSuccessModal.addEventListener('click', function() {
    successScanModal.classList.remove('show');
    window.location.reload();
});

// Handle success modal OK button
okBtn.addEventListener('click', function() {
    successScanModal.classList.remove('show');
    // Force a reload that bypasses the cache
    window.location.href = window.location.href + '?t=' + new Date().getTime();
});

// Handle settings save button
saveSettingsBtn.addEventListener('click', function() {
    // Save settings functionality here
    scanSettingsModal.classList.remove('show');
    alert('Settings saved');
});

// Close modals when clicking outside
window.addEventListener('click', function(event) {
    if (event.target == manualScanModal) {
        manualScanModal.classList.remove('show');
    }
    if (event.target == scanSettingsModal) {
        scanSettingsModal.classList.remove('show');
    }
    if (event.target == successScanModal) {
        successScanModal.classList.remove('show');
        window.location.reload();
    }
});


document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners for all Add Service buttons
    document.querySelectorAll('.btn-sm.btn-primary').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.stopPropagation(); // Prevent event bubbling
            
            // Get the scan ID from the current row
            const scanRow = this.closest('tr');
            const scanId = scanRow.getAttribute('data-scan-id');
            
            // Create a form data object to send the CSRF token
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            
            // Make AJAX call to add the service
           fetch('{{ url("/companies/{$company->id}/configurations/{$configuration->id}/scanner/add-service") }}/' + scanId, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the status in the UI
                    const statusCell = scanRow.querySelector('td:first-child span');
                    statusCell.textContent = 'Added';
                    statusCell.style.backgroundColor = '#d4edda';
                    statusCell.style.color = '#155724';
                    
                    // Update the actions
                    const actionsCell = scanRow.querySelector('td:last-child');
                    actionsCell.innerHTML = '<button class="btn btn-sm btn-light" type="button" style="border: 1px solid #dee2e6; padding: 5px 10px; font-size: 12px;">View Service</button>';
                    
                    // Show success message
                    alert('Service added successfully');
                } else {
                    alert('Error adding service: ' + data.message);
                }
            })
            .catch(error => {
                alert('Error adding service');
                console.error('Error:', error);
            });
        });
    });
});
</script>
@endsection