/**
 * ConsentMent CMP Wall UI Component
 * This script renders the wall-style consent UI
 * Version: 1.0.0
 */
(function() {
    console.log('[ConsentMent-WallUI] Wall UI script initializing...');
    
    // Add the renderWallUI method to the ConsentMent object
    if (!window.ConsentMent) {
        console.error('[ConsentMent-WallUI] ERROR: ConsentMent global object not found!');
        return;
    }
    
    // Add UI rendering function to main object
    window.ConsentMent.renderWallUI = function(config) {
        console.log('[ConsentMent-WallUI] Rendering wall UI with config:', config);
        console.log('[ConsentMent-WallUI] Appearance settings:', config.appearance);
        
        // Load Roboto font
        loadRobotoFont();
        
        // Create and append overlay
        const overlay = createOverlay(config.appearance);
        document.body.appendChild(overlay);
        console.log('[ConsentMent-WallUI] Overlay added to DOM');
        
        // Create and append the main dialog
        const dialog = createMainDialog(config);
        document.body.appendChild(dialog);
        console.log('[ConsentMent-WallUI] Main dialog added to DOM');
        
        // Setup event listeners
        setupEventListeners(dialog);
        console.log('[ConsentMent-WallUI] Event listeners set up');
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
        
        console.log('[ConsentMent-WallUI] Google Roboto font loaded');
    }
    
    /**
     * Create the background overlay
     */
    function createOverlay(appearance) {
        console.log('[ConsentMent-WallUI] Creating overlay');
        
        const overlay = document.createElement('div');
        overlay.id = 'consentment-overlay';
        
        // For wall UI, always create a dark overlay
        overlay.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: ${appearance.overlay_color || 'rgba(75, 85, 99, 0.9)'};
            opacity: ${(appearance.overlay_opacity || 90) / 100};
            z-index: 2147483646;
        `;
        
        return overlay;
    }
    
    /**
     * Create the main dialog element - centered wall modal
     */
    function createMainDialog(config) {
        console.log('[ConsentMent-WallUI] Creating main dialog');
        
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
        wrapper.className = 'cmp-wrapper cmp wall cb desktop zoom-xxs';
        
        // Create main dialog
        const dialog = document.createElement('div');
        dialog.id = 'uc-main-dialog';
        dialog.className = 'cmp wall desktop ltr gdpr';
        dialog.setAttribute('role', 'dialog');
        dialog.setAttribute('aria-modal', 'true');
        dialog.setAttribute('aria-labelledby', 'uc-privacy-title');
        dialog.setAttribute('aria-describedby', 'uc-privacy-description');
        dialog.setAttribute('tabindex', '0');
        
        // Apply the layout for centered modal
        dialog.style.cssText = `
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            max-width: 600px;
            width: 90%;
            background-color: #FFFFFF;
            color: #000000;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            z-index: 2147483647;
            font-family: 'Roboto', sans-serif;
            font-size: ${fontSize};
            padding: 30px;
            box-sizing: border-box;
            border-radius: 8px;
            text-align: left;
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
            font-family: 'Roboto', sans-serif;
        `;
        
        // Privacy Settings title
        const title = document.createElement('h2');
        title.id = 'uc-privacy-title';
        title.textContent = content.first_layer_title || 'Privacy Information';
        title.style.cssText = `
            font-size: 20px;
            font-weight: 600;
            margin: 0 0 15px 0;
            color: #000000;
            font-family: 'Roboto', sans-serif;
            text-align: left;
        `;
        mainContainer.appendChild(title);

        // Description text
        const description = document.createElement('p');
        description.id = 'uc-privacy-description';
        description.textContent = content.first_layer_message || 
            'This site uses third-party website tracking technologies to provide and continually improve our services, and to display advertisements according to users\' interests. I agree and may revoke or change my consent at any time with effect for the future.';
        description.style.cssText = `
            font-size: ${fontSize};
            line-height: 1.5;
            margin: 0 0 20px 0;
            color: #000000;
            font-family: 'Roboto', sans-serif;
            text-align: left;
        `;
        mainContainer.appendChild(description);
        
        // More Details link
        const moreDetailsLink = document.createElement('a');
        moreDetailsLink.id = 'uc-more-link';
        moreDetailsLink.setAttribute('role', 'button');
        moreDetailsLink.setAttribute('tabindex', '0');
        moreDetailsLink.setAttribute('data-action', 'consent');
        moreDetailsLink.setAttribute('data-action-type', 'more');
        moreDetailsLink.style.cssText = `
            text-decoration: none;
            color: rgb(83, 83, 83);
            font-size: ${fontSize};
            cursor: pointer;
            margin-bottom: 20px;
            display: inline-block;
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
        
        // Create a div for the link with left alignment
        const linkContainer = document.createElement('div');
        linkContainer.style.cssText = `
            text-align: left;
            margin-bottom: 20px;
        `;
        linkContainer.appendChild(moreDetailsLink);
        mainContainer.appendChild(linkContainer);
        
        // Buttons container
        const buttonsContainer = document.createElement('div');
        buttonsContainer.className = 'consentment-buttons-container';
        buttonsContainer.style.cssText = `
            display: flex;
            justify-content: space-between;
            gap: 15px;
            margin-top: 10px;
            width: 100%;
        `;
        
        // Deny button
        const denyButton = document.createElement('button');
        denyButton.setAttribute('data-action', 'consent');
        denyButton.setAttribute('data-action-type', 'deny');
        denyButton.id = 'deny';
        denyButton.textContent = content.deny_button_label || 'Deny';
        denyButton.style.cssText = `
            background-color: ${appearance.deny_button_bg || '#004AAD'};
            color: ${appearance.deny_button_text || '#FFFFFF'};
            border: none;
            border-radius: ${appearance.button_corner_radius || 4}px;
            padding: 12px 0;
            font-size: ${fontSize};
            font-weight: 500;
            cursor: pointer;
            flex: 1;
            font-family: 'Roboto', sans-serif;
        `;
        buttonsContainer.appendChild(denyButton);
        
        // Accept All button
        const acceptButton = document.createElement('button');
        acceptButton.setAttribute('data-action', 'consent');
        acceptButton.setAttribute('data-action-type', 'accept');
        acceptButton.id = 'accept';
        acceptButton.textContent = content.accept_button_label || 'Accept All';
        acceptButton.style.cssText = `
            background-color: ${appearance.accept_button_bg || appearance.save_button_bg || '#004AAD'};
            color: ${appearance.accept_button_text || appearance.save_button_text || '#FFFFFF'};
            border: none;
            border-radius: ${appearance.button_corner_radius || 4}px;
            padding: 12px 0;
            font-size: ${fontSize};
            font-weight: 500;
            cursor: pointer;
            flex: 1;
            font-family: 'Roboto', sans-serif;
        `;
        buttonsContainer.appendChild(acceptButton);
        
        mainContainer.appendChild(buttonsContainer);
        
        // Logo image at the bottom instead of text
        const logoContainer = document.createElement('div');
        logoContainer.className = 'consentment-powered-by';
        logoContainer.style.cssText = `
            margin-top: 20px;
            text-align: center;
            font-family: 'Roboto', sans-serif; 
        `;
        
        const logoImage = document.createElement('img');
        logoImage.src = '/consentment-main.png';
        logoImage.alt = 'ConsentMent';
        logoImage.style.cssText = 'max-height: 65px;width: auto;margin-top: 20px;';
        
        logoContainer.appendChild(logoImage);
        mainContainer.appendChild(logoContainer);
        
        dialog.appendChild(mainContainer);
        wrapper.appendChild(dialog);
        
        // Apply custom CSS if enabled
        if (appearance.custom_css_enabled && appearance.custom_css) {
            const customStyle = document.createElement('style');
            customStyle.textContent = appearance.custom_css;
            wrapper.appendChild(customStyle);
        }
        
        console.log('[ConsentMent-WallUI] Main dialog created');
        return wrapper;
    }
    
    /**
     * Setup event listeners for the UI
     */
    function setupEventListeners(dialogContainer) {
        console.log('[ConsentMent-WallUI] Setting up event listeners');
        
        // Accept All button
        const acceptButton = dialogContainer.querySelector('#accept');
        if (acceptButton) {
            acceptButton.addEventListener('click', function() {
                console.log('[ConsentMent-WallUI] Accept All button clicked');
                handleAcceptAll();
            });
        }
        
        // Deny button
        const denyButton = dialogContainer.querySelector('#deny');
        if (denyButton) {
            denyButton.addEventListener('click', function() {
                console.log('[ConsentMent-WallUI] Deny button clicked');
                handleDenyAll();
            });
        }
        
        // More Details link
        const detailsLink = dialogContainer.querySelector('#uc-more-link');
        if (detailsLink) {
            detailsLink.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('[ConsentMent-WallUI] More Details link clicked - opening detail view');
                
                // Hide the wall UI immediately when details link is clicked
                hideWallUI();
                
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
                    console.error('[ConsentMent-WallUI] Detail UI not available');
                    alert('Detail view is not available yet.');
                }
            });
        }
    }
    
    /**
     * Hide wall UI but don't remove it (for when detail view is shown)
     */
    function hideWallUI() {
        console.log('[ConsentMent-WallUI] Hiding wall UI');
        
        const wrapper = document.querySelector('.cmp-wrapper.cmp.wall');
        if (wrapper) {
            wrapper.style.display = 'none';
        }
        
        // Keep overlay but make it less opaque
        const overlay = document.getElementById('consentment-overlay');
        if (overlay) {
            overlay.style.opacity = '0.7';
        }
    }
    
    /**
     * Handle Accept All button click
     */
    function handleAcceptAll() {
        console.log('[ConsentMent-WallUI] Handling Accept All');
        
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
        console.log('[ConsentMent-WallUI] Handling Deny All');
        
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
     * Remove consent UI elements from DOM
     */
    function removeConsentUI() {
        console.log('[ConsentMent-WallUI] Removing consent UI elements');
        
        // Remove overlay
        const overlay = document.getElementById('consentment-overlay');
        if (overlay) {
            overlay.parentNode.removeChild(overlay);
        }
        
        // Remove dialog
        const wrapper = document.querySelector('.cmp-wrapper.cmp.wall');
        if (wrapper) {
            wrapper.parentNode.removeChild(wrapper);
        }
    }
    
})();