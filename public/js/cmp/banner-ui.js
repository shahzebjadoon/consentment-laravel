/**
 * ConsentMent CMP Banner UI Component
 * This script renders the banner-style consent UI
 * Version: 1.0.0
 */
(function() {
    console.log('[ConsentMent-BannerUI] Banner UI script initializing...');
    
    // Add the renderBannerUI method to the ConsentMent object
    if (!window.ConsentMent) {
        console.error('[ConsentMent-BannerUI] ERROR: ConsentMent global object not found!');
        return;
    }
    
    // Add UI rendering function to main object
    window.ConsentMent.renderBannerUI = function(config) {
        console.log('[ConsentMent-BannerUI] Rendering banner UI with config:', config);
        console.log('[ConsentMent-BannerUI] Appearance settings:', config.appearance);
        
        // Load Roboto font
        loadRobotoFont();
        
        // Create and append overlay
        const overlay = createOverlay(config.appearance);
        document.body.appendChild(overlay);
        console.log('[ConsentMent-BannerUI] Overlay added to DOM');
        
        // Create and append the main dialog
        const dialog = createMainDialog(config);
        document.body.appendChild(dialog);
        console.log('[ConsentMent-BannerUI] Main dialog added to DOM');
        
        // Setup event listeners
        setupEventListeners(dialog);
        console.log('[ConsentMent-BannerUI] Event listeners set up');
    };
    
    /**
     * Load Roboto font
     */
    function loadRobotoFont() {
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
        
        console.log('[ConsentMent-BannerUI] Google Roboto font loaded');
    }
    
    /**
     * Create the background overlay
     */
    function createOverlay(appearance) {
        console.log('[ConsentMent-BannerUI] Creating overlay');
        
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
     * Create the main dialog element - with better justified layout
     */
    function createMainDialog(config) {
        console.log('[ConsentMent-BannerUI] Creating main dialog');
        
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
        wrapper.className = 'cmp-wrapper cmp first cb bottom desktop zoom-xxs';
        
        // Create main dialog
        const dialog = document.createElement('div');
        dialog.id = 'uc-main-dialog';
        dialog.className = 'cmp first bottom desktop ltr gdpr';
        dialog.setAttribute('role', 'dialog');
        dialog.setAttribute('aria-modal', 'true');
        dialog.setAttribute('aria-labelledby', 'uc-privacy-title');
        dialog.setAttribute('aria-describedby', 'uc-privacy-description');
        dialog.setAttribute('tabindex', '0');
        
        // Removed tab-like box at the top
        
        // Apply the layout with 15px gap from left, right, and bottom
        dialog.style.cssText = `
            position: relative;
            position: fixed;
            bottom: 15px;
            left: 15px;
            right: 15px;
            width: calc(100% - 30px);
            background-color: ${appearance.background_color || '#FFFFFF'};
            color: ${appearance.text_color || '#000000'};
            ${appearance.background_shadow ? 'box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.2);' : ''}
            z-index: 2147483647;
            font-family: 'Roboto', sans-serif;
            font-size: ${fontSize};
            padding: 20px;
            box-sizing: border-box;
            border-radius: 8px;
        `;
        
        // Create main content container with better justified layout
        const mainContainer = document.createElement('div');
        mainContainer.className = 'consentment-main-container';
        mainContainer.style.cssText = `
            display: flex;
            width: 100%;
            margin: 0 auto;
            align-items: center;
            justify-content: space-between;
            box-sizing: border-box;
            min-height: 100px;
            font-family: 'Roboto', sans-serif;
        `;
        
        // Left section (logos)
const leftSection = document.createElement('div');
leftSection.className = 'consentment-left-section';
leftSection.style.cssText = `
    flex: 0 0 auto;
    margin-right: 20px;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    font-family: 'Roboto', sans-serif;
`;

// Customer logo (from database)
if (config && config.appearance && config.appearance.logo_url) {
    const customerLogo = document.createElement('img');
    // Prepend base URL if the logo_url is a relative path
    customerLogo.src = config.appearance.logo_url.startsWith('http') ? 
        config.appearance.logo_url : 
        'https://app.consentment.com' + config.appearance.logo_url;
    customerLogo.alt = 'Company Logo';
    customerLogo.style.cssText = 'max-width: 200px; max-height: 60px; display: block; margin-bottom: 77px;';
    leftSection.appendChild(customerLogo);
    console.log('[ConsentMent-BannerUI] Added customer logo from:', customerLogo.src);
}

// ConsentMent logo with link
const logoLink = document.createElement('a');
logoLink.href = 'https://consentment.com';
logoLink.target = '_blank';
logoLink.rel = 'noopener noreferrer';
logoLink.style.cssText = 'display: block; text-decoration: none;';

const logo = document.createElement('img');
logo.src = 'https://app.consentment.com/consentment-n.png';
logo.alt = 'ConsentMent Logo';
logo.style.cssText = 'width: 150px; display: block;';

logoLink.appendChild(logo);
leftSection.appendChild(logoLink);
        
        // Center section (main content) - adjusted to better use available space
        const centerSection = document.createElement('div');
        centerSection.className = 'consentment-center-section';
        centerSection.style.cssText = `
            flex: 1;
            display: flex;
            flex-direction: column;
            margin-right: 20px;
            min-width: 0;
            font-family: 'Roboto', sans-serif;
        `;
        
        // Privacy Settings title
        const title = document.createElement('h2');
        title.id = 'uc-privacy-title';
        title.textContent = content.first_layer_title || 'Privacy Settings';
        title.style.cssText = `
            font-size: 18px;
            font-weight: 600;
            margin: 0 0 8px 0;
            color: ${appearance.text_color || '#000000'};
            font-family: 'Roboto', sans-serif;
        `;
        centerSection.appendChild(title);

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
        centerSection.appendChild(description);
        
        // Toggles container - better spread
        const togglesContainer = document.createElement('div');
        togglesContainer.className = 'consentment-toggles-container';
        togglesContainer.style.cssText = `
            display: flex;
    align-items: center;
    justify-content: flex-start;
    flex-wrap: wrap;
    font-family: Roboto, sans-serif;
    margin-top: 40px;
        `;
        
        // Service categories with toggles
        const serviceCategories = [
            { id: 'marketing', name: 'Marketing', essential: false },
            { id: 'functional', name: 'Functional', essential: false },
            { id: 'essential', name: 'Essential', essential: true }
        ];
        
        serviceCategories.forEach(category => {
            const toggleWrapper = document.createElement('div');
            toggleWrapper.className = `consentment-toggle-wrapper ${category.id}`;
            toggleWrapper.style.cssText = `
                display: flex;
                align-items: center;
                margin-right: 20px;
                margin-bottom: 10px;
                font-family: 'Roboto', sans-serif;
            `;
            
            const label = document.createElement('span');
            label.textContent = category.name;
            label.style.cssText = `
                margin-right: 8px;
                font-size: ${fontSize};
                font-family: 'Roboto', sans-serif;
            `;
            
            // Create toggle that looks exactly like in the screenshot
            const toggle = document.createElement('div');
            toggle.className = 'consentment-toggle';
            toggle.style.cssText = `
                position: relative;
                display: inline-block;
                width: 40px;
                height: 20px;
                font-family: 'Roboto', sans-serif;
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
    font-family: 'Roboto', sans-serif;
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
            toggleWrapper.appendChild(label);
            toggleWrapper.appendChild(toggle);
            togglesContainer.appendChild(toggleWrapper);
        });
        
        // Details link - updated to be inline with toggles
        const detailsLink = document.createElement('a');
        detailsLink.id = 'uc-more-link';
        detailsLink.setAttribute('role', 'button');
        detailsLink.setAttribute('tabindex', '0');
        detailsLink.setAttribute('data-action', 'consent');
        detailsLink.setAttribute('data-action-type', 'more');
        detailsLink.style.cssText = `
            text-decoration: none;
            color: ${appearance.link_color || '#D98A8A'};
            font-size: ${fontSize};
            cursor: pointer;
            display: flex;
            align-items: center;
            margin-right: 20px;
            margin-bottom: 10px;
            font-family: 'Roboto', sans-serif;
        `;
        
        const detailsText = document.createElement('span');
        detailsText.textContent = 'Details';
        detailsText.style.cssText = `
            font-family: 'Roboto', sans-serif;
        `;
        
        const detailsArrow = document.createElement('span');
        detailsArrow.textContent = ' ›';
        detailsArrow.style.cssText = 'margin-left: 4px; font-family: "Roboto", sans-serif;';
        
        detailsLink.appendChild(detailsText);
        detailsLink.appendChild(detailsArrow);
        togglesContainer.appendChild(detailsLink);
        
        centerSection.appendChild(togglesContainer);
        
        // Right section (buttons) - width adjusted to better fit the layout
        const rightSection = document.createElement('div');
        rightSection.className = 'consentment-right-section';
        rightSection.style.cssText = `
            flex: 0 0 auto;
            width: 180px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            font-family: 'Roboto', sans-serif;
        `;
        
        // Save Settings button
        const saveButton = document.createElement('button');
        saveButton.setAttribute('data-action', 'consent');
        saveButton.setAttribute('data-action-type', 'save');
        saveButton.id = 'save';
        saveButton.textContent = content.save_button || 'Save Settings';
        saveButton.style.cssText = `
            background-color: ${appearance.save_button_bg || '#D98A8A'};
            color: ${appearance.save_button_text || '#FFFFFF'};
            border: none;
            border-radius: ${appearance.button_corner_radius || 4}px;
            padding: 12px;
            font-size: ${fontSize};
            font-weight: 500;
            cursor: pointer;
            width: 100%;
            text-align: center;
            font-family: 'Roboto', sans-serif;
        `;
        
        // Deny button
        const denyButton = document.createElement('button');
        denyButton.setAttribute('data-action', 'consent');
        denyButton.setAttribute('data-action-type', 'deny');
        denyButton.id = 'deny';
        denyButton.textContent = content.deny_button_label || 'Deny';
        denyButton.style.cssText = `
            background-color: ${appearance.deny_button_bg || '#D98A8A'};
            color: ${appearance.deny_button_text || '#FFFFFF'};
            border: none;
            border-radius: ${appearance.button_corner_radius || 4}px;
            padding: 12px;
            font-size: ${fontSize};
            font-weight: 500;
            cursor: pointer;
            width: 100%;
            text-align: center;
            font-family: 'Roboto', sans-serif;
        `;
        
        // Accept All button
        const acceptButton = document.createElement('button');
        acceptButton.setAttribute('data-action', 'consent');
        acceptButton.setAttribute('data-action-type', 'accept');
        acceptButton.id = 'accept';
        acceptButton.textContent = content.accept_button_label || 'Accept All';
        acceptButton.style.cssText = `
            background-color: ${appearance.accept_button_bg || appearance.save_button_bg || '#D98A8A'};
            color: ${appearance.accept_button_text || appearance.save_button_text || '#FFFFFF'};
            border: none;
            border-radius: ${appearance.button_corner_radius || 4}px;
            padding: 12px;
            font-size: ${fontSize};
            font-weight: 500;
            cursor: pointer;
            width: 100%;
            text-align: center;
            font-family: 'Roboto', sans-serif;
        `;
        
        // Add buttons in correct order
        if (appearance.show_deny_all !== false) {
            rightSection.appendChild(denyButton);
        }
        rightSection.appendChild(saveButton);
        rightSection.appendChild(acceptButton);
        
        // Assemble the layout
        mainContainer.appendChild(leftSection);
        mainContainer.appendChild(centerSection);
        mainContainer.appendChild(rightSection);
        
        dialog.appendChild(mainContainer);
        
        // Tab box removed
        
        // Add dialog to wrapper
        wrapper.appendChild(dialog);
        
        // Apply custom CSS if enabled
        if (appearance.custom_css_enabled && appearance.custom_css) {
            const customStyle = document.createElement('style');
            customStyle.textContent = appearance.custom_css;
            wrapper.appendChild(customStyle);
        }
        
        console.log('[ConsentMent-BannerUI] Main dialog created');
        return wrapper;
    }
    
    /**
     * Setup event listeners for the UI
     */
    function setupEventListeners(dialogContainer) {
        console.log('[ConsentMent-BannerUI] Setting up event listeners');
        
        // Accept All button
        const acceptButton = dialogContainer.querySelector('#accept');
        if (acceptButton) {
            acceptButton.addEventListener('click', function() {
                console.log('[ConsentMent-BannerUI] Accept All button clicked');
                handleAcceptAll();
            });
        }
        
        // Deny button
        const denyButton = dialogContainer.querySelector('#deny');
        if (denyButton) {
            denyButton.addEventListener('click', function() {
                console.log('[ConsentMent-BannerUI] Deny button clicked');
                handleDenyAll();
            });
        }
        
        // Save Settings button
        const saveButton = dialogContainer.querySelector('#save');
        if (saveButton) {
            saveButton.addEventListener('click', function() {
                console.log('[ConsentMent-BannerUI] Save Settings button clicked');
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
                    
                    console.log(`[ConsentMent-BannerUI] Toggle clicked for ${category}: ${!isChecked}`);
                    
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
        const detailsLink = dialogContainer.querySelector('#uc-more-link');
        if (detailsLink) {
            detailsLink.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('[ConsentMent-BannerUI] Details link clicked - opening detail view');
                
                // Hide the banner UI when details link is clicked
                hideBannerUI();
                
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
                    console.error('[ConsentMent-BannerUI] Detail UI not available');
                    alert('Detail view is not available yet.');
                }
            });
        }
    }
    
    /**
     * Hide banner UI but don't remove it (for when detail view is shown)
     */
    function hideBannerUI() {
        console.log('[ConsentMent-BannerUI] Hiding banner UI');
        
        const wrapper = document.querySelector('.cmp-wrapper.cmp.first');
        if (wrapper) {
            wrapper.style.display = 'none';
        }
        
        // Make overlay invisible but keep it in the DOM
        const overlay = document.getElementById('consentment-overlay');
        if (overlay) {
            overlay.style.opacity = '0';
        }
    }
    
    /**
     * Handle Accept All button click
     */
    function handleAcceptAll() {
        console.log('[ConsentMent-BannerUI] Handling Accept All');
        
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
        console.log('[ConsentMent-BannerUI] Handling Deny All');
        
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
        console.log('[ConsentMent-BannerUI] Handling Save Settings');
        
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
        
        console.log('[ConsentMent-BannerUI] Custom consent settings:', services);
        
        // Pass consent data to main script
        window.ConsentMent.handleConsent(consentData);
        
        // Remove UI elements
        removeConsentUI();
    }
    
    /**
     * Remove consent UI elements from DOM
     */
    function removeConsentUI() {
        console.log('[ConsentMent-BannerUI] Removing consent UI elements');
        
        // Remove overlay
        const overlay = document.getElementById('consentment-overlay');
        if (overlay) {
            overlay.parentNode.removeChild(overlay);
        }
        
        // Remove dialog
        const wrapper = document.querySelector('.cmp-wrapper');
        if (wrapper) {
            wrapper.parentNode.removeChild(wrapper);
        }
    }
    
})();