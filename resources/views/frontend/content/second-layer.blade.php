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
            <input type="hidden" name="second_layer_title" id="secondLayerTitleInput" value="{{ $contentSettings->second_layer_title ?? 'Privacy Settings' }}">
            <input type="hidden" name="second_layer_description" id="secondLayerDescriptionInput" value="{{ $contentSettings->second_layer_description ?? 'This tool helps you manage consent to third party technologies collecting and processing personal data.' }}">
            <input type="hidden" name="services_title" id="servicesTitleInput" value="{{ $contentSettings->services_title ?? 'Services' }}">
            <input type="hidden" name="services_description" id="servicesDescriptionInput" value="{{ $contentSettings->services_description ?? 'These services process personal data to display personalized or interest-based advertisements.' }}">
            <input type="hidden" name="categories_title" id="categoriesTitleInput" value="{{ $contentSettings->categories_title ?? 'Categories' }}">
            <input type="hidden" name="categories_description" id="categoriesDescriptionInput" value="{{ $contentSettings->categories_description ?? 'These categories group services by their data processing purpose.' }}">
            <input type="hidden" name="about_title" id="aboutTitleInput" value="{{ $contentSettings->about_title ?? 'About' }}">
            <input type="hidden" name="about_description" id="aboutDescriptionInput" value="{{ $contentSettings->about_description ?? 'This is about us section shows company whereabout.' }}">
            <input type="hidden" name="accept_all_button" id="acceptAllButtonInput" value="{{ $contentSettings->accept_all_button ?? 'Accept All' }}">
            <input type="hidden" name="deny_all_button" id="denyAllButtonInput" value="{{ $contentSettings->deny_all_button ?? 'Deny All' }}">
            <input type="hidden" name="save_button" id="saveButtonInput" value="{{ $contentSettings->save_button ?? 'Save' }}">
            
            <button type="submit" class="btn-save-settings">Save Settings</button>
        </form>
    </div>
    
    <div class="card-body" style="padding-top: 0;">
        <!-- Tabs Navigation -->
        <div style="display: flex; margin-bottom: 20px;">
            <a href="{{ route('frontend.content.first-layer', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}" 
               class="tab-link {{ $activeTab == 'first-layer' ? 'active' : '' }}" 
               style="padding: 12px 20px; border: 1px solid #dee2e6; border-bottom: none; border-radius: 4px 4px 0 0; text-decoration: none;font-weight: 800; {{ $activeTab == 'first-layer' ? 'background-color: white; color: #333; font-weight: 800;' : 'background-color: #f8f9fa; color: #666;' }} margin-right: 5px;">
                First Popup
            </a>
            <a href="{{ route('frontend.content.second-layer', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}" 
               class="tab-link {{ $activeTab == 'second-layer' ? 'active' : '' }}" 
               style="padding: 12px 20px; border: 1px solid #dee2e6; border-bottom: none; border-radius: 4px 4px 0 0; text-decoration: none; font-weight: 800;{{ $activeTab == 'second-layer' ? 'background-color: white; color: #333; font-weight: 800;' : 'background-color: #f8f9fa; color: #666;' }} margin-right: 5px;">
                Detail Popup
            </a>
            <a href="{{ route('frontend.content.labels', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}" 
               class="tab-link {{ $activeTab == 'labels' ? 'active' : '' }}" 
               style="padding: 12px 20px; border: 1px solid #dee2e6; border-bottom: none; border-radius: 4px 4px 0 0; text-decoration: none;font-weight: 800; {{ $activeTab == 'labels' ? 'background-color: white; color: #333; font-weight: 800;' : 'background-color: #f8f9fa; color: #666;' }}">
               Text Labels
            </a>
        </div>
        <div style="border-top: 1px solid #dee2e6; margin-top: -1px;"></div>

        <!-- Second Layer Content -->
        <div class="tab-content">
           
            
            <div class="content-section">
                <h4>Detail Popup</h4>
                <p class="section-description">Below you can enter the relevant content for the Second Layer.</p>
                
                <!-- Title -->
                <div class="form-group">
                    <div class="form-header">
                        <label>Title</label>
                        <button style="display: none;" class="btn-edit-translate" data-field="second-layer-title" data-content="{{ $contentSettings->second_layer_title ?? 'Privacy Settings' }}">
                            <i class="fas fa-edit"></i> Edit & Translate
                        </button>
                    </div>
                    <input type="text" class="form-control" id="secondLayerTitle" value="{{ $contentSettings->second_layer_title ?? 'Privacy Settings' }}">
                </div>
                
                <!-- Description -->
                <div class="form-group">
                    <div class="form-header">
                        <label>Description</label>
                        <button style="display: none;" class="btn-edit-translate" data-field="second-layer-description" data-content="{{ $contentSettings->second_layer_description ?? 'This tool helps you manage consent to third party technologies collecting and processing personal data.' }}">
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
                    <textarea class="form-control second-layer-description" id="secondLayerDescription" rows="5">{{ $contentSettings->second_layer_description ?? 'This tool helps you manage consent to third party technologies collecting and processing personal data.' }}</textarea>
                </div>
                
                <!-- Services Section -->
                <div class="form-group">
                    <h5>Services Section</h5>
                    <p class="section-description">Define labels for the Services section of the Second Layer.</p>
                    
                    <!-- Services Title -->
                    <div class="form-group">
                        <div class="form-header">
                            <label>Services Title</label>
                            <button style="display: none;" class="btn-edit-translate" data-field="services-title" data-content="{{ $contentSettings->services_title ?? 'Services' }}">
                                <i class="fas fa-edit"></i> Edit & Translate
                            </button>
                        </div>
                        <input type="text" class="form-control" id="servicesTitle" value="{{ $contentSettings->services_title ?? 'Services' }}">
                    </div>
                    
                    <!-- Services Description -->
                    <div class="form-group">
                        <div class="form-header">
                            <label>Services Description</label>
                            <button style="display: none;" class="btn-edit-translate" data-field="services-description" data-content="{{ $contentSettings->services_description ?? 'These services process personal data to display personalized or interest-based advertisements.' }}">
                                <i class="fas fa-edit"></i> Edit & Translate
                            </button>
                        </div>
                        <textarea class="form-control" id="servicesDescription" rows="3">{{ $contentSettings->services_description ?? 'These services process personal data to display personalized or interest-based advertisements.' }}</textarea>
                    </div>
                </div>
                
                <!-- Categories Section -->
                <div class="form-group">
                    <h5>Categories Section</h5>
                    <p class="section-description">Define labels for the Categories section of the Second Layer.</p>
                    
                    <!-- Categories Title -->
                    <div class="form-group">
                        <div class="form-header">
                            <label>Categories Title</label>
                            <button  style="display: none;" class="btn-edit-translate" data-field="categories-title" data-content="{{ $contentSettings->categories_title ?? 'Categories' }}">
                                <i class="fas fa-edit"></i> Edit & Translate
                            </button>
                        </div>
                        <input type="text" class="form-control" id="categoriesTitle" value="{{ $contentSettings->categories_title ?? 'Categories' }}">
                    </div>
                    
                    <!-- Categories Description -->
                    <div class="form-group">
                        <div class="form-header">
                            <label>Categories Description</label>
                            <button style="display: none;" class="btn-edit-translate" data-field="categories-description" data-content="{{ $contentSettings->categories_description ?? 'These categories group services by their data processing purpose.' }}">
                                <i class="fas fa-edit"></i> Edit & Translate
                            </button>
                        </div>
                        <textarea class="form-control" id="categoriesDescription" rows="3">{{ $contentSettings->categories_description ?? 'These categories group services by their data processing purpose.' }}</textarea>
                    </div>
                </div>
                

                 <!-- About Section -->
                 <div class="form-group">
                    <h5>About Section</h5>
                    <p class="section-description">Define labels for the About section of the Second Layer.</p>
                    
                    <!-- Categories Title -->
                    <div class="form-group">
                        <div class="form-header">
                            <label>About Title</label>
                          
                        </div>
                        <input type="text" class="form-control" id="aboutTitle" value="{{ $contentSettings->about_title ?? 'About' }}">
                    </div>
                    
                    <!-- Categories Description -->
                    <div class="form-group">
                        <div class="form-header">
                            <label>About Description</label>
                            
                        </div>
                        <textarea class="form-control" id="aboutDescription" rows="3">{{ $contentSettings->about_description ?? 'This is about us section shows company whereabout.' }}</textarea>
                    </div>
                </div>
                <!-- Button Labels -->
                <div class="form-group">
                    <h5>Button Labels</h5>
                    <p class="section-description">Define the text for buttons in the Second Layer.</p>
                    
                    <!-- Accept All Button -->
                    <div class="form-group">
                        <div class="form-header">
                            <label>Accept All Button</label>
                            <button style="display: none;" class="btn-edit-translate" data-field="accept-all-button" data-content="{{ $contentSettings->accept_all_button ?? 'Accept All' }}">
                                <i class="fas fa-edit"></i> Edit & Translate
                            </button>
                        </div>
                        <input type="text" class="form-control" id="acceptAllButton" value="{{ $contentSettings->accept_all_button ?? 'Accept All' }}">
                    </div>
                    
                    <!-- Deny All Button -->
                    <div class="form-group">
                        <div class="form-header">
                            <label>Deny All Button</label>
                            <button style="display: none;" class="btn-edit-translate" data-field="deny-all-button" data-content="{{ $contentSettings->deny_all_button ?? 'Deny All' }}">
                                <i class="fas fa-edit"></i> Edit & Translate
                            </button>
                        </div>
                        <input type="text" class="form-control" id="denyAllButton" value="{{ $contentSettings->deny_all_button ?? 'Deny All' }}">
                    </div>
                    
                    <!-- Save Button -->
                    <div class="form-group">
                        <div class="form-header">
                            <label>Save Button</label>
                            <button style="display: none;" class="btn-edit-translate" data-field="save-button" data-content="{{ $contentSettings->save_button ?? 'Save' }}">
                                <i class="fas fa-edit"></i> Edit & Translate
                            </button>
                        </div>
                        <input type="text" class="form-control" id="saveButton" value="{{ $contentSettings->save_button ?? 'Save' }}">
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
    
    .second-layer-description {
        border-top-left-radius: 0;
        border-top-right-radius: 0;
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
        document.getElementById('secondLayerTitleInput').value = document.getElementById('secondLayerTitle').value;
        document.getElementById('secondLayerDescriptionInput').value = document.getElementById('secondLayerDescription').value;
        document.getElementById('servicesTitleInput').value = document.getElementById('servicesTitle').value;
        document.getElementById('servicesDescriptionInput').value = document.getElementById('servicesDescription').value;
        document.getElementById('categoriesTitleInput').value = document.getElementById('categoriesTitle').value;
        document.getElementById('categoriesDescriptionInput').value = document.getElementById('categoriesDescription').value;
        document.getElementById('aboutTitleInput').value = document.getElementById('aboutTitle').value;
        document.getElementById('aboutDescriptionInput').value = document.getElementById('aboutDescription').value;
        document.getElementById('acceptAllButtonInput').value = document.getElementById('acceptAllButton').value;
        document.getElementById('denyAllButtonInput').value = document.getElementById('denyAllButton').value;
        document.getElementById('saveButtonInput').value = document.getElementById('saveButton').value;
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
    
    // Field change tracking
    document.querySelectorAll('.form-control').forEach(function(input) {
        input.addEventListener('change', function() {
            // Update corresponding hidden input when value changes
            if (this.id) {
                const correspondingHiddenId = this.id + 'Input';
                const hiddenInput = document.getElementById(correspondingHiddenId);
                if (hiddenInput) {
                    hiddenInput.value = this.value;
                }
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
        
        // Map field types to input ids
        const fieldMapping = {
            'second-layer-title': 'secondLayerTitle',
            'second-layer-description': 'secondLayerDescription',
            'services-title': 'servicesTitle',
            'services-description': 'servicesDescription',
            'categories-title': 'categoriesTitle',
            'categories-description': 'categoriesDescription',
            'accept-all-button': 'acceptAllButton',
            'deny-all-button': 'denyAllButton',
            'save-button': 'saveButton'
        };
        
        // Update the corresponding field
        const inputId = fieldMapping[fieldType];
        if (inputId) {
            const input = document.getElementById(inputId);
            if (input) {
                input.value = newContent;
                
                // Also update the hidden field
                const hiddenInput = document.getElementById(inputId + 'Input');
                if (hiddenInput) {
                    hiddenInput.value = newContent;
                }
            }
        }
        
        // Update the data-content attribute on the button for future edits
        document.querySelector(`[data-field="${fieldType}"]`).setAttribute('data-content', newContent);
        
        // Close the modal
        translationModal.classList.remove('show');
    });
</script>
@endsection