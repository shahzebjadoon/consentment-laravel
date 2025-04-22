@extends('frontend.company.layout')

@section('content')
<div class="card" style="margin-bottom: 30px; width: 100%;">
    <div class="card-header" style="border-bottom: none; padding-bottom: 0;">
        <h3 class="page-title">Appearance <i class="fas fa-info-circle" style="color: #ccc; font-size: 16px; vertical-align: middle;"></i></h3>
    </div>
    
    <!-- Step 4: Add Save Settings Button -->
    <div class="card-header-actions" style=" padding: 15px 20px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <span>Last saved: {{ $appearance->updated_at ? $appearance->updated_at->diffForHumans() : 'Never' }}</span>
        </div>
        <form action="{{ route('frontend.appearance.save', ['company_id' => $company->id, 'config_id' => $configuration->id]) }}" method="POST" id="stylingForm" enctype="multipart/form-data">
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

        <!-- Styling Content -->
        <div class="tab-content">
            <div class="styling-intro">
                <h3>Styling</h3>
                <p>Customize your CMP styling to complement your corporate design and ensure high opt-in rates. You can use the preview button in the sidebar at any time to view your current configuration.</p>
            </div>
            
            <!-- Layout Style Section -->
            <div class="styling-section">
                <div class="section-header">
                    <h4>Layout Style</h4>
                    <span class="premium-feature-badge">
                        <i class="fas fa-bolt"></i> Premium Feature
                    </span>
                </div>
                <p class="section-description">Define the styling of your selected layout.</p>
                
                <!-- Colors -->
                <div class="style-group">
                    <h5>Colors</h5>
                    
                    <div class="color-inputs">
                        <div class="color-input-group">
                            <label>Background Color</label>
                            <div class="color-input">
                                <div class="color-preview" style="background-color: {{ $appearance->background_color ?? '#FFFFFF' }};"></div>
                                <input type="text" value="{{ $appearance->background_color ?? '#FFFFFF' }}" class="color-value" data-target="backgroundColorInput">
                            </div>
                        </div>
                        
                        <div class="color-input-group">
                            <label>Text Color</label>
                            <div class="color-input">
                                <div class="color-preview" style="background-color: {{ $appearance->text_color ?? '#000000' }};"></div>
                                <input type="text" value="{{ $appearance->text_color ?? '#000000' }}" class="color-value" data-target="textColorInput">
                            </div>
                        </div>
                        
                        <div class="color-input-group">
                            <label>Link Color</label>
                            <div class="color-input">
                                <div class="color-preview" style="background-color: {{ $appearance->link_color ?? '#CF7A7A' }};"></div>
                                <input type="text" value="{{ $appearance->link_color ?? '#CF7A7A' }}" class="color-value" data-target="linkColorInput">
                            </div>
                        </div>
                        
                        <div class="color-input-group">
                            <label>Tab Color</label>
                            <div class="color-input">
                                <div class="color-preview" style="background-color: {{ $appearance->tab_color ?? '#0026CB' }};"></div>
                                <input type="text" value="{{ $appearance->tab_color ?? '#0026CB' }}" class="color-value" data-target="tabColorInput">
                            </div>
                        </div>
                        
                        <div class="color-input-group">
                            <label>Accent color</label>
                            <div class="color-input">
                                <div class="color-preview" style="background-color: {{ $appearance->accent_color ?? '#D9D9D9' }};"></div>
                                <input type="text" value="{{ $appearance->accent_color ?? '#D9D9D9' }}" class="color-value" data-target="accentColorInput">
                            </div>
                        </div>
                    </div>
                    
                    <div class="border-radius-input">
                        <label>R</label>
                        <input type="text" value="{{ $appearance->border_radius ?? '8' }}" class="radius-value" data-target="borderRadiusInput">
                    </div>
                </div>
                
        <!-- Background Shadow Toggle -->
                <div class="toggle-setting">
                    <div class="toggle-header">
                        <h5>Background Shadow <i class="fas fa-info-circle"></i></h5>
                        <label class="switch">
                            <input type="checkbox" id="backgroundShadowToggle" {{ $appearance->background_shadow ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                
                <!-- Background Overlay -->
                <div class="toggle-setting">
                    <div class="toggle-header">
                        <h5>Background Overlay <i class="fas fa-info-circle"></i></h5>
                        <label class="switch">
                            <input type="checkbox" id="backgroundOverlayToggle" {{ $appearance->background_overlay ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
                    </div>
                    
                    <div class="setting-options" style="{{ $appearance->background_overlay ? 'display: flex;' : 'display: none;' }}">
                        <div class="color-input-group">
                            <label>Color</label>
                            <div class="color-input">
                                <div class="color-preview" style="background-color: {{ $appearance->overlay_color ?? '#000000' }};"></div>
                                <input type="text" value="{{ $appearance->overlay_color ?? '#000000' }}" class="color-value" data-target="overlayColorInput">
                            </div>
                        </div>
                        
                        <div class="opacity-input-group">
                            <label>Opacity (%)</label>
                            <input type="text" value="{{ $appearance->overlay_opacity ?? '70' }}" class="opacity-value" data-target="overlayOpacityInput">
                        </div>
                    </div>
                </div>
                
             
            </div>
            
            <!-- Logo Section -->
            <div class="styling-section">
                <div class="section-header">
                    <h4>Logo</h4>
                </div>
                <p class="section-description">Display your logo in the first layer.</p>
                
                <div class="form-group">
    <label>Logo Image <i class="fas fa-info-circle"></i></label>
    <div class="logo-upload-container">
        @if ($appearance->logo_url)
            <div class="current-logo">
                <img src="{{ $appearance->logo_url }}" alt="Current Logo" style="max-height: 80px; max-width: 100%; margin-bottom: 10px;">
                <div class="logo-path">{{ $appearance->logo_url }}</div>
            </div>
        @endif
        <div class="logo-upload-buttons">
            <label for="logo_file" class="btn-upload">
                <i class="fas fa-upload"></i> {{ $appearance->logo_url ? 'Change Logo' : 'Upload Logo' }}
            </label>
            @if ($appearance->logo_url)
                <button type="button" id="removeLogo" class="btn-remove">
                    <i class="fas fa-trash"></i> Remove
                </button>
            @endif
        </div>
        <input type="file" id="logo_file" name="logo_file" class="file-input" form="stylingForm" accept="image/*" style="display: none;">
        <input type="hidden" name="logo_url" value="{{ $appearance->logo_url ?? '' }}" form="stylingForm" id="logoUrlInput">
        
        <div class="logo-upload-info">
            <small>Recommended size: 200x50px. Max file size: 2MB. Supported formats: JPEG, PNG, GIF, SVG.</small>
        </div>
    </div>
</div>
                
                <div class="form-row">
                    <div class="form-group half">
                        <label>Position (First Layer)</label>
                        <div class="dropdown">
                            <button class="dropdown-toggle">
                                {{ ucfirst($appearance->logo_position ?? 'left') }} <i class="fas fa-chevron-down"></i>
                            </button>
                        </div>
                        <input type="hidden" name="logo_position" value="{{ $appearance->logo_position ?? 'left' }}" form="stylingForm">
                    </div>
                    
                    <div class="form-group half">
                        <label>Logo Alt-Tag <i class="fas fa-info-circle"></i></label>
                        <input type="text" class="form-control" placeholder="Enter the alt-tag for your logo" value="{{ $appearance->logo_alt_tag ?? '' }}" id="logoAltTagInput">
                        <input type="hidden" name="logo_alt_tag" value="{{ $appearance->logo_alt_tag ?? '' }}" form="stylingForm">
                    </div>
                </div>
                
              
            </div>
            
            <!-- Fonts Section -->
            <div class="styling-section">
                <div class="section-header">
                    <h4>Fonts</h4>
                </div>
                
                <div class="form-row">
                    <div class="form-group half">
                        <label>Font-family</label>
                        <div class="dropdown">
                            <button class="dropdown-toggle">
                                {{ $appearance->font_family ?? 'System fonts (Default)' }} <i class="fas fa-chevron-down"></i>
                            </button>
                        </div>
                        <input type="hidden" name="font_family" value="{{ $appearance->font_family ?? 'System fonts' }}" form="stylingForm">
                    </div>
                    
                    <div class="form-group half">
                        <label>Font-size</label>
                        <div class="dropdown">
                            <button class="dropdown-toggle">
                                {{ $appearance->font_size ?? 'Regular (14px - Default)' }} <i class="fas fa-chevron-down"></i>
                            </button>
                        </div>
                        <input type="hidden" name="font_size" value="{{ $appearance->font_size ?? 'Regular (14px)' }}" form="stylingForm">
                    </div>
                </div>
                
             
            </div>
            
            <!-- Buttons Section -->
            <div class="styling-section">
                <div class="section-header">
                    <h4>Buttons</h4>
                </div>
                <p class="section-description">Define the colors and styling of all buttons displayed in your Consent Management Platform.</p>
                
                <h5>Colors</h5>
                
                <div class="button-colors">
                    <div class="button-color-row">
                        <div class="color-input-group">
                            <label>Deny Button (Background)</label>
                            <div class="color-input">
                                <div class="color-preview" style="background-color: {{ $appearance->deny_button_bg ?? '#CF7A7A' }};"></div>
                                <input type="text" value="{{ $appearance->deny_button_bg ?? '#CF7A7A' }}" class="color-value" data-target="denyButtonBgInput">
                            </div>
                        </div>
                        
                        <div class="color-input-group">
                            <label>Deny Button (Text)</label>
                            <div class="color-input">
                                <div class="color-preview" style="background-color: {{ $appearance->deny_button_text ?? '#FFFFFF' }};"></div>
                                <input type="text" value="{{ $appearance->deny_button_text ?? '#FFFFFF' }}" class="color-value" data-target="denyButtonTextInput">
                            </div>
                        </div>
                    </div>
                    
                    <div class="button-color-row">
                        <div class="color-input-group">
                            <label>Save Button (Background)</label>
                            <div class="color-input">
                                <div class="color-preview" style="background-color: {{ $appearance->save_button_bg ?? '#CF7A7A' }};"></div>
                                <input type="text" value="{{ $appearance->save_button_bg ?? '#CF7A7A' }}" class="color-value" data-target="saveButtonBgInput">
                            </div>
                        </div>
                        
                        <div class="color-input-group">
                            <label>Save Button (Text)</label>
                            <div class="color-input">
                                <div class="color-preview" style="background-color: {{ $appearance->save_button_text ?? '#FFFFFF' }};"></div>
                                <input type="text" value="{{ $appearance->save_button_text ?? '#FFFFFF' }}" class="color-value" data-target="saveButtonTextInput">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="button-corners">
                    <h5>Button Corners <i class="fas fa-info-circle"></i></h5>
                    <div class="form-group">
                        <label>Rounded corners (px)</label>
                        <input type="text" class="form-control small" value="{{ $appearance->button_corner_radius ?? '4' }}" data-target="buttonCornerRadiusInput">
                    </div>
                </div>
            </div>
            
            <!-- Toggles Section -->
            <div class="styling-section">
                <div class="section-header">
                    <h4>Toggles</h4>
                </div>
                <p class="section-description">Define the colors of all consent toggles. Toggles are displayed in different states to reflect the current consent status of the integrated categories/purposes and Data Processing Services (DPS)/Vendors.</p>
                
                <div class="toggle-colors">
                    <div class="toggle-color-row">
                        <div class="color-input-group">
                            <label>Active Toggle (Background)</label>
                            <div class="color-input">
                                <div class="color-preview" style="background-color: {{ $appearance->active_toggle_bg ?? '#888888' }};"></div>
                                <input type="text" value="{{ $appearance->active_toggle_bg ?? '#888888' }}" class="color-value" id="activeToggleBgInput">
                                <input type="hidden" name="active_toggle_bg" value="{{ $appearance->active_toggle_bg ?? '#888888' }}" form="stylingForm">
                            </div>
                        </div>
                        
                        <div class="color-input-group">
                            <label>Active Toggle (Icon)</label>
                            <div class="color-input">
                                <div class="color-preview" style="background-color: {{ $appearance->active_toggle_icon ?? '#FFFFFF' }};"></div>
                                <input type="text" value="{{ $appearance->active_toggle_icon ?? '#FFFFFF' }}" class="color-value" id="activeToggleIconInput">
                                <input type="hidden" name="active_toggle_icon" value="{{ $appearance->active_toggle_icon ?? '#FFFFFF' }}" form="stylingForm">
                            </div>
                        </div>
                    </div>
                    
                    <div class="toggle-color-row">
                        <div class="color-input-group">
                            <label>Inactive Toggle (Background)</label>
                            <div class="color-input">
                                <div class="color-preview" style="background-color: {{ $appearance->inactive_toggle_bg ?? '#696A80' }};"></div>
                                <input type="text" value="{{ $appearance->inactive_toggle_bg ?? '#696A80' }}" class="color-value" id="inactiveToggleBgInput">
                                <input type="hidden" name="inactive_toggle_bg" value="{{ $appearance->inactive_toggle_bg ?? '#696A80' }}" form="stylingForm">
                            </div>
                        </div>
                        
                        <div class="color-input-group">
                            <label>Inactive Toggle (Icon)</label>
                            <div class="color-input">
                                <div class="color-preview" style="background-color: {{ $appearance->inactive_toggle_icon ?? '#CF7A7A' }};"></div>
                                <input type="text" value="{{ $appearance->inactive_toggle_icon ?? '#CF7A7A' }}" class="color-value" id="inactiveToggleIconInput">
                                <input type="hidden" name="inactive_toggle_icon" value="{{ $appearance->inactive_toggle_icon ?? '#CF7A7A' }}" form="stylingForm">
                            </div>
                        </div>
                    </div>
                    
                    <div class="toggle-color-row">
                        <div class="color-input-group">
                            <label>Disabled Toggle (Background)</label>
                            <div class="color-input">
                                <div class="color-preview" style="background-color: {{ $appearance->disabled_toggle_bg ?? '#CF7A7A' }};"></div>
                                <input type="text" value="{{ $appearance->disabled_toggle_bg ?? '#CF7A7A' }}" class="color-value" id="disabledToggleBgInput">
                                <input type="hidden" name="disabled_toggle_bg" value="{{ $appearance->disabled_toggle_bg ?? '#CF7A7A' }}" form="stylingForm">
                            </div>
                        </div>
                        
                        <div class="color-input-group">
                            <label>Disabled Toggle (Icon)</label>
                            <div class="color-input">
                                <div class="color-preview" style="background-color: {{ $appearance->disabled_toggle_icon ?? '#FFFFFF' }};"></div>
                                <input type="text" value="{{ $appearance->disabled_toggle_icon ?? '#FFFFFF' }}" class="color-value" id="disabledToggleIconInput">
                                <input type="hidden" name="disabled_toggle_icon" value="{{ $appearance->disabled_toggle_icon ?? '#FFFFFF' }}" form="stylingForm">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="toggle-preview">
                    <h5>Preview</h5>
                    <div class="preview-toggles">
                        <div class="preview-toggle">
                            <span>Active Toggle</span>
                            <div class="toggle-visual active"></div>
                        </div>
                        <div class="preview-toggle">
                            <span>Inactive Toggle</span>
                            <div class="toggle-visual inactive"></div>
                        </div>
                        <div class="preview-toggle">
                            <span>Disabled Toggle</span>
                            <div class="toggle-visual disabled"></div>
                        </div>
                    </div>
                </div>
              
            </div>
            
            <!-- Custom CSS Section -->
            <div class="styling-section">
                <div class="section-header">
                    <h4>Custom CSS</h4>
                    <span class="premium-feature-badge">
                        <i class="fas fa-bolt"></i> Premium Feature
                    </span>
                </div>
                <p class="section-description">Enter your custom CSS code to fully customize your CMP. Information on relevant CSS classes can be found in our documentation.</p>
                
                <div class="toggle-setting">
                    <div class="toggle-header">
                        <h5>Enable Custom CSS <i class="fas fa-info-circle"></i></h5>
                        <label class="switch">
                            <input type="checkbox" id="customCssEnabledToggle" {{ $appearance->custom_css_enabled ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
                        <input type="hidden" name="custom_css_enabled" value="{{ $appearance->custom_css_enabled ?? '0' }}" form="stylingForm">
                    </div>
                    <div class="setting-options" style="{{ $appearance->custom_css_enabled ? 'display: block;' : 'display: none;' }}">
                        <textarea id="customCssTextarea" class="form-control" rows="6" placeholder="Enter your custom CSS here">{{ $appearance->custom_css ?? '' }}</textarea>
                        <input type="hidden" name="custom_css" value="{{ $appearance->custom_css ?? '' }}" form="stylingForm">
                    </div>
                </div>
                
              
            </div>
            
            <!-- Privacy Button Section -->
            <div class="styling-section">
                <div class="section-header">
                    <h4>Privacy Button</h4>
                    <span class="premium-feature-badge">
                        <i class="fas fa-bolt"></i> Premium Feature
                    </span>
                </div>
                <p class="section-description">Define the styling of your Privacy Button.</p>
                
                <h5>Icon</h5>
                <div class="icon-options">
                    <div class="icon-option {{ $appearance->privacy_button_icon == 'fingerprint' || !$appearance->privacy_button_icon ? 'active' : '' }}">
                        <div class="icon-preview fingerprint">
                            <i class="fas fa-fingerprint"></i>
                        </div>
                        <span>Fingerprint</span>
                        <input type="radio" name="privacy_button_icon" value="fingerprint" {{ $appearance->privacy_button_icon == 'fingerprint' || !$appearance->privacy_button_icon ? 'checked' : '' }}>
                    </div>
                    <div class="icon-option {{ $appearance->privacy_button_icon == 'settings' ? 'active' : '' }}">
                        <div class="icon-preview settings">
                            <i class="fas fa-cog"></i>
                        </div>
                        <span>Settings</span>
                        <input type="radio" name="privacy_button_icon" value="settings" {{ $appearance->privacy_button_icon == 'settings' ? 'checked' : '' }}>
                    </div>
                    <div class="icon-option {{ $appearance->privacy_button_icon == 'security' ? 'active' : '' }}">
                        <div class="icon-preview security">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <span>Security</span>
                        <input type="radio" name="privacy_button_icon" value="security" {{ $appearance->privacy_button_icon == 'security' ? 'checked' : '' }}>
                    </div>
                    <div class="icon-option {{ $appearance->privacy_button_icon == 'custom' ? 'active' : '' }}">
                        <div class="icon-preview custom">
                            <i class="fas fa-plus"></i>
                        </div>
                        <span>Custom Icon</span>
                        <input type="radio" name="privacy_button_icon" value="custom" {{ $appearance->privacy_button_icon == 'custom' ? 'checked' : '' }}>
                    </div>
                    <input type="hidden" name="privacy_button_icon" value="{{ $appearance->privacy_button_icon ?? 'fingerprint' }}" form="stylingForm">
                </div>
                
                <div class="form-row">
                    <div class="color-input-group">
                        <label>Background</label>
                        <div class="color-input">
                            <div class="color-preview" style="background-color: {{ $appearance->privacy_button_bg ?? '#0045A5' }};"></div>
                            <input type="text" value="{{ $appearance->privacy_button_bg ?? '#0045A5' }}" class="color-value" id="privacyButtonBgInput">
                            <input type="hidden" name="privacy_button_bg" value="{{ $appearance->privacy_button_bg ?? '#0045A5' }}" form="stylingForm">
                        </div>
                    </div>
                    
                    <div class="color-input-group">
                        <label>Icon</label>
                        <div class="color-input">
                            <div class="color-preview" style="background-color: {{ $appearance->privacy_button_icon_color ?? '#FFFFFF' }};"></div>
                            <input type="text" value="{{ $appearance->privacy_button_icon_color ?? '#FFFFFF' }}" class="color-value" id="privacyButtonIconColorInput">
                            <input type="hidden" name="privacy_button_icon_color" value="{{ $appearance->privacy_button_icon_color ?? '#FFFFFF' }}" form="stylingForm">
                        </div>
                    </div>
                </div>
                
                <h5>Size</h5>
                <div class="form-row">
                    <div class="form-group half">
                        <label>Desktop</label>
                        <div class="dropdown">
                            <button class="dropdown-toggle">
                                {{ $appearance->privacy_button_desktop_size ?? 'Large - 50px' }} <i class="fas fa-chevron-down"></i>
                            </button>
                        </div>
                        <input type="hidden" name="privacy_button_desktop_size" value="{{ $appearance->privacy_button_desktop_size ?? 'Large - 50px' }}" form="stylingForm">
                    </div>
                    
                    <div class="form-group half">
                        <label>Mobile</label>
                        <div class="dropdown">
                            <button class="dropdown-toggle">
                                {{ $appearance->privacy_button_mobile_size ?? 'Small - 48px' }} <i class="fas fa-chevron-down"></i>
                            </button>
                        </div>
                        <input type="hidden" name="privacy_button_mobile_size" value="{{ $appearance->privacy_button_mobile_size ?? 'Small - 48px' }}" form="stylingForm">
                    </div>
                </div>
                
            
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>

/* Logo Upload */
.logo-upload-container {
    margin-bottom: 15px;
}

.current-logo {
    margin-bottom: 15px;
    padding: 15px;
    background-color: #f8f9fa;
    border-radius: 8px;
    text-align: left;
    border: 1px solid #e6e8eb;
}

.current-logo img {
    max-height: 80px;
    max-width: 100%;
    margin-bottom: 10px;
}

.logo-upload-buttons {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
    align-items: center;
}

.btn-upload {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    background-color: #1DA1F2; /* Twitter blue color as requested */
    color: white;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: background-color 0.2s;
}

.btn-upload:hover {
    background-color: #0c85d0; /* Slightly darker shade of the Twitter blue */
}

.btn-remove {
    display: none !important;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    background-color: #f8f9fa;
    color: #dc3545;
    border: 1px solid #e6e8eb;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-remove:hover {
    background-color: #dc3545;
    color: white;
}

.logo-upload-info {
    margin-top: 10px;
    font-size: 12px;
    color: #666;
}

.logo-path {
    display: none !important;
}

    /* Styling Sections */
    .styling-intro {
        margin-bottom: 30px;
    }
    
    .styling-intro h3 {
        margin-bottom: 10px;
    }
    
    .styling-intro p {
        color: #666;
        font-size: 14px;
        line-height: 1.5;
    }
    
    .styling-section {
        border: 1px solid #e6e8eb;
        border-radius: 8px;
        margin-bottom: 30px;
        overflow: hidden;
        padding: 20px;
    }
    
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    
    .section-header h4 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
    }
    
    .section-description {
        color: #666;
        font-size: 14px;
        margin-bottom: 20px;
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
    
    .style-group {
        margin-bottom: 20px;
    }
    
    .style-group h5 {
        margin-bottom: 15px;
        font-size: 16px;
        font-weight: 500;
    }
    
    /* Color Inputs */
    .color-inputs {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        margin-bottom: 20px;
    }
    
    .color-input-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .color-input-group label {
        font-size: 14px;
        color: #333;
    }
    
    .color-input {
        display: flex;
        align-items: center;
        gap: 10px;
        width: 100%;
    }
    
    .color-preview {
        width: 36px;
        height: 36px;
        border-radius: 4px;
        border: 1px solid #e6e8eb;
    }
    
    .color-value {
        flex: 1;
        padding: 8px 12px;
        border: 1px solid #e6e8eb;
        border-radius: 4px;
        font-size: 14px;
    }
    
    .border-radius-input {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .border-radius-input label {
        font-size: 14px;
        color: #333;
        background-color: #f8f9fa;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
    }
    
    .radius-value {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #e6e8eb;
        border-radius: 4px;
        font-size: 14px;
    }
    
    /* Toggle Settings */
    .toggle-setting {
        margin-bottom: 20px;
    }
    
    .toggle-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    
    .toggle-header h5 {
        margin: 0;
        font-size: 16px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .toggle-header h5 i {
        color: #ccc;
        font-size: 14px;
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
        background-color: #0066cc;
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
    
    .setting-options {
        display: flex;
        gap: 15px;
        margin-top: 15px;
    }
    
    .opacity-input-group {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .opacity-input-group label {
        font-size: 14px;
        color: #333;
    }
    
    .opacity-value {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #e6e8eb;
        border-radius: 4px;
        font-size: 14px;
    }
    
    /* Form Groups */
    .form-group {
        margin-bottom: 15px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-size: 14px;
        color: #333;
    }
    
    .form-control {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #e6e8eb;
        border-radius: 4px;
        font-size: 14px;
    }
    
    .form-control.small {
        width: 150px;
    }
    
    .form-row {
        display: flex;
        gap: 15px;
        margin-bottom: 15px;
    }
    
    .form-group.half {
        flex: 1;
    }
    
    .dropdown {
        position: relative;
    }
    
    .dropdown-toggle {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #e6e8eb;
        border-radius: 4px;
        background-color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 14px;
        cursor: pointer;
    }
    
    /* Button Colors */
    .button-colors, .toggle-colors {
        margin-bottom: 20px;
    }
    
    .button-color-row, .toggle-color-row {
        display: flex;
        gap: 15px;
        margin-bottom: 15px;
    }
    
    .button-corners {
        margin-bottom: 20px;
    }
    
    /* Toggle Preview */
    .toggle-preview {
        margin: 20px 0;
    }
    
    .preview-toggles {
        display: flex;
        gap: 30px;
        margin-top: 15px;
    }
    
    .preview-toggle {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .toggle-visual {
        width: 40px;
        height: 24px;
        border-radius: 12px;
        position: relative;
    }
    
    .toggle-visual.active {
        background-color: #888888;
    }
    
    .toggle-visual.inactive {
        background-color: #696A80;
    }
    
    .toggle-visual.disabled {
        background-color: #CF7A7A;
    }
    
    .toggle-visual:after {
        content: "";
        position: absolute;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background-color: white;
        top: 3px;
        left: 3px;
    }
    
    .toggle-visual.active:after {
        left: auto;
        right: 3px;
    }
    
    /* Icon Options */
    .icon-options {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }
    
    .icon-option {
        text-align: center;
        cursor: pointer;
        position: relative;
    }
    
    .icon-option input {
        position: absolute;
        opacity: 0;
    }
    
    .icon-preview {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: #0045A5;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        font-size: 20px;
    }
    
    .icon-option.active .icon-preview {
        border: 2px solid #0066cc;
    }
    
    .icon-preview.custom {
        background-color: #f8f9fa;
        border: 1px dashed #ccc;
        color: #666;
    }
    
    /* Premium Banner */
    .premium-banner {
        background-color: #fff8e1;
        padding: 15px 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        margin-top: 20px;
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

<script>
    // Color preview handling and update hidden fields
    document.querySelectorAll('.color-value').forEach(function(input) {
        const preview = input.previousElementSibling;
        const targetId = input.getAttribute('data-target');
        
        input.addEventListener('input', function() {
            // Update color preview
            preview.style.backgroundColor = this.value;
            
            // Update hidden field
            if (targetId) {
                document.getElementById(targetId).value = this.value;
            }
        });
    });
    
    // Icon option selection
    document.querySelectorAll('.icon-option').forEach(function(option) {
        option.addEventListener('click', function() {
            document.querySelectorAll('.icon-option').forEach(function(opt) {
                opt.classList.remove('active');
                opt.querySelector('input').checked = false;
            });
            
            this.classList.add('active');
            this.querySelector('input').checked = true;
            
            // Update hidden field for privacy button icon
            const iconValue = this.querySelector('input').value;
            document.querySelector('input[name="privacy_button_icon"]').value = iconValue;
        });
    });
    
    // Toggle switches and update hidden fields
    document.querySelectorAll('.switch input').forEach(function(toggle) {
        toggle.addEventListener('change', function() {
            const settingOptions = this.closest('.toggle-setting').querySelector('.setting-options');
            if (settingOptions) {
                settingOptions.style.display = this.checked ? 'flex' : 'none';
            }
            
            // Update hidden fields
            if (this.id === 'backgroundShadowToggle') {
                document.getElementById('backgroundShadowInput').value = this.checked ? '1' : '0';
            } else if (this.id === 'backgroundOverlayToggle') {
                document.getElementById('backgroundOverlayInput').value = this.checked ? '1' : '0';
            } else if (this.id === 'customCssEnabledToggle') {
                document.querySelector('input[name="custom_css_enabled"]').value = this.checked ? '1' : '0';
                const customCssOptions = this.closest('.toggle-setting').querySelector('.setting-options');
                if (customCssOptions) {
                    customCssOptions.style.display = this.checked ? 'block' : 'none';
                }
            }
        });
        
        // Initialize visibility
        const settingOptions = toggle.closest('.toggle-setting').querySelector('.setting-options');
        if (settingOptions) {
            settingOptions.style.display = toggle.checked ? 'flex' : 'none';
        }
    });
    
    // Update border radius
    document.querySelector('.radius-value').addEventListener('input', function() {
        document.getElementById('borderRadiusInput').value = this.value;
    });
    
    // Update button corner radius
    document.querySelector('.form-control.small').addEventListener('input', function() {
        document.getElementById('buttonCornerRadiusInput').value = this.value;
    });
    
    // Update overlay opacity
    document.querySelector('.opacity-value').addEventListener('input', function() {
        document.getElementById('overlayOpacityInput').value = this.value;
    });
    
    // Update logo URL
    document.getElementById('logoUrlInput').addEventListener('input', function() {
        document.querySelector('input[name="logo_url"]').value = this.value;
    });
    
    // Update logo alt tag
    document.getElementById('logoAltTagInput').addEventListener('input', function() {
        document.querySelector('input[name="logo_alt_tag"]').value = this.value;
    });
    
    // Update custom CSS
    document.getElementById('customCssTextarea').addEventListener('input', function() {
        document.querySelector('input[name="custom_css"]').value = this.value;
    });
    
    // Toggle active toggle styles
    document.getElementById('activeToggleBgInput').addEventListener('input', function() {
        document.querySelector('.toggle-visual.active').style.backgroundColor = this.value;
    });
    
    // Toggle inactive toggle styles
    document.getElementById('inactiveToggleBgInput').addEventListener('input', function() {
        document.querySelector('.toggle-visual.inactive').style.backgroundColor = this.value;
    });
    
    // Toggle disabled toggle styles
    document.getElementById('disabledToggleBgInput').addEventListener('input', function() {
        document.querySelector('.toggle-visual.disabled').style.backgroundColor = this.value;
    });
    
    
    // Logo upload preview
document.getElementById('logo_file').addEventListener('change', function(event) {
    if (this.files && this.files[0]) {
        const file = this.files[0];
        const fileSize = file.size / 1024 / 1024; // in MB
        
        // Check file size
        if (fileSize > 2) {
            alert('File size exceeds 2MB. Please select a smaller file.');
            this.value = '';
            return;
        }
        
        // If there's no current logo section, create one
        let currentLogo = document.querySelector('.current-logo');
        if (!currentLogo) {
            const logoUploadContainer = document.querySelector('.logo-upload-container');
            currentLogo = document.createElement('div');
            currentLogo.className = 'current-logo';
            
            const img = document.createElement('img');
            img.style.maxHeight = '80px';
            img.style.maxWidth = '100%';
            img.style.marginBottom = '10px';
            img.alt = 'Selected Logo';
            
            const logoPath = document.createElement('div');
            logoPath.className = 'logo-path';
            logoPath.textContent = 'New logo selected (not yet uploaded)';
            
            currentLogo.appendChild(img);
            currentLogo.appendChild(logoPath);
            
            logoUploadContainer.insertBefore(currentLogo, logoUploadContainer.firstChild);
        } else {
            const img = currentLogo.querySelector('img');
            const logoPath = currentLogo.querySelector('.logo-path');
            logoPath.textContent = 'New logo selected (not yet uploaded)';
        }
        
        // Preview the selected image
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.querySelector('.current-logo img');
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
        
        // Update upload button text
        const uploadButton = document.querySelector('.btn-upload');
        uploadButton.innerHTML = '<i class="fas fa-upload"></i> Change Logo';
        
        // Add remove button if it doesn't exist
        if (!document.getElementById('removeLogo')) {
            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.id = 'removeLogo';
            removeButton.className = 'btn-remove';
            removeButton.innerHTML = '<i class="fas fa-trash"></i> Remove';
            
            document.querySelector('.logo-upload-buttons').appendChild(removeButton);
            addRemoveLogoEvent();
        }
    }
});

// Function to add event listener to remove logo button
function addRemoveLogoEvent() {
    const removeButton = document.getElementById('removeLogo');
    if (removeButton) {
        removeButton.addEventListener('click', function() {
            // Clear the file input
            document.getElementById('logo_file').value = '';
            
            // Clear the hidden input
            document.getElementById('logoUrlInput').value = '';
            
            // Remove the current logo preview
            const currentLogo = document.querySelector('.current-logo');
            if (currentLogo) {
                currentLogo.remove();
            }
            
            // Update upload button text
            const uploadButton = document.querySelector('.btn-upload');
            uploadButton.innerHTML = '<i class="fas fa-upload"></i> Upload Logo';
            
            // Remove the remove button
            this.remove();
            
            // Add a flag to indicate logo removal
            const removeLogoInput = document.createElement('input');
            removeLogoInput.type = 'hidden';
            removeLogoInput.name = 'remove_logo';
            removeLogoInput.value = '1';
            document.getElementById('stylingForm').appendChild(removeLogoInput);
        });
    }
}

// Initialize the remove logo event
addRemoveLogoEvent();
    
</script>
@endsection