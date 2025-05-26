@extends('frontend.company.layout')

@section('content')
<div class="card" style="margin-bottom: 30px; width: 80%;">
    <div class="card-header" style="border-bottom: none; padding-bottom: 0;">
        <h3 class="page-title">Domain Setup <i class="" style="color: #ccc; font-size: 16px; vertical-align: middle;"></i></h3>
    </div>

        <!-- Step 4: Add Save Settings Button -->
        <div class="card-header-actions" style=" padding: 15px 20px; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <span>Last saved: {{ $configuration->updated_at ? $configuration->updated_at->diffForHumans() : 'Never' }}</span>
            </div>
            <form action="{{ route('frontend.appearance.save', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}" method="POST" id="stylingForm">
                @csrf
                <!-- Hidden fields for the current tab's settings -->
                <input type="hidden" name="background_color" value="{{ $appearance->background_color ?? '#FFFFFF' }}" id="backgroundColorInput">
                <input type="hidden" name="text_color" value="{{ $appearance->text_color ?? '#000000' }}" id="textColorInput">
                <input type="hidden" name="link_color" value="{{ $appearance->link_color ?? '#0066CC' }}" id="linkColorInput">
                <input type="hidden" name="tab_color" value="{{ $appearance->tab_color ?? '#0026CB' }}" id="tabColorInput">
                <input type="hidden" name="accent_color" value="{{ $appearance->accent_color ?? '#D9D9D9' }}" id="accentColorInput">
                <input type="hidden" name="border_radius" value="{{ $appearance->border_radius ?? '8' }}" id="borderRadiusInput">
                <input type="hidden" name="background_shadow" value="{{ $appearance->background_shadow ?? '0' }}" id="backgroundShadowInput">
                <input type="hidden" name="background_overlay" value="{{ $appearance->background_overlay ?? '1' }}" id="backgroundOverlayInput">
                <input type="hidden" name="overlay_color" value="{{ $appearance->overlay_color ?? '#000000' }}" id="overlayColorInput">
                <input type="hidden" name="overlay_opacity" value="{{ $appearance->overlay_opacity ?? '70' }}" id="overlayOpacityInput">
                <input type="hidden" name="deny_button_bg" value="{{ $appearance->deny_button_bg ?? '#CF7A7A' }}" id="denyButtonBgInput">
                <input type="hidden" name="deny_button_text" value="{{ $appearance->deny_button_text ?? '#FFFFFF' }}" id="denyButtonTextInput">
                <input type="hidden" name="save_button_bg" value="{{ $appearance->save_button_bg ?? '#CF7A7A' }}" id="saveButtonBgInput">
                <input type="hidden" name="save_button_text" value="{{ $appearance->save_button_text ?? '#FFFFFF' }}" id="saveButtonTextInput">
                <input type="hidden" name="button_corner_radius" value="{{ $appearance->button_corner_radius ?? '4' }}" id="buttonCornerRadiusInput">
                
                <button type="submit" class="btn-save-settings">Save Settings</button>
            </form>
        </div>


    <div class="card-body" style="padding-top: 0;">
        <!-- Tabs Navigation -->
        <div style="display: flex; margin-bottom: 20px;">
            <a href="{{ route('frontend.configurations.edit', ['company_id' => $company->id, 'config_id' => $configuration->id, 'tab' => 'setup']) }}" 
               class="tab-link {{ $activeTab == 'setup' ? 'active' : '' }}" 
               style="padding: 12px 20px; border: 1px solid #dee2e6; border-bottom: none; border-radius: 4px 4px 0 0; text-decoration: none; font-weight: 800; {{ $activeTab == 'setup' ? 'background-color: white; color: #333; font-weight: 800;' : 'background-color: #f8f9fa; color: #666;' }} margin-right: 5px;">
                Setup
            </a>
            <a href="{{ route('frontend.configurations.edit', ['company_id' => $company->id, 'config_id' => $configuration->id, 'tab' => 'legal']) }}" 
               class="tab-link {{ $activeTab == 'legal' ? 'active' : '' }}" 
               style="padding: 12px 20px; border: 1px solid #dee2e6; border-bottom: none; border-radius: 4px 4px 0 0; text-decoration: none; font-weight: 800; {{ $activeTab == 'legal' ? 'background-color: white; color: #333; font-weight: 800;' : 'background-color: #f8f9fa; color: #666;' }} margin-right: 5px;">
                Legal Specifications
            </a>
         
        </div>
        <div style="border-top: 1px solid #dee2e6; margin-top: -1px;"></div>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- Setup Tab -->
            @if($activeTab == 'setup')
            <div class="tab-pane active">
                <form action="{{ route('frontend.configurations.update', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!-- General Information Section -->
                    <div class="section-card" style="margin-bottom: 30px;">
                        <h4 class="section-title">General Information</h4>
                        <p class="section-description">Below you can add more details about this configuration.</p>
                        
                        <div class="form-group">
                            <label class="form-label">Configuration Name</label>
                            <div class="form-info-icon">
                                <i class="" style="color: #ccc; cursor: help;" title="The name of your configuration that will be displayed in the dashboard"></i>
                            </div>
                            <input type="text" class="form-control" name="name" value="{{ $configuration->name }}">
                        </div>
                        
                      
                    </div>
                    
                    <!-- Domain Management Section -->
                    <div class="section-card" style="margin-bottom: 30px; max-width: 100%;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <div>
                                <h4 style="white-space: nowrap;" class="section-title" style="margin-bottom: 5px;">Domain Management
                                <span class="badge" style="background-color: #e9ecef; color: #666; padding: 3px 8px; border-radius: 10px; font-size: 12px;">1</span>
                            </h4>
                            </div>
                            <button type="button" class="btn btn-primary" id="addDomainBtn">
                                Update domain
                            </button>
                        </div>
                        <p class="section-description">Update all domains that should be scanned and show the consent banner</p>
                        
                      
                        
                        <!-- Domain Table -->
