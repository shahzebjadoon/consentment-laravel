/**
 * ConsentMent CMP Dialog UI Component
 * This script renders the dialog-style consent UI
 * Version: 1.0.0
 */
(function() {
    console.log('[ConsentMent-DialogUI] Dialog UI script initializing...');
    
    // Add the renderDialogUI method to the ConsentMent object
    if (!window.ConsentMent) {
        console.error('[ConsentMent-DialogUI] ERROR: ConsentMent global object not found!');
        return;
    }
    
    // Add UI rendering function to main object
    window.ConsentMent.renderDialogUI = function(config) {
        console.log('[ConsentMent-DialogUI] Rendering dialog UI with config:', config);
        console.log('[ConsentMent-DialogUI] Appearance settings:', config.appearance);
        
        // Load custom fonts
        loadCustomFonts();
        
        // Create and append overlay
        const overlay = createOverlay(config.appearance);
        document.body.appendChild(overlay);
        console.log('[ConsentMent-DialogUI] Overlay added to DOM');
        
        // Create and append the main dialog
        const dialog = createMainDialog(config);
        document.body.appendChild(dialog);
        console.log('[ConsentMent-DialogUI] Main dialog added to DOM');
        
        // Setup event listeners
        setupEventListeners(dialog);
        console.log('[ConsentMent-DialogUI] Event listeners set up');
    };
    
    /**
     * Load custom fonts
     */
    function loadCustomFonts() {
        // Check if fonts are already loaded
        if (document.getElementById('consentment-fonts')) {
            return;
        }
        
        // Create link element for Google Roboto font
        const fontLink = document.createElement('link');
        fontLink.id = 'consentment-fonts';
        fontLink.rel = 'stylesheet';
        fontLink.href = 'https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap';
        document.head.appendChild(fontLink);
        
        console.log('[ConsentMent-DialogUI] Google Roboto font loaded');
    }
    
    /**
     * Create the background overlay
     */
    function createOverlay(appearance) {
        console.log('[ConsentMent-DialogUI] Creating overlay');
        
        const overlay = document.createElement('div');
        overlay.id = 'consentment-overlay';
        
        // Only create the overlay if background_overlay is enabled
        if (appearance.background_overlay) {
            overlay.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: ${appearance.overlay_color || '#000000'};
                opacity: ${(appearance.overlay_opacity || 70) / 100};
                z-index: 2147483646;
            `;
        } else {
            overlay.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: transparent;
                z-index: 2147483646;
            `;
        }
        
        return overlay;
    }
    
    /**
     * Create the main dialog element - centered dialog layout
     */
    function createMainDialog(config) {
        console.log('[ConsentMent-DialogUI] Creating main dialog');
        
        const appearance = config.appearance;
        const content = config.content;
        
        // Parse font size from appearance settings
        let fontSize = '14px';
        if (appearance.font_size) {
            const sizeMatch = appearance.font_size.match(/\d+px/);
            if (sizeMatch) {
                fontSize = sizeMatch[0];
            }
        }
        
        // Create wrapper
        const wrapper = document.createElement('div');
        wrapper.className = 'cmp-wrapper cmp dialog cb desktop zoom-xxs';
        
        // Create main dialog
        const dialog = document.createElement('div');
        dialog.id = 'uc-main-dialog';
        dialog.className = 'cmp dialog desktop ltr gdpr';
        dialog.setAttribute('role', 'dialog');
        dialog.setAttribute('aria-modal', 'true');
        dialog.setAttribute('aria-labelledby', 'uc-privacy-title');
        dialog.setAttribute('aria-describedby', 'uc-privacy-description');
        dialog.setAttribute('tabindex', '0');
        
        // Apply dialog-specific layout (centered in screen)
        dialog.style.cssText = `
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 480px;
            max-width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            background-color: ${appearance.background_color || '#FFFFFF'};
            color: ${appearance.text_color || '#000000'};
            ${appearance.background_shadow ? 'box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);' : ''}
            z-index: 2147483647;
            font-family: 'Roboto', ${appearance.font_family || 'Arial, sans-serif'};
            font-size: ${fontSize};
            padding: 0;
            box-sizing: border-box;
            border-radius: ${appearance.border_radius || 8}px;
        `;
        
        // Create main content container
        const mainContainer = document.createElement('div');
        mainContainer.className = 'consentment-main-container';
        mainContainer.style.cssText = `
            display: flex;
            flex-direction: column;
            width: 100%;
            margin: 0 auto;
            box-sizing: border-box;
        `;
        
       // Add header with customer and ConsentMent logos
const header = document.createElement('div');
header.className = 'dialog-header';
header.style.cssText = `
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    border-bottom: 1px solid #eee;
`;

// Customer logo (left side)
const customerLogo = document.createElement('div');
customerLogo.className = 'customer-logo';
customerLogo.style.cssText = `
    display: flex;
    align-items: center;
`;

// Add customer logo if available in config
if (config && config.appearance && config.appearance.logo_url) {
    const customerImg = document.createElement('img');
    // Prepend base URL if the logo_url is a relative path
    customerImg.src = config.appearance.logo_url.startsWith('http') ? 
        config.appearance.logo_url : 
        'https://app.consentment.com' + config.appearance.logo_url;
    customerImg.alt = 'Company Logo';
    customerImg.style.cssText = 'max-height: 40px; max-width: 150px; width: auto;';
    customerLogo.appendChild(customerImg);
    console.log('[ConsentMent-DialogUI] Added customer logo from:', customerImg.src);
}

// ConsentMent logo (right side) with link
const consentmentLogo = document.createElement('div');
consentmentLogo.className = 'consentment-logo';
consentmentLogo.style.cssText = `
    display: flex;
    align-items: center;
`;

const logoLink = document.createElement('a');
logoLink.href = 'https://consentment.com';
logoLink.target = '_blank';
logoLink.rel = 'noopener noreferrer';
logoLink.style.cssText = 'display: block; text-decoration: none;';

const consentmentImg = document.createElement('img');
consentmentImg.src = 'https://app.consentment.com/consentment-n.png';
consentmentImg.alt = 'ConsentMent';
consentmentImg.style.cssText = 'max-height: 30px; width: auto;';
logoLink.appendChild(consentmentImg);
consentmentLogo.appendChild(logoLink);

// Assemble header with both logos
header.appendChild(customerLogo);
header.appendChild(consentmentLogo);
        
        // Content section
        const contentSection = document.createElement('div');
        contentSection.className = 'consentment-content-section';
        contentSection.style.cssText = `
            padding: 20px;
            border-bottom: 1px solid #e0e0e0;
            font-family: 'Roboto', sans-serif;
        `;
        
        // Privacy Settings title
        const title = document.createElement('h2');
        title.id = 'uc-privacy-title';
        title.textContent = content.first_layer_title || 'Privacy Information';
        title.style.cssText = `
            font-size: 18px;
            font-weight: 600;
            margin: 0 0 15px 0;
            color: ${appearance.text_color || '#000000'};
            font-family: 'Roboto', sans-serif;
        `;
        contentSection.appendChild(title);
        
        // Description text
        const description = document.createElement('p');
        description.id = 'uc-privacy-description';
        description.textContent = content.first_layer_message || 
            'This site uses third-party website tracking technologies to provide and continually improve our services, and to display advertisements according to users\' interests. I agree and may revoke or change my consent at any time with effect for the future.';
        description.style.cssText = `
            font-size: ${fontSize};
            line-height: 1.5;
            margin: 0 0 15px 0;
            color: ${appearance.text_color || '#000000'};
            font-family: 'Roboto', sans-serif;
        `;
        contentSection.appendChild(description);
        
        // Create the "More Details >" link
        const moreDetailsLink = document.createElement('a');
        moreDetailsLink.id = 'uc-more-link';
        moreDetailsLink.setAttribute('role', 'button');
        moreDetailsLink.setAttribute('tabindex', '0');
        moreDetailsLink.setAttribute('data-action', 'consent');
        moreDetailsLink.setAttribute('data-action-type', 'more');
        moreDetailsLink.style.cssText = `
            text-decoration: none;
            color: ${appearance.link_color || '#1446CD'};
            font-size: ${fontSize};
            cursor: pointer;
            display: inline-block;
            margin-bottom: 10px;
            font-family: 'Roboto', sans-serif;
        `;
        
        const moreDetailsText = document.createElement('span');
        moreDetailsText.textContent = 'More Details';
        moreDetailsText.style.cssText = `
            font-family: 'Roboto', sans-serif;
        `;
        
        const moreDetailsArrow = document.createElement('span');
        moreDetailsArrow.textContent = ' ›';
        moreDetailsArrow.style.cssText = 'margin-left: 4px; font-family: "Roboto", sans-serif;';
        
        moreDetailsLink.appendChild(moreDetailsText);
        moreDetailsLink.appendChild(moreDetailsArrow);
        contentSection.appendChild(moreDetailsLink);
        
        // Toggles container - horizontal layout like in the screenshot
        const togglesContainer = document.createElement('div');
        togglesContainer.className = 'consentment-toggles-container';
        togglesContainer.style.cssText = `
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            padding: 15px 0;
            font-family: 'Roboto', sans-serif;
        `;
        
        // Service categories with toggles - using only the three shown in the screenshot
        const serviceCategories = [
            { id: 'essential', name: 'Essential', essential: true },
            { id: 'functional', name: 'Functional', essential: false },
            { id: 'marketing', name: 'Marketing', essential: false }
        ];
        
        serviceCategories.forEach(category => {
            const toggleWrapper = document.createElement('div');
            toggleWrapper.className = `consentment-toggle-wrapper ${category.id}`;
            toggleWrapper.style.cssText = `
                display: flex;
                flex-direction: column;
                align-items: center;
                width: 30%;
                text-align: center;
                font-family: 'Roboto', sans-serif;
            `;
            
            // Add label above the toggle
            const label = document.createElement('span');
            label.textContent = category.name;
            label.style.cssText = `
                font-weight: 600;
                font-size: ${fontSize};
                margin-bottom: 8px;
                font-family: 'Roboto', sans-serif;
            `;
            toggleWrapper.appendChild(label);
            
            // Create toggle that looks like in the screenshot
            const toggle = document.createElement('div');
            toggle.className = 'consentment-toggle';
            toggle.style.cssText = `
                position: relative;
                display: inline-block;
                width: 40px;
                height: 20px;
            `;
            
            const toggleInput = document.createElement('button');
            toggleInput.setAttribute('type', 'button');
            toggleInput.setAttribute('role', 'switch');
            toggleInput.setAttribute('aria-checked', category.essential ? 'true' : 'false');
            toggleInput.id = `uc-category-${category.id}-toggle`;
            toggleInput.setAttribute('data-action', 'toggle');
            toggleInput.setAttribute('data-action-type', 'categoryConsent');
            toggleInput.setAttribute('value', category.id);
            
            if (category.essential) {
                toggleInput.setAttribute('disabled', 'disabled');
                toggleInput.setAttribute('aria-disabled', 'true');
            }
            
            // Style to match the toggles in screenshot
toggleInput.style.cssText = `
    position: absolute;
    cursor: ${category.essential ? 'default' : 'pointer'};
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    width: 100%;
    height: 100%;
    background-color: ${category.essential ? 
        (appearance.disabled_toggle_bg || '#CF7A7A') : 
        (appearance.inactive_toggle_bg || '#696A80')};
    transition: .4s;
    border-radius: 15px;
    border: none;
    padding: 0;
`;
            
            // Create the toggle slider
const toggleSlider = document.createElement('span');
toggleSlider.className = 'toggle-slider';
toggleSlider.style.cssText = `
    position: absolute;
    content: "";
    height: 16px;
    width: 16px;
    left: ${category.essential ? '21px' : '2px'};
    top: 2px;
    background-color: ${category.essential ? 
        (appearance.disabled_toggle_icon || '#FFFFFF') : 
        (appearance.inactive_toggle_icon || '#FFFFFF')};
    transition: .4s;
    border-radius: 50%;
    pointer-events: none;
`;
            
            toggleInput.appendChild(toggleSlider);
            toggle.appendChild(toggleInput);
            toggleWrapper.appendChild(toggle);
            
            togglesContainer.appendChild(toggleWrapper);
        });
        
        contentSection.appendChild(togglesContainer);
        
        // Buttons section - match exact style from screenshot
        const buttonsSection = document.createElement('div');
        buttonsSection.className = 'consentment-buttons-section';
        buttonsSection.style.cssText = `
            display: flex;
            gap: 10px;
            justify-content: space-between;
            padding: 20px;
            font-family: 'Roboto', sans-serif;
        `;
        
        // Deny button
        if (appearance.show_deny_all !== false) {
            const denyButton = document.createElement('button');
            denyButton.setAttribute('data-action', 'consent');
            denyButton.setAttribute('data-action-type', 'deny');
            denyButton.id = 'deny';
            denyButton.textContent = content.deny_button_label || 'Deny';
            denyButton.style.cssText = `
                background-color: ${appearance.deny_button_bg || '#1446CD'};
                color: ${appearance.deny_button_text || '#FFFFFF'};
                border: none;
                border-radius: ${appearance.button_corner_radius || 4}px;
                padding: 10px 0;
                font-size: ${fontSize};
                font-weight: 500;
                font-family: 'Roboto', sans-serif;
                cursor: pointer;
                flex: 1;
            `;
            buttonsSection.appendChild(denyButton);
        }
        
        // Save Settings button (renamed to "Allow Selection" as in screenshot)
        const saveButton = document.createElement('button');
        saveButton.setAttribute('data-action', 'consent');
        saveButton.setAttribute('data-action-type', 'save');
        saveButton.id = 'save';
        saveButton.textContent = content.save_button || 'Allow Selection';
        saveButton.style.cssText = `
            background-color: ${appearance.save_button_bg || '#1446CD'};
            color: ${appearance.save_button_text || '#FFFFFF'};
            border: none;
            border-radius: ${appearance.button_corner_radius || 4}px;
            padding: 10px 0;
            font-size: ${fontSize};
            font-weight: 500;
            font-family: 'Roboto', sans-serif;
            cursor: pointer;
            flex: 1;
            margin: 0 5px;
        `;
        buttonsSection.appendChild(saveButton);
        
        // Accept All button
        const acceptButton = document.createElement('button');
        acceptButton.setAttribute('data-action', 'consent');
        acceptButton.setAttribute('data-action-type', 'accept');
        acceptButton.id = 'accept';
        acceptButton.textContent = content.accept_button_label || 'Accept All';
        acceptButton.style.cssText = `
            background-color: ${appearance.accept_button_bg || appearance.save_button_bg || '#1446CD'};
            color: ${appearance.accept_button_text || appearance.save_button_text || '#FFFFFF'};
            border: none;
            border-radius: ${appearance.button_corner_radius || 4}px;
            padding: 10px 0;
            font-size: ${fontSize};
            font-weight: 500;
            font-family: 'Roboto', sans-serif;
            cursor: pointer;
            flex: 1;
        `;
        buttonsSection.appendChild(acceptButton);
        
        // Assemble the layout
        mainContainer.appendChild(header);
        // Removed the tabs container here
        mainContainer.appendChild(contentSection);
        mainContainer.appendChild(buttonsSection);
        dialog.appendChild(mainContainer);
        
        // Apply custom CSS if enabled
        if (appearance.custom_css_enabled && appearance.custom_css) {
            const customStyle = document.createElement('style');
            customStyle.textContent = appearance.custom_css;
            wrapper.appendChild(customStyle);
        }
        
        // Add dialog to wrapper
        wrapper.appendChild(dialog);
        
        console.log('[ConsentMent-DialogUI] Main dialog created');
        return wrapper;
    }
    
    /**
     * Setup event listeners for the UI
     */
    function setupEventListeners(dialogContainer) {
        console.log('[ConsentMent-DialogUI] Setting up event listeners');
        
        // Accept All button
        const acceptButton = dialogContainer.querySelector('#accept');
        if (acceptButton) {
            acceptButton.addEventListener('click', function() {
                console.log('[ConsentMent-DialogUI] Accept All button clicked');
                handleAcceptAll();
            });
        }
        
        // Deny button
        const denyButton = dialogContainer.querySelector('#deny');
        if (denyButton) {
            denyButton.addEventListener('click', function() {
                console.log('[ConsentMent-DialogUI] Deny button clicked');
                handleDenyAll();
            });
        }
        
        // Save Settings button
        const saveButton = dialogContainer.querySelector('#save');
        if (saveButton) {
            saveButton.addEventListener('click', function() {
                console.log('[ConsentMent-DialogUI] Save Settings button clicked');
                handleSaveSettings();
            });
        }
        
        // Category toggles
        const toggles = dialogContainer.querySelectorAll('button[data-action="toggle"]');
        toggles.forEach(toggle => {
            if (!toggle.hasAttribute('disabled')) {
                toggle.addEventListener('click', function() {
                    const category = this.getAttribute('value');
                    const isChecked = this.getAttribute('aria-checked') === 'true';
                    const appearance = window.ConsentMent.config.appearance;
                    
                    console.log(`[ConsentMent-DialogUI] Toggle clicked for ${category}: ${!isChecked}`);
                    
                    // Toggle the state
                    this.setAttribute('aria-checked', isChecked ? 'false' : 'true');
                    
                    // Update visual state using appearance settings
if (isChecked) {
    // Switch to inactive
    this.style.backgroundColor = appearance.inactive_toggle_bg || '#696A80';
    // Move toggle slider to left
    const slider = this.querySelector('.toggle-slider');
    if (slider) {
        slider.style.left = '2px';
        slider.style.backgroundColor = appearance.inactive_toggle_icon || '#FFFFFF';
    }
} else {
    // Switch to active
    this.style.backgroundColor = appearance.active_toggle_bg || '#888888';
    // Move toggle slider to right
    const slider = this.querySelector('.toggle-slider');
    if (slider) {
        slider.style.left = '21px';
        slider.style.backgroundColor = appearance.active_toggle_icon || '#FFFFFF';
    }
}
                });
            }
        });
        
        // Details link
        const moreDetailsLink = dialogContainer.querySelector('#uc-more-link');
        if (moreDetailsLink) {
            moreDetailsLink.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('[ConsentMent-DialogUI] More Details link clicked - opening detail view');
                
                // Check if detail UI is loaded and renderDetailUI exists
                if (window.ConsentMent && typeof window.ConsentMent.renderDetailUI === 'function') {
                    // Render the detail UI if not already rendered
                    if (!document.querySelector('.cmp-wrapper.cmp.detail')) {
                        window.ConsentMent.renderDetailUI(window.ConsentMent.config);
                    } else {
                        // If already rendered, just toggle visibility
                        window.ConsentMent.toggleDetailView(true);
                    }
                } else {
                    // Fallback if detail UI is not loaded
                    console.error('[ConsentMent-DialogUI] Detail UI not available');
                    alert('Detail view is not available yet.');
                }
            });
        }
    }
    
    /**
     * Handle Accept All button click
     */
    function handleAcceptAll() {
        console.log('[ConsentMent-DialogUI] Handling Accept All');
        
        // Generate consent data for all categories
        const consentData = {
            choice: 'acceptAll',
            services: {
                'essential': true,
                'functional': true,
                'marketing': true,
                'analytics': true
            }
        };
        
        // Pass consent data to main script
        window.ConsentMent.handleConsent(consentData);
        
        // Remove UI elements
        removeConsentUI();
    }
    
    /**
     * Handle Deny All button click
     */
    function handleDenyAll() {
        console.log('[ConsentMent-DialogUI] Handling Deny All');
        
        // Generate consent data with only essential services
        const consentData = {
            choice: 'denyAll',
            services: {
                'essential': true,
                'functional': false,
                'marketing': false,
                'analytics': false
            }
        };
        
        // Pass consent data to main script
        window.ConsentMent.handleConsent(consentData);
        
        // Remove UI elements
        removeConsentUI();
    }
    
    /**
     * Handle Save Settings button click
     */
    function handleSaveSettings() {
        console.log('[ConsentMent-DialogUI] Handling Save Settings');
        
        // Get all toggle states
        const toggles = document.querySelectorAll('button[data-action="toggle"]');
        const services = {
            'essential': true // Essential is always true
        };
        
        // Build consent data from toggle states
        toggles.forEach(toggle => {
            const category = toggle.getAttribute('value');
            if (category !== 'essential') {
                const isChecked = toggle.getAttribute('aria-checked') === 'true';
                services[category] = isChecked;
            }
        });
        
        // Generate consent data
        const consentData = {
            choice: 'custom',
            services: services
        };
        
        console.log('[ConsentMent-DialogUI] Custom consent settings:', services);
        
        // Pass consent data to main script
        window.ConsentMent.handleConsent(consentData);
        
        // Remove UI elements
        removeConsentUI();
    }
    
    /**
     * Remove consent UI elements from DOM
     */
    function removeConsentUI() {
        console.log('[ConsentMent-DialogUI] Removing consent UI elements');
        
        // Remove overlay
        const overlay = document.getElementById('consentment-overlay');
        if (overlay) {
            overlay.parentNode.removeChild(overlay);
        }
        
        // Remove dialog
        const wrapper = document.querySelector('.cmp-wrapper.cmp.dialog');
        if (wrapper) {
            wrapper.parentNode.removeChild(wrapper);
        }
    }
    
    console.log('[ConsentMent-DialogUI] Dialog UI script initialized');
})();