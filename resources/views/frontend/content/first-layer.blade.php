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
            <input type="hidden" name="first_layer_title" id="firstLayerTitleInput" value="{{ $contentSettings->first_layer_title ?? 'Privacy Settings' }}">
            <input type="hidden" name="first_layer_message" id="firstLayerMessageInput" value="{{ $contentSettings->first_layer_message ?? 'This site uses third-party website tracking technologies to provide and continually improve our services, and to display advertisements according to users\' interests. I agree and may revoke or change my consent at any time with effect for the future.' }}">
            <input type="hidden" name="mobile_specific_message" id="mobileSpecificMessageInput" value="{{ $contentSettings->mobile_specific_message ?? '0' }}">
            <input type="hidden" name="mobile_message" id="mobileMessageInput" value="{{ $contentSettings->mobile_message ?? '' }}">
            <input type="hidden" name="legal_notice_text" id="legalNoticeTextInput" value="{{ $contentSettings->legal_notice_text ?? 'Legal Notice' }}">
            <input type="hidden" name="legal_notice_url" id="legalNoticeUrlInput" value="{{ $contentSettings->legal_notice_url ?? '' }}">
            <input type="hidden" name="privacy_policy_text" id="privacyPolicyTextInput" value="{{ $contentSettings->privacy_policy_text ?? 'Privacy Policy' }}">
            <input type="hidden" name="privacy_policy_url" id="privacyPolicyUrlInput" value="{{ $contentSettings->privacy_policy_url ?? '' }}">
            
            <button type="submit" class="btn-save-settings">Save Settings</button>
        </form>
    </div>
    
    <div class="card-body" style="padding-top: 0;">
        <!-- Tabs Navigation -->
        <div style="display: flex; margin-bottom: 20px;">
            <a href="{{ route('frontend.content.first-layer', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}" 
               class="tab-link {{ $activeTab == 'first-layer' ? 'active' : '' }}" 
               style="padding: 12px 20px; border: 1px solid #dee2e6; border-bottom: none; border-radius: 4px 4px 0 0; text-decoration: none; {{ $activeTab == 'first-layer' ? 'background-color: white; color: #333; font-weight: 500;' : 'background-color: #f8f9fa; color: #666;' }} margin-right: 5px;">
                First Popup
            </a>
            <a href="{{ route('frontend.content.second-layer', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}" 
               class="tab-link {{ $activeTab == 'second-layer' ? 'active' : '' }}" 
               style="padding: 12px 20px; border: 1px solid #dee2e6; border-bottom: none; border-radius: 4px 4px 0 0; text-decoration: none; {{ $activeTab == 'second-layer' ? 'background-color: white; color: #333; font-weight: 500;' : 'background-color: #f8f9fa; color: #666;' }} margin-right: 5px;">
                Detail Popup
            </a>
            <a href="{{ route('frontend.content.labels', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}" 
               class="tab-link {{ $activeTab == 'labels' ? 'active' : '' }}" 
               style="padding: 12px 20px; border: 1px solid #dee2e6; border-bottom: none; border-radius: 4px 4px 0 0; text-decoration: none; {{ $activeTab == 'labels' ? 'background-color: white; color: #333; font-weight: 500;' : 'background-color: #f8f9fa; color: #666;' }}">
                Text Labels
            </a>
        </div>
        <div style="border-top: 1px solid #dee2e6; margin-top: -1px;"></div>

        <!-- First Layer Content -->
        <div class="tab-content">
            <!-- Auto Translation Banner -->
            {{-- <div class="premium-banner" style="margin: 20px 0;">
                <div class="premium-banner-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <div class="premium-banner-text">
                    <h4>Auto Translation is a premium feature</h4>
                </div>
                <button type="button" class="btn-upgrade">Upgrade</button>
            </div> --}}
            
            <div class="content-section" style="margin-top: 5px">
                <h4>First Popup</h4>
                <p class="section-description">Below you can enter the relevant content for the First Layer.</p>
                
                <!-- Title -->
                <div class="form-group">
                    <div class="form-header">
                        <label>Title</label>
                        <button  style="display: none;" class="btn-edit-translate" data-field="title" data-content="{{ $contentSettings->first_layer_title ?? 'Privacy Settings' }}">
                            <i class="fas fa-edit"></i> Edit & Translate
                        </button>
                    </div>
                    <input type="text" class="form-control" id="titleInput" value="{{ $contentSettings->first_layer_title ?? 'Privacy Settings' }}">
                </div>
                
                <!-- Banner Message -->
                <div class="form-group">
                    <div class="form-header">
                        <label>Banner Message</label>
                        <button style="display: none;" class="btn-edit-translate" data-field="banner-message" data-content="{{ $contentSettings->first_layer_message ?? 'This site uses third-party website tracking technologies to provide and continually improve our services, and to display advertisements according to users\' interests. I agree and may revoke or change my consent at any time with effect for the future.' }}">
                            <i class="fas fa-edit"></i> Edit & Translate
                        </button>
                    </div>
                    <div class="rich-text-toolbar">
                        <button class="toolbar-btn" title="Undo"><i class="fas fa-undo"></i></button>
                        <button class="toolbar-btn" title="Bold"><i class="fas fa-bold"></i></button>
                        <button class="toolbar-btn" title="Italic"><i class="fas fa-italic"></i></button>
                        <button class="toolbar-btn" title="Underline"><i class="fas fa-underline"></i></button>
                        <div class="separator"></div>
                        <button class="toolbar-btn" title="Accept"><span class="action-text">Accept</span></button>
                        <button class="toolbar-btn" title="Deny"><span class="action-text">Deny</span></button>
                        <div class="separator"></div>
                        <button class="toolbar-btn" title="Bullet List"><i class="fas fa-list-ul"></i></button>
                        <button class="toolbar-btn" title="Numbered List"><i class="fas fa-list-ol"></i></button>
                        <button class="toolbar-btn" title="Link"><i class="fas fa-link"></i></button>
                        <button class="toolbar-btn" title="Cookie"><i class="fas fa-cookie"></i></button>
                        <button class="toolbar-btn" title="Clear Formatting"><i class="fas fa-eraser"></i></button>
                    </div>
                    <textarea class="form-control banner-message" id="bannerMessageInput" rows="5">{{ $contentSettings->first_layer_message ?? 'This site uses third-party website tracking technologies to provide and continually improve our services, and to display advertisements according to users\' interests. I agree and may revoke or change my consent at any time with effect for the future.' }}</textarea>
                    
                    <div class="mobile-options">
                        <div class="mobile-option">
                            <input type="radio" id="same-message" name="mobile-message" {{ !isset($contentSettings) || !$contentSettings->mobile_specific_message ? 'checked' : '' }}>
                            <label for="same-message">Short Banner Message for Web</label>
                        </div>
                        <div class="mobile-option">
                            <input type="radio" id="different-message" name="mobile-message" {{ isset($contentSettings) && $contentSettings->mobile_specific_message ? 'checked' : '' }}>
                            <label for="different-message">Short Banner Message for Mobile and Tablet Devices</label>
                        </div>
                    </div>
                </div>
                
                <!-- Links Section -->
                <div class="form-group">
                    <h5>Links</h5>
                    <p class="section-description">Below you can add links to your legal notice and privacy policy pages.</p>
                    
                    <!-- Legal Notice Link Text -->
                    <div class="form-group">
                        <div class="form-header">
                            <label>Legal Notice Link Text</label>
                            <button style="display: none;" class="btn-edit-translate" data-field="legal-notice-text" data-content="{{ $contentSettings->legal_notice_text ?? 'Legal Notice' }}">
                                <i class="fas fa-edit"></i> Edit & Translate
                            </button>
                        </div>
                        <input type="text" class="form-control" id="legalNoticeTextInput" value="{{ $contentSettings->legal_notice_text ?? 'Legal Notice' }}">
                    </div>
                    
                    <!-- Legal Notice URL -->
                    <div class="form-group">
                        <div class="form-header">
                            <label>Legal Notice URL</label>
                            <button style="display: none;" class="btn-edit-translate" data-field="legal-notice-url" data-content="{{ $contentSettings->legal_notice_url ?? '' }}">
                                <i class="fas fa-edit"></i> Edit & Translate
                            </button>
                        </div>
                        <input type="text" class="form-control" id="legalNoticeUrlInput" placeholder="Legal Notice URL" value="{{ $contentSettings->legal_notice_url ?? '' }}">
                    </div>
                    
                    <!-- Privacy Policy Link Text -->
                    <div class="form-group">
                        <div class="form-header">
                            <label>Privacy Policy Link Text</label>
                            <button style="display: none;" class="btn-edit-translate" data-field="privacy-policy-text" data-content="{{ $contentSettings->privacy_policy_text ?? 'Privacy Policy' }}">
                                <i class="fas fa-edit"></i> Edit & Translate
                            </button>
                        </div>
                        <input type="text" class="form-control" id="privacyPolicyTextInput" value="{{ $contentSettings->privacy_policy_text ?? 'Privacy Policy' }}">
                    </div>
                    
                    <!-- Privacy Policy URL -->
                    <div class="form-group">
                        <div class="form-header">
                            <label>Privacy Policy URL</label>
                            <button style="display: none;" class="btn-edit-translate" data-field="privacy-policy-url" data-content="{{ $contentSettings->privacy_policy_url ?? '' }}">
                                <i class="fas fa-edit"></i> Edit & Translate
                            </button>
                        </div>
                        <input type="text" class="form-control" id="privacyPolicyUrlInput" placeholder="Privacy Policy URL" value="{{ $contentSettings->privacy_policy_url ?? '' }}">
                    </div>
                </div>
            </div>
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
            {{-- <div class="premium-banner" style="margin-bottom: 20px;">
                <div class="premium-banner-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <div class="premium-banner-text">
                    <h4>Auto Translation is a premium feature</h4>
                </div>
                <button type="button" class="btn-upgrade">Upgrade</button>
            </div>
             --}}
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
    
    .btn-edit-translate {
        background: none;
        border: none;
        color: #0066cc;
        display: flex;
        align-items: center;
        gap: 5px;
        cursor: pointer;
        font-size: 14px;
    }
    
    /* Rich Text Toolbar */
    .rich-text-toolbar {
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 8px 10px;
        background-color: #f8f9fa;
        border: 1px solid #e6e8eb;
        border-bottom: none;
        border-radius: 4px 4px 0 0;
    }
    
    .toolbar-btn {
        background: none;
        border: none;
        padding: 6px 8px;
        border-radius: 4px;
        cursor: pointer;
        color: #666;
    }
    
    .toolbar-btn:hover {
        background-color: #e9ecef;
    }
    
    .separator {
        width: 1px;
        height: 24px;
        background-color: #e6e8eb;
        margin: 0 5px;
    }
    
    .action-text {
        font-size: 14px;
        font-weight: 500;
    }
    
    .banner-message {
        border-top-left-radius: 0;
        border-top-right-radius: 0;
    }
    
    /* Mobile Options */
    .mobile-options {
        display: flex;
        gap: 20px;
        margin-top: 15px;
    }
    
    .mobile-option {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .mobile-option input {
        margin: 0;
    }
    
    .mobile-option label {
        font-size: 14px;
        color: #333;
        cursor: pointer;
        font-weight: 400;
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
    
    /* Translation Modal */
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
        document.getElementById('firstLayerTitleInput').value = document.getElementById('titleInput').value;
        document.getElementById('firstLayerMessageInput').value = document.getElementById('bannerMessageInput').value;
        document.getElementById('mobileSpecificMessageInput').value = document.getElementById('different-message').checked ? '1' : '0';
        document.getElementById('legalNoticeTextInput').value = document.getElementById('legalNoticeTextInput').value;
        document.getElementById('legalNoticeUrlInput').value = document.getElementById('legalNoticeUrlInput').value;
        document.getElementById('privacyPolicyTextInput').value = document.getElementById('privacyPolicyTextInput').value;
        document.getElementById('privacyPolicyUrlInput').value = document.getElementById('privacyPolicyUrlInput').value;
    }
    
    // Form submission
    document.getElementById('contentSettingsForm').addEventListener('submit', function(e) {
        // Update hidden fields with current values before submitting
        updateHiddenFields();
    });
    
    // Rich text toolbar functionality
    document.querySelectorAll('.toolbar-btn').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            // Here you would implement rich text editing functionality
            // For this demo, we'll just add a visual feedback when buttons are clicked
            this.classList.add('active');
            setTimeout(() => {
                this.classList.remove('active');
            }, 200);
        });
    });
    
    // Mobile message option toggle
    document.querySelectorAll('input[name="mobile-message"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            // Update the hidden field
            document.getElementById('mobileSpecificMessageInput').value = document.getElementById('different-message').checked ? '1' : '0';
            
            // Here you would show/hide additional text area for mobile message if needed
        });
    });
    
    // Field change tracking
    document.querySelectorAll('.form-control').forEach(function(input) {
        input.addEventListener('change', function() {
            // Update corresponding hidden input when value changes
            if (this.id === 'titleInput') {
                document.getElementById('firstLayerTitleInput').value = this.value;
            } else if (this.id === 'bannerMessageInput') {
                document.getElementById('firstLayerMessageInput').value = this.value;
            } else if (this.id === 'legalNoticeTextInput') {
                document.getElementById('legalNoticeTextInput').value = this.value;
            } else if (this.id === 'legalNoticeUrlInput') {
                document.getElementById('legalNoticeUrlInput').value = this.value;
            } else if (this.id === 'privacyPolicyTextInput') {
                document.getElementById('privacyPolicyTextInput').value = this.value;
            } else if (this.id === 'privacyPolicyUrlInput') {
                document.getElementById('privacyPolicyUrlInput').value = this.value;
            }
        });
    });
    
    // Translation Modal
    const translationModal = document.getElementById('translationModal');
    const closeTranslationModal = document.getElementById('closeTranslationModal');
    const translationContent = document.getElementById('translationContent');
    const discardBtn = document.getElementById('discardBtn');
    const saveTranslationBtn = document.getElementById('saveTranslationBtn');
    
    // Open modal when Edit & Translate buttons are clicked
    document.querySelectorAll('.btn-edit-translate').forEach(function(button) {
        button.addEventListener('click', function() {
            const fieldType = this.getAttribute('data-field');
            const content = this.getAttribute('data-content');
            
            // Set the content in the translation textarea
            translationContent.value = content;
            
            // Show the modal
            translationModal.classList.add('show');
            
            // Store field info for later use when saving
            translationModal.setAttribute('data-current-field', fieldType);
        });
    });
    
    // Close modal when X is clicked
    closeTranslationModal.addEventListener('click', function() {
        translationModal.classList.remove('show');
    });
    
    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target == translationModal) {
            translationModal.classList.remove('show');
        }
    });
    
    // Discard changes
    discardBtn.addEventListener('click', function() {
        translationModal.classList.remove('show');
    });
    
    // Save changes
    saveTranslationBtn.addEventListener('click', function() {
        const fieldType = translationModal.getAttribute('data-current-field');
        const newContent = translationContent.value;
        
        // Update the corresponding field based on fieldType
        if (fieldType === 'title') {
            document.getElementById('titleInput').value = newContent;
            document.getElementById('firstLayerTitleInput').value = newContent;
        } else if (fieldType === 'banner-message') {
            document.getElementById('bannerMessageInput').value = newContent;
            document.getElementById('firstLayerMessageInput').value = newContent;
        } else if (fieldType === 'legal-notice-text') {
            document.getElementById('legalNoticeTextInput').value = newContent;
        } else if (fieldType === 'privacy-policy-text') {
            document.getElementById('privacyPolicyTextInput').value = newContent;
        } else if (fieldType === 'legal-notice-url') {
            document.getElementById('legalNoticeUrlInput').value = newContent;
        } else if (fieldType === 'privacy-policy-url') {
            document.getElementById('privacyPolicyUrlInput').value = newContent;
        }
        
        // Update the data-content attribute on the button for future edits
        document.querySelector(`[data-field="${fieldType}"]`).setAttribute('data-content', newContent);
        
        // Close the modal
        translationModal.classList.remove('show');
    });
</script>
@endsection