<table class="table" style="margin-bottom: 0;">
    <thead>
        <tr>
            <th>Domain / Application</th>
            <th>Status</th>
            <th>Date Added</th>
            <th>Created By</th>
            {{-- <th>Actions</th> --}}
        </tr>
    </thead>
    <tbody>
        
      @php
    $domains = [];
    if ($configuration->domain) {
        $decodedDomains = json_decode($configuration->domain, true);
        if (is_array($decodedDomains)) {
            $domains = $decodedDomains;
        } else {
            $domains = [$configuration->domain];
        }
    }
@endphp

@if(count($domains) > 0)
    @foreach($domains as $domain)
    <tr>
        <td>https://www.{{ $domain }}</td>
        <td><span class="badge" style="background-color: #d4edda; color: #155724; padding: 5px 10px; border-radius: 4px;">Active</span></td>
        <td>{{ $configuration->created_at->format('d/m/Y') }}</td>
        <td>{{ auth()->user()->email }}</td>
        {{-- <td>
          
            <button type="button" class="btn btn-link text-danger delete-domain" data-domain="{{ $domain }}">
                <i class="fas fa-edit"></i>

            </button>
        </td> --}}
    </tr>
    @endforeach
@else
    <tr>
        <td colspan="5" class="text-center">No domains added yet. Click "Add domain" to add your first domain.</td>
    </tr>
@endif
    </tbody>
