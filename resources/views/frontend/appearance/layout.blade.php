
@extends('frontend.company.layout')

@section('content')
<div class="card" style="margin-bottom: 30px; width: 100%;">
    <div class="card-header" style="border-bottom: none; padding-bottom: 0;">
        <h3 class="page-title">Appearance <i class="" style="color: #ccc; font-size: 16px; vertical-align: middle;"></i></h3>
    </div>
    
    <!-- Step 4: Add Save Settings Button -->
    <div class="card-header-actions" style=" padding: 15px 20px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <span>Last saved: {{ $appearance->updated_at ? $appearance->updated_at->diffForHumans() : 'Never' }}</span>
        </div>
        <form action="{{ route('frontend.appearance.save', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}" method="POST">
            @csrf
            <!-- Hidden fields for the current tab's settings -->
            <input type="hidden" name="layout_type" value="{{ $appearance->layout_type ?? 'wall' }}" id="layoutTypeInput">
            <input type="hidden" name="show_deny_all" value="{{ $appearance->show_deny_all ?? '1' }}" id="showDenyAllInput">
            <input type="hidden" name="more_info_type" value="{{ $appearance->more_info_type ?? 'link' }}" id="moreInfoTypeInput">
            
            <button type="submit" class="btn-save-settings">Save Settings</button>
        </form>
    </div>
    
    <div class="card-body" style="padding-top: 0;">
        <!-- Tabs Navigation -->
        <div style="display: flex; margin-bottom: 20px;">
            <a href="{{ route('frontend.appearance.layout', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}" 
               class="tab-link {{ $activeTab == 'layout' ? 'active' : '' }}" 
               style="padding: 12px 20px; border: 1px solid #dee2e6; border-bottom: none; border-radius: 4px 4px 0 0; text-decoration: none; {{ $activeTab == 'layout' ? 'background-color: white; color: #333; font-weight: 500;' : 'background-color: #f8f9fa; color: #666;' }} margin-right: 5px;">
                Layout
            </a>
            <a href="{{ route('frontend.appearance.styling', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}" 
               class="tab-link {{ $activeTab == 'styling' ? 'active' : '' }}" 
               style="padding: 12px 20px; border: 1px solid #dee2e6; border-bottom: none; border-radius: 4px 4px 0 0; text-decoration: none; {{ $activeTab == 'styling' ? 'background-color: white; color: #333; font-weight: 500;' : 'background-color: #f8f9fa; color: #666;' }} margin-right: 5px;">
                Styling
            </a>
        </div>
        <div style="border-top: 1px solid #dee2e6; margin-top: -1px;"></div>

        <!-- Layout Content -->
        <div class="tab-content">
            <!-- GDPR First Layer Section -->
            <div class="section-card">
                <div class="section-header">
                    <h3>GDPR First Layer</h3>
                    <span class="premium-feature-badge">
                        <i class="fas fa-bolt"></i> Premium Feature
                    </span>
                </div>
                <div class="section-content">
                    <p class="section-description">
                        The First Layer is displayed to the user when the website / app is accessed without any preexisting consent information and contains all information that must be disclosed to the user to obtain a valid consent.
                    </p>
                    {{$appearance->layout_type}}
                
                    
                    <div class="layout-options">
                        <div class="layout-option-group">
                            <div class="layout-option-item {{ $appearance->layout_type == 'dialog' || !$appearance->layout_type ? 'active' : '' }}">
                                <input type="radio" id="dialog" name="firstLayerLayout" value="dialog" {{ $appearance->layout_type == 'dialog' || !$appearance->layout_type ? 'checked' : '' }}>
                                <label for="dialog" class="layout-label">
                                    <div class="layout-icon " id="dialog-icon"></div>
                                    <span>Dialog</span>
                                </label>
                            </div>
                            
                            <div class="layout-option-item {{ $appearance->layout_type == 'bar' ? 'active' : '' }}">
                                <input type="radio" id="bar" name="firstLayerLayout" value="bar" {{ $appearance->layout_type == 'bar' ? 'checked' : '' }}>
                                <label for="bar" class="layout-label">
                                    <div class="layout-icon bar-icon" id="bar-icon"></div>
                                    <span>Bar</span>
                                </label>
                            </div>
                            
                            <div class="layout-option-item {{ $appearance->layout_type == 'wall' ? 'active' : '' }}">
                                <input type="radio" id="wall" name="firstLayerLayout" value="wall" {{ $appearance->layout_type == 'wall' ? 'checked' : '' }}>
                                <label for="wall" class="layout-label">
                                    <div   class="layout-icon  " id="wall-icon"></div>
                                    <span>Wall</span>
                                </label>
                            </div>
                            
                            <div class="layout-option-item {{ $appearance->layout_type == 'banner' ? 'active' : '' }}">
                                <input type="radio" id="banner" name="firstLayerLayout" value="banner" {{ $appearance->layout_type == 'banner' ? 'checked' : '' }}>
                                <label for="banner" class="layout-label">
                                    <div class="layout-icon banner-icon" id="banner-icon"></div>
                                    <span>Banner</span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="layout-preview-container">
                        
                          
                                <div class="page-wrapper">
                                  <div class="layout-container">
                                    <!-- Swap this class to try others like layout-box-dialog, etc. -->
                                    <div id= 'preveiw'  class="layout-box-common layout-box-{{$appearance->layout_type}}">
                                      {{$appearance->layout_type}} Layout
                                    </div>
                                  </div>
                                </div>
                        
                        
                        </div>
                    </div>
                    
                    <div class="settings-group">
                        <h4>Settings</h4>
                        <div class="setting-item">
                            <label>Hide language switch</label>
                        </div>
                        
                        <div class="setting-item">
                            <div class="checkbox-container">
                                <input type="checkbox" id="showDenyAll" {{ $appearance->show_deny_all ? 'checked' : '' }}>
                                <label for="showDenyAll">Show 'Deny All' Button</label>
                            </div>
                        </div>
                        
                        <div class="setting-item">
                            <div class="radio-container">
                                <input type="radio" id="moreInfoLink" name="moreInfoType" value="link" {{ $appearance->more_info_type == 'link' || !$appearance->more_info_type ? 'checked' : '' }}>
                                <label for="moreInfoLink">More Information Link</label>
                            </div>
                            
                            <div class="radio-container">
                                <input type="radio" id="moreInfoButton" name="moreInfoType" value="button" {{ $appearance->more_info_type == 'button' ? 'checked' : '' }}>
                                <label for="moreInfoButton">More Information Button</label>
                            </div>
                            
                            <div class="radio-container">
                                <input type="radio" id="moreInfoBanner" name="moreInfoType" value="banner_message" {{ $appearance->more_info_type == 'banner_message' ? 'checked' : '' }}>
                                <label for="moreInfoBanner">More Information Link in Banner Message</label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Premium Feature Banner -->
                {{-- <div class="premium-banner">
                    <div class="premium-banner-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <div class="premium-banner-text">
                        <h4>This feature is available in higher plan</h4>
                        <p>Upgrade your plan to unlock exclusive features and premium content.</p>
                    </div>
                    <button type="button" class="btn-upgrade">Upgrade</button>
                </div> --}}
            </div>
            
          
            
            <!-- Privacy Trigger Section -->
            <div class="section-card">
                <div class="section-header">
                    <h3>Privacy Trigger</h3>
                    <span class="premium-feature-badge">
                        <i class="fas fa-bolt"></i> Premium Feature
                    </span>
                </div>
                <div class="section-content">
                    <p class="section-description">
                        Enable users to access their current Privacy Settings (i.e. Second Layer). Choose between a permanently visible widget (Privacy Button) or trigger the Privacy Settings via a custom link that you can place on your website.
                    </p>
                    
                    <div class="layout-options">
                        <div class="layout-option-group">
                            <div class="layout-option-item active">
                                <input type="radio" id="privacyButton" name="privacyTrigger" checked>
                                <label for="privacyButton" class="layout-label">
                                    <div class="layout-icon privacy-button-icon"></div>
                                    <span>Privacy Button</span>
                                </label>
                            </div>
                            
                            <div class="layout-option-item">
                                <input type="radio" id="privacyLink" name="privacyTrigger">
                                <label for="privacyLink" class="layout-label">
                                    <div class="layout-icon privacy-link-icon"></div>
                                    <span>Privacy Link</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="settings-group">
                        <h4>Settings</h4>
                        
                        <div class="setting-item">
                            <div class="radio-container">
                                <input type="radio" id="positionLeft" name="buttonPosition" checked>
                                <label for="positionLeft">Position Button Left</label>
                            </div>
                            
                            <div class="radio-container">
                                <input type="radio" id="positionRight" name="buttonPosition">
                                <label for="positionRight">Position Button Right</label>
                            </div>
                        </div>
                        
                        <div class="setting-item">
                            <div class="radio-container">
                                <input type="radio" id="showAllPages" name="showOn" checked>
                                <label for="showAllPages">Show on all pages</label>
                            </div>
                            
                            <div class="radio-container">
                                <input type="radio" id="showSpecific" name="showOn">
                                <label for="showSpecific">Show on specific pages <i class=""></i></label>
                            </div>
                        </div>
                    </div>
                </div>
                
              
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
    /* Section Cards */
    .section-card {
        border: 1px solid #e6e8eb;
        border-radius: 8px;
        margin-bottom: 30px;
        overflow: hidden;
        position: relative;
    }
    
    .section-header {
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #e6e8eb;
    }
    
    .section-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
    }
    
    .premium-feature-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background-color: #f0f7ff;
        color: #0066cc;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 500;
    }
    
    .section-content {
        padding: 20px;
    }
    
    .section-description {
        color: #666;
        margin-bottom: 20px;
        font-size: 14px;
        line-height: 1.5;
    }
    
    /* Layout Options */
    .layout-options {
        display: flex;
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .layout-option-group {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    
    .layout-option-item {
        position: relative;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 15px;
        cursor: pointer;
        background-color: #fff;
        transition: all 0.2s ease;
    }
    
    .layout-option-item.active {
        border-color: #0066cc;
        background-color: #f0f7ff;
    }
    
    .layout-option-item input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }
    
    .layout-label {
        display: flex;
        align-items: center;
        gap: 15px;
        cursor: pointer;
    }
    
    .layout-icon {
        width: 40px;
        height: 40px;
        background-color: #e9ecef;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .dialog-icon {
        background-color: #0066cc;
    }

    .active-icon {
        background-color: #1da1f2;
    }
    
    
    .layout-preview {
        flex: 2;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        overflow: hidden;
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 250px;
    }
    
    /* Settings Group */
    .settings-group {
        margin-top: 20px;
    }
    
    .settings-group h4 {
        font-size: 16px;
        font-weight: 500;
        margin-bottom: 15px;
    }
    
    .setting-item {
        margin-bottom: 15px;
    }
    
    .checkbox-container, .radio-container {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
    }
    
    .checkbox-container label, .radio-container label {
        font-size: 14px;
        color: #333;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .checkbox-container input, .radio-container input {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }
    
    /* Premium Banner */
    .premium-banner {
        background-color: #fff8e1;
        padding: 15px 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        margin-top: 10px;
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
        margin: 0 0 5px 0;
        font-size: 16px;
        font-weight: 500;
    }
    
    .premium-banner-text p {
        margin: 0;
        font-size: 14px;
        color: #666;
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
<style>
    body {
      margin: 0;
      /* background-color: #e9ecef; */
      font-family: Arial, sans-serif;
    }

    .layout-preview-container {
      border: 1px solid #dee2e6;
      border-radius: 6px;
      background-color: #f8f9fa;
      overflow: hidden;
      width: 600px;
      height: 320px;
      margin: auto;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .page-wrapper {
      width: 100%;
      height: 100%;
      background-color: #ddd;
      position: relative;
    }

    .layout-container {
      width: 100%;
      height: 100%;
      position: relative;
    }

    .layout-box-common {
      background-color: #1da1f2;
      color: white;
      font-size: 18px;
      font-weight: bold;
      display: flex;
      align-items: center;
      justify-content: center;
      position: absolute;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
      border-radius: 8px;
    }

    /* Layout Types */
    .layout-box-banner {
      width: 100%;
      height: 100px;
      bottom: 0;
      left: 0;
    }

    .layout-box-dialog {
      width: 300px;
      height: 200px;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }

    .layout-box-wall {
      width: 150px;
      height: 100%;
      top: 0;
      left: 0;
    }

    .layout-box-bar {
      width: 100%;
      height: 60px;
      top: 0;
      left: 0;
    }

    .layout-box-block {
      width: 100%;
      height: 250px;
      top: 0;
      left: 0;
    }
  </style>



<script src="{{ asset('js/jquery.js') }}"></script>

<script>

$(document).ready(function() {
        // Apply 'active-icon' to initially selected radio button's icon
        $('.layout-options input[type="radio"]:checked').each(function() {
            var selectedRadio = $(this).attr('id'); // Get the id of the checked radio button
            $('#' + selectedRadio + '-icon').addClass('active-icon'); // Add the class to the icon div
        });
        // $('#preveiw').removeClass('layout-box-dialog layout-box-bar layout-box-wall layout-box-banner layout-box-block');


        $('.layout-options input[type="radio"]').on('change', function() {
            // Remove 'active-icon' class from all icons
            $('.layout-icon').removeClass('active-icon');
            $('#preveiw').removeClass('layout-box-dialog layout-box-bar layout-box-wall layout-box-banner layout-box-block');
            $('#preveiw').addClass('layout-box-' + $('#layoutTypeInput').val()); // Add the class based on the selected radio button value
            $('#preveiw').text($(this).val() + ' Layout'); // Update the text inside the preview box
          
            // Add 'active-icon' class to the icon corresponding to the selected radio button
            var selectedRadio = $(this).attr('id'); // Get the id of the selected radio button
            $('#' + selectedRadio + '-icon').addClass('active-icon'); // Add the class to the icon div
        });


    });



 
    // Toggle active layout option and update hidden field
    document.querySelectorAll('.layout-option-item').forEach(function(item) {
        const radio = item.querySelector('input[type="radio"]');
        
        radio.addEventListener('change', function() {
            // Remove active class from all items in the same group
            const name = this.getAttribute('name');
            document.querySelectorAll(`input[name="${name}"]`).forEach(function(input) {
                const parentItem = input.closest('.layout-option-item');
                parentItem.classList.remove('active');
            });
            
            // Add active class to selected item
            if (this.checked) {
                item.classList.add('active');
                
                // Update hidden input fields based on selection
                if (name === 'firstLayerLayout') {
                    document.getElementById('layoutTypeInput').value = this.value;
                }
            }
        });
    });
    
    // Update show_deny_all hidden input when checkbox changes
    document.getElementById('showDenyAll').addEventListener('change', function() {
        document.getElementById('showDenyAllInput').value = this.checked ? '1' : '0';
    });
    
    // Update more_info_type hidden input when radio changes
    document.querySelectorAll('input[name="moreInfoType"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            if (this.checked) {
                document.getElementById('moreInfoTypeInput').value = this.value;
            }
        });
    });
</script>
@endsection
