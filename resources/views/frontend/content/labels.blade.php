@extends('frontend.company.layout')

@section('content')
<div class="card" style="margin-bottom: 30px; width: 100%;">
    <div class="card-header" style="border-bottom: none; padding-bottom: 0;">
        <h3 class="page-title">Content <i class="" style="color: #ccc; font-size: 16px; vertical-align: middle;"></i></h3>
    </div>
    
    <!-- Add Save Settings Button in Header -->
    <div class="card-header-actions" style=" padding: 15px 20px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <span>Last saved: {{ isset($contentSettings) && $contentSettings->updated_at ? $contentSettings->updated_at->diffForHumans() : 'Never' }}</span>
        </div>
        <form id="contentSettingsForm" action="{{ route('frontend.content.save', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}" method="POST">
            @csrf
            <!-- Hidden fields for the current tab's settings -->
            <input type="hidden" name="accept_button_label" id="acceptButtonLabelInput" value="{{ $contentSettings->accept_button_label ?? 'Accept' }}">
            <input type="hidden" name="deny_button_label" id="denyButtonLabelInput" value="{{ $contentSettings->deny_button_label ?? 'Deny' }}">
            <input type="hidden" name="more_info_label" id="moreInfoLabelInput" value="{{ $contentSettings->more_info_label ?? 'More Information' }}">
            <input type="hidden" name="service_provider_label" id="serviceProviderLabelInput" value="{{ $contentSettings->service_provider_label ?? 'Service Provider' }}">
            <input type="hidden" name="privacy_policy_label" id="privacyPolicyLabelInput" value="{{ $contentSettings->privacy_policy_label ?? 'Privacy Policy' }}">
            <input type="hidden" name="legitimate_interest_label" id="legitimateInterestLabelInput" value="{{ $contentSettings->legitimate_interest_label ?? 'Legitimate Interest' }}">
            <input type="hidden" name="storage_info_label" id="storageInfoLabelInput" value="{{ $contentSettings->storage_info_label ?? 'Storage Information' }}">
            <input type="hidden" name="save_settings_label" id="saveSettingsLabelInput" value="{{ $contentSettings->save_settings_label ?? 'Save Settings' }}">
            <input type="hidden" name="accept_selected_label" id="acceptSelectedLabelInput" value="{{ $contentSettings->accept_selected_label ?? 'Accept Selected' }}">
            <input type="hidden" name="essential_category_label" id="essentialCategoryLabelInput" value="{{ $contentSettings->essential_category_label ?? 'Essential' }}">
            <input type="hidden" name="marketing_category_label" id="marketingCategoryLabelInput" value="{{ $contentSettings->marketing_category_label ?? 'Marketing' }}">
            <input type="hidden" name="functional_category_label" id="functionalCategoryLabelInput" value="{{ $contentSettings->functional_category_label ?? 'Functional' }}">
            <input type="hidden" name="analytics_category_label" id="analyticsCategoryLabelInput" value="{{ $contentSettings->analytics_category_label ?? 'Analytics' }}">
            <input type="hidden" name="active_status_label" id="activeStatusLabelInput" value="{{ $contentSettings->active_status_label ?? 'Active' }}">
            <input type="hidden" name="inactive_status_label" id="inactiveStatusLabelInput" value="{{ $contentSettings->inactive_status_label ?? 'Inactive' }}">
            <input type="hidden" name="required_status_label" id="requiredStatusLabelInput" value="{{ $contentSettings->required_status_label ?? 'Required' }}">
            
            <button type="submit" class="btn-save-settings">Save Settings</button>
        </form>
    </div>
    
    <div class="card-body" style="padding-top: 0;">
        <!-- Tabs Navigation -->
        <div style="display: flex; margin-bottom: 20px;">
            <a href="{{ route('frontend.content.first-layer', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}" 
               class="tab-link {{ $activeTab == 'first-layer' ? 'active' : '' }}" 
               style="padding: 12px 20px; border: 1px solid #dee2e6; border-bottom: none; border-radius: 4px 4px 0 0; text-decoration: none; font-weight: 800; {{ $activeTab == 'first-layer' ? 'background-color: white; color: #333; font-weight: 800;' : 'background-color: #f8f9fa; color: #666;' }} margin-right: 5px;">
                First Popup
            </a>
            <a href="{{ route('frontend.content.second-layer', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}" 
               class="tab-link {{ $activeTab == 'second-layer' ? 'active' : '' }}" 
               style="padding: 12px 20px; border: 1px solid #dee2e6; border-bottom: none; border-radius: 4px 4px 0 0; text-decoration: none; font-weight: 800; {{ $activeTab == 'second-layer' ? 'background-color: white; color: #333; font-weight: 800;' : 'background-color: #f8f9fa; color: #666;' }} margin-right: 5px;">
                Detail Popup
            </a>
            <a href="{{ route('frontend.content.labels', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}" 
               class="tab-link {{ $activeTab == 'labels' ? 'active' : '' }}" 
               style="padding: 12px 20px; border: 1px solid #dee2e6; border-bottom: none; border-radius: 4px 4px 0 0; text-decoration: none; font-weight: 800; {{ $activeTab == 'labels' ? 'background-color: white; color: #333; font-weight: 800;' : 'background-color: #f8f9fa; color: #666;' }}">
                Text Labels
            </a>
        </div>
        <div style="border-top: 1px solid #dee2e6; margin-top: -1px;"></div>

        <!-- Labels Content -->
        <div class="tab-content">
            
           
            
            <div class="content-section">
                <h4>Labels</h4>
                <p class="section-description">Define the labels for various elements in your Consent Management Platform.</p>
                
                <!-- First Layer Labels -->
                <div class="form-group">
                    <h4>First Popup Labels</h4>
                    
                    <!-- Accept Button -->
                    <div class="form-group">
                        <div class="form-header">
                            <label>Accept Button Label</label>
                            <button style="display: none;" class="btn-edit" data-field="accept-button-label" data-content="{{ $contentSettings->accept_button_label ?? 'Accept' }}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </div>
                        <input type="text" class="form-control" id="acceptButtonLabel" value="{{ $contentSettings->accept_button_label ?? 'Accept' }}">
                    </div>
                    
                    <!-- Deny Button -->
                    <div class="form-group">
                        <div class="form-header">
                            <label>Deny Button Label</label>
                            <button style="display: none;" class="btn-edit" data-field="deny-button-label" data-content="{{ $contentSettings->deny_button_label ?? 'Deny' }}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </div>
                        <input type="text" class="form-control" id="denyButtonLabel" value="{{ $contentSettings->deny_button_label ?? 'Deny' }}">
                    </div>
                    
                    <!-- More Information Link -->
                    <div class="form-group">
                        <div class="form-header">
                            <label>More Information Link Label</label>
                            <button style="display: none;" class="btn-edit" data-field="more-info-link-label" data-content="{{ $contentSettings->more_info_label ?? 'More Information' }}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </div>
                        <input type="text" class="form-control" id="moreInfoLabel" value="{{ $contentSettings->more_info_label ?? 'More Information' }}">
                    </div>
                </div>
                
                <!-- Second Layer Labels -->
                <div class="form-group">
                    <h4  >Detail Popup Labels</h4>
                    
                    <!-- Service Provider Label -->
                    <div class="form-group">
                        <div class="form-header">
                            <label>Service Provider Label</label>
                            <button style="display: none;" class="btn-edit" data-field="service-provider-label" data-content="{{ $contentSettings->service_provider_label ?? 'Service Provider' }}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </div>
                        <input type="text" class="form-control" id="serviceProviderLabel" value="{{ $contentSettings->service_provider_label ?? 'Service Provider' }}">
                    </div>
                    
                    <!-- Privacy Policy Label -->
                    <div class="form-group">
                        <div class="form-header">
                            <label>Privacy Policy Label</label>
                            <button style="display: none;" class="btn-edit" data-field="privacy-policy-label" data-content="{{ $contentSettings->privacy_policy_label ?? 'Privacy Policy' }}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </div>
                        <input type="text" class="form-control" id="privacyPolicyLabel" value="{{ $contentSettings->privacy_policy_label ?? 'Privacy Policy' }}">
                    </div>
                    
                    <!-- Legitimate Interest Label -->
                    <div class="form-group">
                        <div class="form-header">
                            <label>Legitimate Interest Label</label>
                            <button style="display: none;" class="btn-edit" data-field="legitimate-interest-label" data-content="{{ $contentSettings->legitimate_interest_label ?? 'Legitimate Interest' }}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </div>
                        <input type="text" class="form-control" id="legitimateInterestLabel" value="{{ $contentSettings->legitimate_interest_label ?? 'Legitimate Interest' }}">
                    </div>
                    
                    <!-- Storage Information Label -->
                    <div class="form-group">
                        <div class="form-header">
                            <label>Storage Information Label</label>
                            <button style="display: none;" class="btn-edit" data-field="storage-info-label" data-content="{{ $contentSettings->storage_info_label ?? 'Storage Information' }}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </div>
                        <input type="text" class="form-control" id="storageInfoLabel" value="{{ $contentSettings->storage_info_label ?? 'Storage Information' }}">
                    </div>
                </div>
                
                <!-- Button Labels -->
                <div class="form-group">
                    <h4>Button Labels</h4>
                    
                    <!-- Save Settings Button -->
                    <div class="form-group">
                        <div class="form-header">
                            <label>Save Settings Button Label</label>
                            <button style="display: none;" class="btn-edit" data-field="save-settings-label" data-content="{{ $contentSettings->save_settings_label ?? 'Save Settings' }}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </div>
                        <input type="text" class="form-control" id="saveSettingsLabel" value="{{ $contentSettings->save_settings_label ?? 'Save Settings' }}">
                    </div>
                    
                    <!-- Accept Selected Button -->
                    <div class="form-group">
                        <div class="form-header">
                            <label>Accept Selected Button Label</label>
                            <button style="display: none;" class="btn-edit" data-field="accept-selected-label" data-content="{{ $contentSettings->accept_selected_label ?? 'Accept Selected' }}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </div>
                        <input type="text" class="form-control" id="acceptSelectedLabel" value="{{ $contentSettings->accept_selected_label ?? 'Accept Selected' }}">
                    </div>
                </div>
                
                <!-- Category Labels -->
                <div class="form-group">
                    <h4>Category Labels</h4>
                    
                    <!-- Essential Category -->
                    <div class="form-group">
                        <div class="form-header">
                            <label>Essential Category Label</label>
                            <button style="display: none;" class="btn-edit" data-field="essential-category-label" data-content="{{ $contentSettings->essential_category_label ?? 'Essential' }}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </div>
                        <input type="text" class="form-control" id="essentialCategoryLabel" value="{{ $contentSettings->essential_category_label ?? 'Essential' }}">
                    </div>
                    
                    <!-- Marketing Category -->
                    <div class="form-group">
                        <div class="form-header">
                            <label>Marketing Category Label</label>
                            <button style="display: none;" class="btn-edit" data-field="marketing-category-label" data-content="{{ $contentSettings->marketing_category_label ?? 'Marketing' }}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </div>
                        <input type="text" class="form-control" id="marketingCategoryLabel" value="{{ $contentSettings->marketing_category_label ?? 'Marketing' }}">
                    </div>
                    
                    <!-- Functional Category -->
                    <div class="form-group">
                        <div class="form-header">
                            <label>Functional Category Label</label>
                            <button style="display: none;" class="btn-edit" data-field="functional-category-label" data-content="{{ $contentSettings->functional_category_label ?? 'Functional' }}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </div>
                        <input type="text" class="form-control" id="functionalCategoryLabel" value="{{ $contentSettings->functional_category_label ?? 'Functional' }}">
                    </div>
                    
                    <!-- Analytics Category -->
                    <div class="form-group">
                        <div class="form-header">
                            <label>Analytics Category Label</label>
                            <button style="display: none;" class="btn-edit" data-field="analytics-category-label" data-content="{{ $contentSettings->analytics_category_label ?? 'Analytics' }}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </div>
                        <input type="text" class="form-control" id="analyticsCategoryLabel" value="{{ $contentSettings->analytics_category_label ?? 'Analytics' }}">
                    </div>
                </div>
                
                <!-- Status Labels -->
                <div class="form-group">
                    <h4>Status Labels</h4>
                    
                    <!-- Active Status -->
                    <div class="form-group">
                        <div class="form-header">
                            <label>Active Status Label</label>
                            <button style="display: none;" class="btn-edit" data-field="active-status-label" data-content="{{ $contentSettings->active_status_label ?? 'Active' }}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </div>
                        <input type="text" class="form-control" id="activeStatusLabel" value="{{ $contentSettings->active_status_label ?? 'Active' }}">
                    </div>
                    
                    <!-- Inactive Status -->
                    <div class="form-group">
                        <div class="form-header">
                            <label>Inactive Status Label</label>
                            <button style="display: none;" class="btn-edit" data-field="inactive-status-label" data-content="{{ $contentSettings->inactive_status_label ?? 'Inactive' }}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </div>
                        <input type="text" class="form-control" id="inactiveStatusLabel" value="{{ $contentSettings->inactive_status_label ?? 'Inactive' }}">
                    </div>
                    
                    <!-- Required Status -->
                    <div class="form-group">
                        <div class="form-header">
                            <label>Required Status Label</label>
                            <button style="display: none;" class="btn-edit" data-field="required-status-label" data-content="{{ $contentSettings->required_status_label ?? 'Required' }}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </div>
                        <input type="text" class="form-control" id="requiredStatusLabel" value="{{ $contentSettings->required_status_label ?? 'Required' }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header" style="background-color: #0052cc;">
            <div style="font-size: 18px; font-weight: 500;">Edit Label</div>
            <span class="close" id="closeEditModal">&times;</span>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label>Label Text</label>
                <input type="text" id="editLabelContent" class="form-control">
            </div>
        </div>
        <div class="modal-footer" style="display: flex; justify-content: flex-end; gap: 10px;">
            <button id="cancelEditBtn" class="btn btn-light">Cancel</button>
            <button id="saveEditBtn" class="btn btn-primary">Save</button>
        </div>
    </div>