</table>
                    </div>
                    
                    <!-- Settings Section -->
                    <div style="display: none;" class="section-card" style="margin-bottom: 30px;">
                        <h4 class="section-title">Settings
                        <span >
                            <i class="fas fa-info-circle" style="color: #ccc; cursor: help;" title="When enabled, an error message will be displayed if your configuration is used on domains not listed above"></i>
                        </span>
                    </h4>
                        
                        <div class="form-group" style="display: flex; align-items: center;">
                            <div style="flex-grow: 1;">
                                <label class="form-label">Show error CMP when your configuration is used on unauthorized domains</label>
                              
                            </div>
                            <div class="form-switch" style="margin-top: 15px">
                                <label class="switch">
                                    <input type="checkbox" name="show_error_cmp" {{ $configuration->show_error_cmp ?? false ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                          <!-- Google Consent Mode Section -->
                    <div class="section-card" style="margin-bottom: 30px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <div style="flex-grow: 1;">
                                <h4 class="section-title">Google Consent Mode</h4>
                                <p class="section-description">If enabled, the consent mode allows you to adjust how your Google tags behave based on the consent status of your users. Make sure to implement the consent mode script including the default states on your website. If you do not want to use a TCF configuration, we recommend you use a GDPR configuration template. For more details, visit the <a href="#" style="color: #1da1f2; text-decoration: none;">official documentation</a>.</p>
                            </div>
                            <div class="form-switch">
                                <label class="switch">
                                    <input type="checkbox" name="google_consent_mode">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    
                    <!-- Language Settings Section -->
                    <div class="section-card">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                            <div>
                                <h4 class="section-title">Language Settings</h4>
                            </div>
                            {{-- <button type="button" class="btn btn-primary" id="addLanguageBtn">
                                Add Language
                            </button> --}}
                        </div>
                        <p class="section-description">Please specify which languages you want to enable for your CMP. The user can switch between these languages.</p>
                        
                        <!-- Language Table -->
                        <table class="table" style="margin-bottom: 0;">
                            <thead>
                                <tr>
                                    <th>Language</th>
                                    <th>Default</th>
                                    <th>Visible on CMP</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>English</td>
                                    <td><span class="badge" style="background-color: #d4edda; color: #155724; padding: 5px 10px; border-radius: 4px;">Default</span></td>
                                    <td>
                                        <label class="switch">
                                            <input type="checkbox" checked>
                                            <span class="slider round"></span>
                                        </label>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div style="margin-top: 30px; text-align: right;">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
            @endif

            <!-- Legal Specifications Tab -->
            @if($activeTab == 'legal')
            <div class="tab-pane active">
                <form action="{{ route('frontend.configurations.update', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    {{-- <h4 class="section-title">Legal Specifications <i class="fas fa-info-circle" style="color: #ccc; font-size: 16px; vertical-align: middle;"></i></h4> --}}
                    
                    <!-- General Data Protection Regulation (GDPR) Section -->
                    <div class="section-card" style="margin-bottom: 30px;">
                        <h4 class="section-title">General Data Protection Regulation (GDPR)</h4>
                        <p class="section-description">Below you can adjust your settings regarding the General Data Protection Regulation (GDPR).</p>
                        
                        <div class="form-group">
                            <label class="form-label">Regional Settings</label>
                            <div class="form-info-icon">
                                <i class="" style="color: #ccc; cursor: help;" title="Configure how the CMP will be displayed in different regions"></i>
                            </div>
                            <select class="form-control" name="regional_settings">
                                <option value="default">Display CMP to all users (default)</option>
                                <option value="eu_only">Display CMP to EU users only</option>
                                <option value="custom">Custom regional settings</option>
                            </select>
                        </div>
                        
                        <!-- Reshow GDPR CMP Section -->
                        <div class="form-group" style="margin-top: 25px;">
                            <h5 style="font-size: 16px; margin-bottom: 10px;">Reshow GDPR CMP</h5>
                       
                            <div class="form-switch" style="float: right; margin-top: -32px;">
                                <label class="switch">

                                    <input type="checkbox" name="google_consent_mode" >
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        
                            <p class="section-description">This reshows the CMP and refreshes the users consent choice after the selected time period in months.</p>

                            <select class="form-control" name="regional_settings">
                                <option value="7">Every 07 Days (default)</option>
                                <option value="30">Every 30 Days</option>
                                <option value="90">Every 90 Days</option>
                            </select>
                       
                        </div>
                        
                    
                    </div>
                    
                
                    
                    <!-- DPS Details Section with two columns -->
                    <div style="display: none; gap: 20px; margin-bottom: 30px;">
                        <!-- All DPS Details -->
                        <div style="flex: 1; background: white; border: 1px solid #e6e8eb; border-radius: 8px; padding: 20px;">
                            <div style="display: flex; align-items: center; margin-bottom: 15px;">
                                <input type="radio" name="dps_details_type" id="all_dps_details" value="all" style="margin-right: 10px;">
                                <label for="all_dps_details" style="margin: 0; font-weight: 600;">All DPS Details</label>
                            </div>
                            
                            <p style="color: #666; font-size: 14px; margin-bottom: 20px;">
                                If selected, all DPS information is displayed in your CMP. We recommend to review this setting with your DPO in order to be compliant with the supported legal framework.
                            </p>
                            
                            <a href="#" style="color: #1da1f2; text-decoration: none; font-size: 14px;">Show list of DPS Details</a>
                        </div>
                        
                        <!-- Limited DPS Details -->
                        <div style="flex: 1; background: white; border: 1px solid #e6e8eb; border-radius: 8px; padding: 20px;">
                            <div style="display: flex; align-items: center; margin-bottom: 15px;">
                                <input type="radio" name="dps_details_type" id="limited_dps_details" value="limited" style="margin-right: 10px;">
                                <label for="limited_dps_details" style="margin: 0; font-weight: 600;">Limited DPS Details</label>
                            </div>
                            
                            <p style="color: #666; font-size: 14px; margin-bottom: 20px;">
                                If selected, only limited DPS information is displayed in your CMP. We recommend to review this setting with your DPO in order to be compliant with the supported legal framework.
                            </p>
                            
                            <a href="#" style="color: #1da1f2; text-decoration: none; font-size: 14px;">Show list of DPS Details</a>
                        </div>
                    </div>
                    
                    <div style="margin-top: 30px; text-align: right;">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
            @endif

          
        </div>
    </div>
</div>

<!-- Update Domain Modal -->

<div id="addDomainModal" class="modal">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h3>Update Domain</h3>
            <span class="close" id="closeDomainModal">&times;</span>
        </div>
        <div class="modal-body">
            <p>Select the domain you want to update to this configuration.</p>
            
            <div class="form-group">
                <br>
                <label class="form-label" >Domain</label>
                <div style="display: flex;">
                    <div style="background-color: #e9ecef; padding: 12px 15px; border-radius: 8px 0 0 8px; border: 1px solid #ddd; border-right: none;">https://www.</div>
                    <input type="text" class="form-control" id="new_domain" placeholder="example.com"  value={{$configuration->domain}} style="border-radius: 0 8px 8px 0; font-size:20px">
                </div>
                <small class="text-muted" style="display: block; margin-top: 5px; font-size:20px">Enter domain name without https://www. prefix</small>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" id="cancelDomainBtn" style="font-size: 20px">Cancel</button>
            <button type="button" class="btn btn-primary" id="saveDomainBtn" style="font-size: 20px">Update</button>
        </div>
    </div>
</div>
<div id="successDomainModal" class="modal">
    <div class="modal-content" style="max-width: 500px;">
        <div class="modal-header" style="background-color: #28a745;">
            <div style="font-size: 18px; font-weight: 500; color: white;">Domain Added</div>
            <span class="close" id="closeSuccessModal">&times;</span>
        </div>
        <div class="modal-body">
            <p>The domain has been added successfully.</p>
        </div>
        <div class="modal-footer" style="justify-content: flex-end; display: flex; gap: 10px;">
            <button id="okDomainBtn" class="btn btn-primary" style="background-color: #28a745; color: white;">OK</button>
        </div>
    </div>
</div>
<!-- Success Domain Modal -->
<div id="successDomainModal" class="modal">
    <div class="modal-content" style="max-width: 500px;">
        <div class="modal-header" style="background-color: #28a745;">
            <div style="font-size: 18px; font-weight: 500; color: white;">Domain Added</div>
            <span class="close" id="closeSuccessModal">&times;</span>
        </div>
        <div class="modal-body">
            <p>The domain has been added successfully.</p>
        </div>
        <div class="modal-footer" style="justify-content: flex-end; display: flex; gap: 10px;">
            <button id="okDomainBtn" class="btn btn-primary" style="background-color: #28a745; color: white;">OK</button>
        </div>
    </div>
</div>

<!-- Delete Domain Confirmation Modal -->
<div id="deleteDomainModal" class="modal">
    <div class="modal-content" style="max-width: 500px;">
        <div class="modal-header" style="background-color: #dc3545;">
            <div style="font-size: 18px; font-weight: 500; color: white;">Delete Domain</div>
            <span class="close" id="closeDeleteModal">&times;</span>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete this domain?</p>
            <p><strong id="domainToDelete"></strong></p>
        </div>
        <div class="modal-footer" style="justify-content: flex-end; display: flex; gap: 10px;">
            <button id="cancelDeleteBtn" class="btn btn-secondary">Cancel</button>
            <button id="confirmDeleteBtn" class="btn btn-danger">Delete</button>
        </div>
    </div>
</div>

<style>
.section-card {
    background: white;
    border: 1px solid #e6e8eb;
    border-radius: 8px;
    padding: 20px;
}

.section-title {
    font-size: 18px;
    font-weight: 600;
    margin-top: 0;
    margin-bottom: 5px;
}

.section-description {
    color: #666;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 20px;
    position: relative;
}

.form-label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #333;
    font-size: 20px;
    font-weight: 700;
}

.form-info-icon {
    position: absolute;
    top: 0;
    right: 0;
}

.form-control {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
}

.form-switch {
    display: flex;
    align-items: center;
}

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
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .4s;
}

input:checked + .slider {
    background-color: #1da1f2;
}

input:focus + .slider {
    box-shadow: 0 0 1px #1da1f2;
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

.table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
 
}

.table th {
    text-align: left;
    padding: 12px 15px;
    border-bottom: 1px solid #e6e8eb;
    color: #666;
    font-weight: 600;
    font-size: 14px;
}

.table td {
    padding: 12px 15px;
    border-bottom: 1px solid #e6e8eb;
    vertical-align: middle;
    word-break: break-word;
}

.btn-link {
    background: none;
    border: none;
    color: #1da1f2;
    padding: 0;
    cursor: pointer;
    
}

.text-danger {
    color: #dc3545 !important;
    font-size: 20px !important;
}

.text-primary {
    color: #1da1f2 !important;
    font-size: 20px !important;

}

.modal {
    display: none;
    position: fixed;
    z-index: 1050;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.modal.show {
    opacity: 1;
}

.modal-content {
    background-color: #fefefe;
    margin: 10% auto;
    padding: 0;
    border: 1px solid #888;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 80%;
    max-width: 500px;
    transform: translateY(-20px);
    font-size: 20px;
    transition: transform 0.3s ease;
}

.modal.show .modal-content {
    transform: translateY(0);
}

.modal-header {
    padding: 15px 20px;
    border-bottom: 1px solid #e6e8eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
}

.modal-body {
    padding: 20px;
}

.modal-footer {
    padding: 15px 20px;
    border-top: 1px solid #e6e8eb;
    text-align: right;
}

.close {
    color: #ffffff;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover,
.close:focus {
    color: rgb(255, 255, 255);
    text-decoration: none;
    opacity: 1;
}


 /* Save Settings Button */
 .btn-save-settings {
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 8px 16px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .btn-save-settings:hover {
        background-color: #45a049;
    }
</style>
@endsection

@section('scripts')
<script>
// Domain Modal
const domainModal = document.getElementById('addDomainModal');
const successDomainModal = document.getElementById('successDomainModal');
const deleteDomainModal = document.getElementById('deleteDomainModal');
const addDomainBtn = document.getElementById('addDomainBtn');
const closeDomainModalBtn = document.getElementById('closeDomainModal');
const closeSuccessModal = document.getElementById('closeSuccessModal');
const closeDeleteModal = document.getElementById('closeDeleteModal');
const cancelDomainBtn = document.getElementById('cancelDomainBtn');
const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
const saveDomainBtn = document.getElementById('saveDomainBtn');
const okDomainBtn = document.getElementById('okDomainBtn');
const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
const domainToDeleteElement = document.getElementById('domainToDelete');

// Variables to store current domain being deleted
let domainToDeleteValue = '';

function openDomainModal() {
    domainModal.style.display = 'block';
    setTimeout(() => {
        domainModal.classList.add('show');
    }, 10);
}

function closeDomainModalFunc() {
    domainModal.classList.remove('show');
    setTimeout(() => {
        domainModal.style.display = 'none';
    }, 300);
    // Clear the input field when closing the modal
    document.getElementById('new_domain').value = '';
}

if (addDomainBtn) {
    addDomainBtn.addEventListener('click', openDomainModal);
}

if (closeDomainModalBtn) {
    closeDomainModalBtn.addEventListener('click', closeDomainModalFunc);
}

if (cancelDomainBtn) {
    cancelDomainBtn.addEventListener('click', closeDomainModalFunc);
}

if (closeSuccessModal) {
    closeSuccessModal.addEventListener('click', function() {
        successDomainModal.classList.remove('show');
        window.location.reload();
    });
}

if (okDomainBtn) {
    okDomainBtn.addEventListener('click', function() {
        successDomainModal.classList.remove('show');
        // Force a reload that bypasses the cache
        window.location.href = window.location.href + '?t=' + new Date().getTime();
    });
}

if (closeDeleteModal) {
    closeDeleteModal.addEventListener('click', function() {
        deleteDomainModal.classList.remove('show');
    });
}

if (cancelDeleteBtn) {
    cancelDeleteBtn.addEventListener('click', function() {
        deleteDomainModal.classList.remove('show');
    });
}

if (saveDomainBtn) {
    saveDomainBtn.addEventListener('click', function() {
        const newDomain = document.getElementById('new_domain').value.trim();
        if (newDomain) {
            // Show loading state
            saveDomainBtn.innerHTML = 'Saving...';
            saveDomainBtn.disabled = true;
            
            // Create a form data object
            const formData = new FormData();
            formData.append('domain', newDomain);
            formData.append('_token', '{{ csrf_token() }}');
            
            // Send AJAX request to add the domain
            fetch('{{ route('frontend.configurations.update-domain', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close the domain modal
                    closeDomainModalFunc();
                    
                    // Show success modal
                    successDomainModal.style.display = 'block';
                    setTimeout(() => {
                        successDomainModal.classList.add('show');
                    }, 10);
                } else {
                    alert(data.message || 'There was an error adding the domain');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('There was an error adding the domain');
            })
            .finally(() => {
                // Reset button state
                saveDomainBtn.innerHTML = 'Add';
                saveDomainBtn.disabled = false;
            });
        } else {
            alert('Please enter a valid domain');
        }
    });
}

// Delete Domain handlers
document.querySelectorAll('.delete-domain').forEach(function(btn) {
    btn.addEventListener('click', function() {
        // Get the domain value
        domainToDeleteValue = this.getAttribute('data-domain');
        
        // Set the domain in the confirmation modal
        domainToDeleteElement.textContent = domainToDeleteValue;
        
        // Show delete confirmation modal
        deleteDomainModal.style.display = 'block';
        setTimeout(() => {
            deleteDomainModal.classList.add('show');
        }, 10);
    });
});

// Confirm domain deletion
if (confirmDeleteBtn) {
    confirmDeleteBtn.addEventListener('click', function() {
        // Show loading state
        confirmDeleteBtn.innerHTML = 'Deleting...';
        confirmDeleteBtn.disabled = true;
        
        // Create a form data object
        const formData = new FormData();
        formData.append('domain', domainToDeleteValue);
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('_method', 'DELETE');
        
        // Send AJAX request to delete the domain
        fetch('{{ route('frontend.configurations.delete-domain', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Hide delete modal
                deleteDomainModal.classList.remove('show');
                
                // Force a reload that bypasses the cache
                window.location.href = window.location.href + '?t=' + new Date().getTime();
            } else {
                alert('There was an error deleting the domain');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('There was an error deleting the domain');
        })
        .finally(() => {
            // Reset button state
            confirmDeleteBtn.innerHTML = 'Delete';
            confirmDeleteBtn.disabled = false;
        });
    });
}

// Close modal when clicking outside
window.addEventListener('click', function(event) {
    if (event.target == domainModal) {
        closeDomainModalFunc();
    }
    if (event.target == successDomainModal) {
        successDomainModal.classList.remove('show');
        window.location.reload();
    }
    if (event.target == deleteDomainModal) {
        deleteDomainModal.classList.remove('show');
    }
});

</script>
@endsection