</div>

<!-- Translation Modal -->
<div id="translationModal" class="modal">
    <div class="modal-content" style="max-width: 800px;">
        <div class="modal-header" style="background-color: #0052cc;">
            <div style="font-size: 18px; font-weight: 500;">Translation</div>
            <span class="close" id="closeTranslationModal">&times;</span>
        </div>
        <div class="modal-body">
            <!-- Auto Translation Banner -->
            <div class="premium-banner" style="margin-bottom: 20px;">
                <div class="premium-banner-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <div class="premium-banner-text">
                    <h4>Auto Translation is a premium feature</h4>
                </div>
                <button type="button" class="btn-upgrade">Upgrade</button>
            </div>
            
            <!-- Default Language -->
            <div class="form-group">
                <label>Default language</label>
                <div class="default-language">English</div>
            </div>
            
            <div class="form-group">
                <div class="auto-translate-option">
                    <i class="fas fa-globe" style="color: #ccc;"></i>
                    <span class="auto-translate-text">Auto translate ALL languages</span>
                </div>
            </div>
            
            <!-- Translation Content -->
            <div class="form-group">
                <textarea id="translationContent" class="form-control" rows="6"></textarea>
            </div>
        </div>
        <div class="modal-footer" style="display: flex; justify-content: flex-end; gap: 10px;">
            <button id="discardBtn" class="btn btn-light">Discard</button>
            <button id="saveTranslationBtn" class="btn btn-primary">Save to Draft</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
    /* Content Sections */
    .content-section {
        margin-bottom: 30px;
    }
    
    .content-section h4 {
        margin-bottom: 10px;
        font-size: 18px;
        font-weight: 500;
    }
    
    .section-description {
        color: #666;
        font-size: 14px;
        margin-bottom: 20px;
    }
    
    /* Form Groups */
    .form-group {
        margin-bottom: 25px;
    }
    
    .form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    
    .form-group label {
        font-size: 14px;
        font-weight: 500;
        color: #333;
    }
    
    .form-control {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #e6e8eb;
        border-radius: 4px;
        font-size: 14px;
    }
    
    textarea.form-control {
        min-height: 100px;
        resize: vertical;
    }
    
    .btn-edit, .btn-edit-translate {
        background: none;
        border: none;
        color: #0066cc;
        display: flex;
        align-items: center;
        gap: 5px;
        cursor: pointer;
        font-size: 14px;
    }
    
    /* Premium Banner */
    .premium-banner {
        background-color: #fff8e1;
        padding: 15px 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        border-radius: 4px;
    }
    
    .premium-banner-icon {
        background-color: #ffd600;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }
    
    .premium-banner-text {
        flex: 1;
    }
    
    .premium-banner-text h4 {
        margin: 0;
        font-size: 16px;
        font-weight: 500;
    }
    
    .btn-upgrade {
        background-color: #0066cc;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 8px 16px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .btn-upgrade:hover {
        background-color: #0052a3;
    }
    
    /* Modal Styles */
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
        margin: 10% auto;
        border-radius: 6px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        overflow: hidden;
    }
    
    .modal-header {
        padding: 15px 20px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .modal-body {
        padding: 20px;
    }
    
    .modal-footer {
        padding: 15px 20px;
        border-top: 1px solid #e9ecef;
    }
    
    .close {
        color: white;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }
    
    .default-language {
        font-weight: 500;
        font-size: 16px;
        margin-bottom: 15px;
    }
    
    .auto-translate-option {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #999;
        font-size: 14px;
        margin-bottom: 15px;
        cursor: not-allowed;
    }
    
    .btn-light {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        color: #333;
        padding: 8px 16px;
        border-radius: 4px;
        cursor: pointer;
    }
    
    .btn-primary {
        background-color: #0066cc;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 4px;
        cursor: pointer;
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

<script>
    // Update hidden form fields with current values
    function updateHiddenFields() {
        // Map input IDs to hidden input IDs
        const fieldMap = {
            'acceptButtonLabel': 'acceptButtonLabelInput',
            'denyButtonLabel': 'denyButtonLabelInput',
            'moreInfoLabel': 'moreInfoLabelInput',
            'serviceProviderLabel': 'serviceProviderLabelInput',
            'privacyPolicyLabel': 'privacyPolicyLabelInput',
            'legitimateInterestLabel': 'legitimateInterestLabelInput',
            'storageInfoLabel': 'storageInfoLabelInput',
            'saveSettingsLabel': 'saveSettingsLabelInput',
            'acceptSelectedLabel': 'acceptSelectedLabelInput',
            'essentialCategoryLabel': 'essentialCategoryLabelInput',
            'marketingCategoryLabel': 'marketingCategoryLabelInput',
            'functionalCategoryLabel': 'functionalCategoryLabelInput',
            'analyticsCategoryLabel': 'analyticsCategoryLabelInput',
            'activeStatusLabel': 'activeStatusLabelInput',
            'inactiveStatusLabel': 'inactiveStatusLabelInput',
            'requiredStatusLabel': 'requiredStatusLabelInput'
        };
        
        // Update all hidden fields based on visible input values
        for (const [inputId, hiddenId] of Object.entries(fieldMap)) {
            const input = document.getElementById(inputId);
            const hiddenInput = document.getElementById(hiddenId);
            
            if (input && hiddenInput) {
                hiddenInput.value = input.value;
            }
        }
    }
    
    // Form submission
    document.getElementById('contentSettingsForm').addEventListener('submit', function(e) {
        // Update hidden fields with current values before submitting
        updateHiddenFields();
    });
    
    // Field change tracking
    document.querySelectorAll('.form-control').forEach(function(input) {
        input.addEventListener('change', function() {
            const inputId = this.id;
            const hiddenInputId = inputId + 'Input';
            const hiddenInput = document.getElementById(hiddenInputId);
            
            if (hiddenInput) {
                hiddenInput.value = this.value;
            }
        });
    });
    
    // Edit Modal
    const editModal = document.getElementById('editModal');
    const closeEditModal = document.getElementById('closeEditModal');
    const editLabelContent = document.getElementById('editLabelContent');
    const cancelEditBtn = document.getElementById('cancelEditBtn');
    const saveEditBtn = document.getElementById('saveEditBtn');
    
    // Field mapping for edit modal
    const fieldMapping = {
        'accept-button-label': 'acceptButtonLabel',
        'deny-button-label': 'denyButtonLabel',
        'more-info-link-label': 'moreInfoLabel',
        'service-provider-label': 'serviceProviderLabel',
        'privacy-policy-label': 'privacyPolicyLabel',
        'legitimate-interest-label': 'legitimateInterestLabel',
        'storage-info-label': 'storageInfoLabel',
        'save-settings-label': 'saveSettingsLabel',
        'accept-selected-label': 'acceptSelectedLabel',
        'essential-category-label': 'essentialCategoryLabel',
        'marketing-category-label': 'marketingCategoryLabel',
        'functional-category-label': 'functionalCategoryLabel',
        'analytics-category-label': 'analyticsCategoryLabel',
        'active-status-label': 'activeStatusLabel',
        'inactive-status-label': 'inactiveStatusLabel',
        'required-status-label': 'requiredStatusLabel'
    };
    
    // Open modal when Edit buttons are clicked
    document.querySelectorAll('.btn-edit').forEach(function(button) {
        button.addEventListener('click', function() {
            const fieldType = this.getAttribute('data-field');
            const content = this.getAttribute('data-content');
            
            // Set the content in the edit input
            editLabelContent.value = content;
            
            // Show the modal
            editModal.classList.add('show');
            
            // Store field info for later use when saving
            editModal.setAttribute('data-current-field', fieldType);
        });
    });
    
    // Close modal when X is clicked
    closeEditModal.addEventListener('click', function() {
        editModal.classList.remove('show');
    });
    
    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target == editModal) {
            editModal.classList.remove('show');
        }
    });
    
    // Cancel edit
    cancelEditBtn.addEventListener('click', function() {
        editModal.classList.remove('show');
    });
    
    // Save edit
    saveEditBtn.addEventListener('click', function() {
        const fieldType = editModal.getAttribute('data-current-field');
        const newContent = editLabelContent.value;
        
        // Get the corresponding input field ID
       const inputId = fieldMapping[fieldType];
       
       if (inputId) {
           // Update the visible input field
           const input = document.getElementById(inputId);
           if (input) {
               input.value = newContent;
               
               // Also update the hidden input field
               const hiddenInput = document.getElementById(inputId + 'Input');
               if (hiddenInput) {
                   hiddenInput.value = newContent;
               }
           }
           
           // Update the data-content attribute on the button for future edits
           const button = document.querySelector(`[data-field="${fieldType}"]`);
           if (button) {
               button.setAttribute('data-content', newContent);
           }
       }
       
       // Close the modal
       editModal.classList.remove('show');
   });
   
   // Translation Modal (for future use if needed)
   const translationModal = document.getElementById('translationModal');
   const closeTranslationModal = document.getElementById('closeTranslationModal');
   const translationContent = document.getElementById('translationContent');
   const discardBtn = document.getElementById('discardBtn');
   const saveTranslationBtn = document.getElementById('saveTranslationBtn');
   
   if (closeTranslationModal) {
       closeTranslationModal.addEventListener('click', function() {
           translationModal.classList.remove('show');
       });
   }
   
   if (discardBtn) {
       discardBtn.addEventListener('click', function() {
           translationModal.classList.remove('show');
       });
   }
   
   if (saveTranslationBtn) {
       saveTranslationBtn.addEventListener('click', function() {
           translationModal.classList.remove('show');
       });
   }
</script>
@endsection