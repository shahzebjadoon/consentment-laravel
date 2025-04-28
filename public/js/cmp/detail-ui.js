/**
 * ConsentMent CMP Detail UI Component
 * This script renders the detailed view when "Details" is clicked
 * Version: 1.0.0
 */
(function() {
    console.log('[ConsentMent-DetailUI] Detail UI script initializing...');
    
    // Add the renderDetailUI method to the ConsentMent object
    if (!window.ConsentMent) {
        console.error('[ConsentMent-DetailUI] ERROR: ConsentMent global object not found!');
        return;
    }
    
    // Add UI rendering function to main object
window.ConsentMent.renderDetailUI = function(config) {
    console.log('[ConsentMent-DetailUI] Rendering detail UI with config:', config);
    console.log('[ConsentMent-DetailUI] Appearance settings:', config.appearance);
    
    // Load Roboto font
    loadRobotoFont();
    
    loadResponsiveDetailCSS();
        
        // Create and append the detail dialog
        const dialog = createDetailDialog(config);
        document.body.appendChild(dialog);
        console.log('[ConsentMent-DetailUI] Detail dialog added to DOM');
        
        // Setup event listeners
        setupEventListeners(dialog, config);
        console.log('[ConsentMent-DetailUI] Event listeners set up');
        
        // Set initial active tab
        activateTab('categories', dialog);
        
        // Show detail UI and hide wall UI
        window.ConsentMent.toggleDetailView(true);
    };
    
    /**
     * Create the detailed dialog element
     */
    function createDetailDialog(config) {
        console.log('[ConsentMent-DetailUI] Creating detail dialog');
        
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
        wrapper.className = 'cmp-wrapper cmp detail cb desktop zoom-xxs';
        
        // Create detail dialog
        const dialog = document.createElement('div');
        dialog.id = 'uc-detail-dialog';
        dialog.className = 'cmp detail desktop ltr gdpr';
        dialog.setAttribute('role', 'dialog');
        dialog.setAttribute('aria-modal', 'true');
        dialog.setAttribute('aria-labelledby', 'uc-detail-title');
        dialog.setAttribute('tabindex', '0');
        
        // Apply the layout with similar styling to the main dialog
dialog.style.cssText = `
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 800px;
    max-width: 90%;
    max-height: 80vh;
    background-color: ${appearance.background_color || '#FFFFFF'};
    color: ${appearance.text_color || '#000000'};
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
    z-index: 2147483647;
    font-family: 'Roboto', sans-serif;
    font-size: ${fontSize};
    border-radius: 6px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
`;
        
        // Header section
const header = document.createElement('div');
header.className = 'detail-header';
header.style.cssText = `
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    border-bottom: 1px solid #eee;
`;

// Customer logo on the left side
const headerLogo = document.createElement('div');
headerLogo.className = 'detail-header-logo';
headerLogo.style.cssText = `
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
    headerLogo.appendChild(customerImg);
    console.log('[ConsentMent-DetailUI] Added customer logo from:', customerImg.src);
}

// ConsentMent logo on the right side with link
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
consentmentImg.style.cssText = 'max-height: 35px; width: auto;';
logoLink.appendChild(consentmentImg);
consentmentLogo.appendChild(logoLink);

// Close button
const closeButton = document.createElement('button');
closeButton.className = 'detail-close-button';
closeButton.setAttribute('aria-label', 'Close');
closeButton.style.cssText = `
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
    color: #666;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    padding: 0;
    margin-left: 15px;
    display: none !important;
`;
closeButton.innerHTML = '×';

// Create a flex container for the right side elements
const rightContainer = document.createElement('div');
rightContainer.className = 'detail-right-container';
rightContainer.style.cssText = `
    display: flex;
    align-items: center;
`;

// Assemble right container
rightContainer.appendChild(consentmentLogo);
rightContainer.appendChild(closeButton);

// Assemble header (logo on left, ConsentMent logo and close button on right)
header.appendChild(headerLogo);
header.appendChild(rightContainer);
        
        // Tabs section
        const tabsContainer = document.createElement('div');
        tabsContainer.className = 'detail-tabs';
        tabsContainer.style.cssText = `
            display: flex;
            border-bottom: 1px solid #eee;
            background-color: ${appearance.background_color || '#FFFFFF'};
        `;
        
        // Tab buttons
        const tabs = [
            { id: 'categories', label: 'Categories' },
            { id: 'services', label: 'Services' },
            { id: 'about', label: 'About' }
        ];
        
        tabs.forEach(tab => {
            const tabButton = document.createElement('button');
            tabButton.className = `detail-tab-button ${tab.id === 'categories' ? 'active' : ''}`;
            tabButton.setAttribute('data-tab', tab.id);
            tabButton.textContent = tab.label;
            tabButton.style.cssText = `
                padding: 15px 20px;
                background: none;
                border: none;
                border-bottom: ${tab.id === 'categories' ? `3px solid ${appearance.active_toggle_bg || '#D98A8A'}` : '3px solid transparent'};
                cursor: pointer;
                font-weight: ${tab.id === 'categories' ? '600' : 'normal'};
                color: ${tab.id === 'categories' ? (appearance.text_color || '#000000') : '#666'};
                transition: all 0.2s;
                flex: 1;
                text-align: center;
                font-size: ${fontSize};
            `;
            tabsContainer.appendChild(tabButton);
        });
        
        // Content area
        const contentArea = document.createElement('div');
        contentArea.className = 'detail-content';
        contentArea.style.cssText = `
            flex: 1;
            overflow-y: auto;
            padding: 0;
        `;
        
        // Create content for each tab
        const tabContents = document.createElement('div');
        tabContents.className = 'detail-tab-contents';
        
        // Categories tab content
        const categoriesContent = document.createElement('div');
        categoriesContent.className = 'detail-tab-content categories active';
        categoriesContent.setAttribute('data-tab-content', 'categories');
        categoriesContent.style.cssText = `
            display: block;
            padding: 20px;
        `;
        
        // Add categories header at the top of the categories tab
const categoriesHeader = document.createElement('div');
categoriesHeader.style.cssText = `margin-bottom: 20px;`;

const categoriesTitle = document.createElement('h3');
categoriesTitle.textContent = content.categories_title || 'Categories';
categoriesTitle.style.cssText = `
    margin-top: 0; 
    margin-bottom: 10px;
    color: ${appearance.text_color || '#000000'};
`;

const categoriesDescription = document.createElement('p');
categoriesDescription.textContent = content.categories_description || 'These categories group services by their data processing purpose.';
categoriesDescription.style.cssText = `
    margin: 0 0 20px 0;
    line-height: 1.5;
`;

categoriesHeader.appendChild(categoriesTitle);
categoriesHeader.appendChild(categoriesDescription);
categoriesContent.appendChild(categoriesHeader);
        
        // Add category sections
        const categories = [
            { 
                id: 'marketing', 
                name: 'Marketing', 
                description: 'These technologies are used by advertisers to serve ads that are relevant to your interests.',
                count: 2,
                essential: false 
            },
            { 
                id: 'functional', 
                name: 'Functional', 
                description: 'These technologies enable us to analyse usage behavior in order to measure and improve performance.',
                count: 4,
                essential: false 
            },
            { 
                id: 'essential', 
                name: 'Essential', 
                description: 'These technologies are required to activate the core functionality of our service.',
                count: 1,
                essential: true 
            }
        ];
        
        categories.forEach(category => {
            const categorySection = document.createElement('div');
            categorySection.className = `detail-category ${category.id}`;
            categorySection.style.cssText = `
                margin-bottom: 30px;
                border-bottom: 1px solid #eee;
                padding-bottom: 20px;
            `;
            
            // Category header with toggle
            const categoryHeader = document.createElement('div');
            categoryHeader.className = 'detail-category-header';
            categoryHeader.style.cssText = `
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 15px;
            `;
            
            // Category title and counter
            const categoryTitle = document.createElement('div');
            categoryTitle.className = 'detail-category-title';
            categoryTitle.style.cssText = `
                display: flex;
                align-items: center;
            `;
            
            const categoryName = document.createElement('h3');
            categoryName.textContent = category.name;
            categoryName.style.cssText = `
                margin: 0;
                font-size: 16px;
                font-weight: 600;
                color: ${appearance.text_color || '#000000'};
            `;
            
            const categoryCounter = document.createElement('span');
            categoryCounter.className = 'category-counter';
            categoryCounter.textContent = category.count;
            categoryCounter.style.cssText = `
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 20px;
                height: 20px;
                border-radius: 50%;
                background-color: #eee;
                font-size: 12px;
                margin-left: 10px;
                color: #666;
            `;
            
            categoryTitle.appendChild(categoryName);
            categoryTitle.appendChild(categoryCounter);
            
            // Category toggle
            const categoryToggleContainer = document.createElement('div');
            categoryToggleContainer.className = 'detail-category-toggle';
            
            // Create toggle that looks exactly like in the screenshot
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
            toggleInput.id = `uc-detail-category-${category.id}-toggle`;
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
            categoryToggleContainer.appendChild(toggle);
            
            // Assemble category header
            categoryHeader.appendChild(categoryTitle);
            categoryHeader.appendChild(categoryToggleContainer);
            
            // Category description
            const categoryDescription = document.createElement('p');
            categoryDescription.className = 'detail-category-description';
            categoryDescription.textContent = category.description;
            categoryDescription.style.cssText = `
                margin: 0 0 15px 0;
                font-size: ${fontSize};
                line-height: 1.5;
                color: ${appearance.text_color || '#000000'};
            `;
            
            // Assemble category section
            categorySection.appendChild(categoryHeader);
            categorySection.appendChild(categoryDescription);
            
            // Add expandable arrow and service list if not essential
            if (!category.essential) {
                const expandToggle = document.createElement('button');
                expandToggle.className = 'category-expand-toggle';
                expandToggle.setAttribute('data-category', category.id);
                expandToggle.style.cssText = `
                    background: none;
                    border: none;
                    color: ${appearance.link_color || '#D98A8A'};
                    font-size: ${fontSize};
                    padding: 0;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    margin-bottom: 15px;
                `;
                
                const expandText = document.createElement('span');
                expandText.textContent = 'Show services';
                
                const expandArrow = document.createElement('span');
                expandArrow.textContent = ' ›';
                expandArrow.style.cssText = 'margin-left: 4px; transform: rotate(90deg); display: inline-block;';
                
                expandToggle.appendChild(expandText);
                expandToggle.appendChild(expandArrow);
                categorySection.appendChild(expandToggle);
                
                // Service list (initially hidden)
                const serviceList = document.createElement('div');
                serviceList.className = 'category-service-list';
                serviceList.setAttribute('data-category', category.id);
                serviceList.style.cssText = `
                    display: none;
                    margin-top: 10px;
                    padding-left: 15px;
                `;
                
                // Add some sample services
                const services = [
                    { id: `${category.id}-service-1`, name: 'Analytics Service' },
                    { id: `${category.id}-service-2`, name: 'Tracking Pixel' }
                ];
                
                services.forEach(service => {
                    const serviceItem = document.createElement('div');
                    serviceItem.className = 'service-item';
                    serviceItem.style.cssText = `
                        margin-bottom: 10px;
                        padding-bottom: 10px;
                        border-bottom: 1px dashed #eee;
                    `;
                    
                    const serviceName = document.createElement('p');
                    serviceName.textContent = service.name;
                    serviceName.style.cssText = `
                        margin: 0 0 5px 0;
                        font-weight: 500;
                        font-size: ${fontSize};
                    `;
                    
                    const serviceDesc = document.createElement('p');
                    serviceDesc.textContent = `This is a sample description for ${service.name}.`;
                    serviceDesc.style.cssText = `
                        margin: 0;
                        font-size: 13px;
                        color: #666;
                    `;
                    
                    serviceItem.appendChild(serviceName);
                    serviceItem.appendChild(serviceDesc);
                    serviceList.appendChild(serviceItem);
                });
                
                categorySection.appendChild(serviceList);
            }
            
            categoriesContent.appendChild(categorySection);
        });
        
        // Services tab content
        const servicesContent = document.createElement('div');
        servicesContent.className = 'detail-tab-content services';
        servicesContent.setAttribute('data-tab-content', 'services');
        servicesContent.style.cssText = `
            display: none;
            padding: 20px;
        `;
        
        servicesContent.innerHTML = `
    <h3 style="margin-top: 0; color: ${appearance.text_color || '#000000'};">${content.services_title || 'All Services'}</h3>
    <p>${content.services_description || 'This tab displays all services used on this website.'}</p>
            
            <div style="margin-top: 20px;">
                <div style="margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <h4 style="margin: 0; font-size: 16px; color: ${appearance.text_color || '#000000'};">Google Analytics</h4>
                        <span style="font-size: 12px; color: #666; background: #eee; padding: 2px 6px; border-radius: 10px;">Marketing</span>
                    </div>
                    <p style="margin: 10px 0 0; font-size: 14px; color: #666;">This service helps us track user behavior on our website.</p>
                </div>
                
                <div style="margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <h4 style="margin: 0; font-size: 16px; color: ${appearance.text_color || '#000000'};">Facebook Pixel</h4>
                        <span style="font-size: 12px; color: #666; background: #eee; padding: 2px 6px; border-radius: 10px;">Marketing</span>
                    </div>
                    <p style="margin: 10px 0 0; font-size: 14px; color: #666;">This service helps us track conversions from Facebook ads.</p>
                </div>
                
                <div style="margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #eee;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <h4 style="margin: 0; font-size: 16px; color: ${appearance.text_color || '#000000'};">Cloudflare</h4>
                        <span style="font-size: 12px; color: #666; background: #eee; padding: 2px 6px; border-radius: 10px;">Essential</span>
                    </div>
                    <p style="margin: 10px 0 0; font-size: 14px; color: #666;">This service provides security and performance optimization.</p>
                </div>
            </div>
        `;
        
        // About tab content
        const aboutContent = document.createElement('div');
        aboutContent.className = 'detail-tab-content about';
        aboutContent.setAttribute('data-tab-content', 'about');
        aboutContent.style.cssText = `
            display: none;
            padding: 20px;
        `;
        
        aboutContent.innerHTML = `
            <h3 style="margin-top: 0; color: ${appearance.text_color || '#000000'};">About Our Privacy Policy</h3>
            
            <p style="line-height: 1.5; margin-bottom: 15px;">
                We use cookies and similar technologies on our website. Some of them are essential, while others help us improve this website and your experience.
            </p>
            
            <p style="line-height: 1.5; margin-bottom: 15px;">
                You can decide for yourself which categories you want to allow. Please note that depending on your settings, not all functions of the website may be available.
            </p>
            
            <h4 style="margin: 20px 0 10px; color: ${appearance.text_color || '#000000'};">Contact Information</h4>
            
            <p style="line-height: 1.5; margin-bottom: 15px;">
                If you have any questions about our privacy policy, please contact our data protection officer:
            </p>
            
            <p style="line-height: 1.5; margin-bottom: 5px;">
                Email: privacy@example.com
            </p>
            
            <p style="line-height: 1.5; margin-bottom: 15px;">
                Address: 123 Privacy Street, Data City, 12345
            </p>
            
            <h4 style="margin: 20px 0 10px; color: ${appearance.text_color || '#000000'};">Last Updated</h4>
            <p style="line-height: 1.5;">
                Our privacy policy was last updated on April 1, 2025.
            </p>
        `;
        
        // Add all tab contents
        tabContents.appendChild(categoriesContent);
        tabContents.appendChild(servicesContent);
        tabContents.appendChild(aboutContent);
        contentArea.appendChild(tabContents);
        
        // Bottom buttons section
        const buttonSection = document.createElement('div');
        buttonSection.className = 'detail-buttons';
        buttonSection.style.cssText = `
            display: flex;
            justify-content: space-between;
            padding: 15px 20px;
            border-top: 1px solid #eee;
            background-color: ${appearance.background_color || '#FFFFFF'};
        `;
        
        // Save Settings button
        const saveButton = document.createElement('button');
        saveButton.className = 'detail-save-button';
        saveButton.setAttribute('data-action', 'consent');
        saveButton.setAttribute('data-action-type', 'save');
        saveButton.textContent = content.save_button || 'Save Settings';
        saveButton.style.cssText = `
            background-color: ${appearance.save_button_bg || '#D98A8A'};
            color: ${appearance.save_button_text || '#FFFFFF'};
            border: none;
            border-radius: ${appearance.button_corner_radius || 4}px;
            padding: 10px 20px;
            font-size: ${fontSize};
            font-weight: 500;
            cursor: pointer;
            flex: 1;
            margin: 0 5px;
            text-align: center;
        `;
        
        // Deny button
        const denyButton = document.createElement('button');
        denyButton.className = 'detail-deny-button';
        denyButton.setAttribute('data-action', 'consent');
        denyButton.setAttribute('data-action-type', 'deny');
        denyButton.textContent =  content.deny_all_button || 'Deny';
        denyButton.style.cssText = `
            background-color: ${appearance.deny_button_bg || '#D98A8A'};
            color: ${appearance.deny_button_text || '#FFFFFF'};
            border: none;
            border-radius: ${appearance.button_corner_radius || 4}px;
            padding: 10px 20px;
            font-size: ${fontSize};
            font-weight: 500;
            cursor: pointer;
            flex: 1;
            margin: 0 5px;
            text-align: center;
        `;
        
        // Accept All button
        const acceptButton = document.createElement('button');
        acceptButton.className = 'detail-accept-button';
        acceptButton.setAttribute('data-action', 'consent');
        acceptButton.setAttribute('data-action-type', 'accept');
        acceptButton.textContent = content.accept_all_button || 'Accept All';
        acceptButton.style.cssText = `
            background-color: ${appearance.accept_button_bg || appearance.save_button_bg || '#D98A8A'};
            color: ${appearance.accept_button_text || appearance.save_button_text || '#FFFFFF'};
            border: none;
            border-radius: ${appearance.button_corner_radius || 4}px;
            padding: 10px 20px;
            font-size: ${fontSize};
            font-weight: 500;
            cursor: pointer;
            flex: 1;
            margin: 0 5px;
            text-align: center;
        `;
        
        // Add buttons in the order matching the screenshot
        buttonSection.appendChild(denyButton);
        buttonSection.appendChild(saveButton);
        buttonSection.appendChild(acceptButton);
        
        // Assemble the dialog
        dialog.appendChild(header);
        dialog.appendChild(tabsContainer);
        dialog.appendChild(contentArea);
        dialog.appendChild(buttonSection);
        
        // Add dialog to wrapper
        wrapper.appendChild(dialog);
        
        console.log('[ConsentMent-DetailUI] Detail dialog created');
        return wrapper;
    }
    
    /**
     * Setup event listeners for the UI
     */
    function setupEventListeners(dialogContainer, config) {
        console.log('[ConsentMent-DetailUI] Setting up event listeners');
        
        // Close button
        const closeButton = dialogContainer.querySelector('.detail-close-button');
        if (closeButton) {
            closeButton.addEventListener('click', function() {
                console.log('[ConsentMent-DetailUI] Close button clicked');
                removeDetailUI();
            });
        }
        
        // Tab buttons
        const tabButtons = dialogContainer.querySelectorAll('.detail-tab-button');
        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');
                console.log(`[ConsentMent-DetailUI] Tab clicked: ${tabId}`);
                activateTab(tabId, dialogContainer);
            });
        });
        
        // Category expand toggles
        const expandToggles = dialogContainer.querySelectorAll('.category-expand-toggle');
        expandToggles.forEach(toggle => {
            toggle.addEventListener('click', function() {
                const categoryId = this.getAttribute('data-category');
                const serviceList = dialogContainer.querySelector(`.category-service-list[data-category="${categoryId}"]`);
                const arrowElement = this.querySelector('span:last-child');
                
                if (serviceList) {
                    const isVisible = serviceList.style.display === 'block';
                    serviceList.style.display = isVisible ? 'none' : 'block';
                    
                    // Update toggle text and arrow
                    this.querySelector('span:first-child').textContent = isVisible ? 'Show services' : 'Hide services';
                    
                    // Rotate arrow
                    if (arrowElement) {
                        arrowElement.style.transform = isVisible ? 'rotate(90deg)' : 'rotate(-90deg)';
                    }
                    
                    console.log(`[ConsentMent-DetailUI] Service list for ${categoryId} ${isVisible ? 'hidden' : 'shown'}`);
                }
            });
        });
        
        // Category toggles
        const toggles = dialogContainer.querySelectorAll('button[data-action="toggle"]');
        toggles.forEach(toggle => {
            if (!toggle.hasAttribute('disabled')) {
                toggle.addEventListener('click', function() {
                    const category = this.getAttribute('value');
                    const isChecked = this.getAttribute('aria-checked') === 'true';
                    const appearance = config.appearance;
                    
                    console.log(`[ConsentMent-DetailUI] Toggle clicked for ${category}: ${!isChecked}`);
                    
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
        
        // Accept All button
        const acceptButton = dialogContainer.querySelector('.detail-accept-button');
        if (acceptButton) {
            acceptButton.addEventListener('click', function() {
                console.log('[ConsentMent-DetailUI] Accept All button clicked');
                handleAcceptAll();
                removeDetailUI();
            });
        }
        
        // Deny button
        const denyButton = dialogContainer.querySelector('.detail-deny-button');
        if (denyButton) {
            denyButton.addEventListener('click', function() {
                console.log('[ConsentMent-DetailUI] Deny button clicked');
                handleDenyAll();
                removeDetailUI();
            });
        }
        
        // Save Settings button
        const saveButton = dialogContainer.querySelector('.detail-save-button');
        if (saveButton) {
            saveButton.addEventListener('click', function() {
                console.log('[ConsentMent-DetailUI] Save Settings button clicked');
                handleSaveSettings(dialogContainer);
                removeDetailUI();
            });
        }
    }
    
    /**
     * Activate a tab in the detail view
     */
    function activateTab(tabId, container) {
        console.log(`[ConsentMent-DetailUI] Activating tab: ${tabId}`);
        
        // Update tab buttons
        const tabButtons = container.querySelectorAll('.detail-tab-button');
        const appearance = window.ConsentMent.config.appearance;
        
tabButtons.forEach(button => {
    const buttonTabId = button.getAttribute('data-tab');
    if (buttonTabId === tabId) {
        button.classList.add('active');
        button.style.fontWeight = '600';
        button.style.color = appearance.text_color || '#000000';
        button.style.borderBottom = `3px solid ${appearance.active_toggle_bg || '#888888'}`;
    } else {
        button.classList.remove('active');
        button.style.fontWeight = 'normal';
        button.style.color = '#666';
        button.style.borderBottom = '3px solid transparent';
    }
});
        
        // Update tab content
        const tabContents = container.querySelectorAll('.detail-tab-content');
        tabContents.forEach(content => {
            const contentTabId = content.getAttribute('data-tab-content');
            if (contentTabId === tabId) {
                content.style.display = 'block';
            } else {
                content.style.display = 'none';
            }
        });
    }
    
    /**
     * Handle Accept All button click
     */
    function handleAcceptAll() {
        console.log('[ConsentMent-DetailUI] Handling Accept All');
        
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
        console.log('[ConsentMent-DetailUI] Handling Deny All');
        
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
    function handleSaveSettings(dialogContainer) {
        console.log('[ConsentMent-DetailUI] Handling Save Settings');
        
        // Get all toggle states
        const toggles = dialogContainer.querySelectorAll('button[data-action="toggle"]');
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
        
        console.log('[ConsentMent-DetailUI] Custom consent settings:', services);
        
        // Pass consent data to main script
        window.ConsentMent.handleConsent(consentData);
        
        // Remove UI elements
        removeConsentUI();
    }
    
    /**
     * Remove consent UI elements from DOM
     */
    function removeConsentUI() {
        console.log('[ConsentMent-DetailUI] Removing all consent UI elements');
        
        // Remove overlay
        const overlay = document.getElementById('consentment-overlay');
        if (overlay) {
            overlay.parentNode.removeChild(overlay);
        }
        
        // Remove wall dialog
        const wallWrapper = document.querySelector('.cmp-wrapper.cmp.first');
        if (wallWrapper) {
            wallWrapper.parentNode.removeChild(wallWrapper);
        }
        
        // Remove detail dialog
        removeDetailUI();
    }

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
    
    console.log('[ConsentMent-DetailUI] Google Roboto font loaded');
}

    
 function loadResponsiveDetailCSS() {
    // Check if already loaded
    if (document.getElementById('consentment-detail-responsive-css')) {
        return;
    }
    
    // Create style element for mobile styles
    const mobileStyle = document.createElement('style');
    mobileStyle.id = 'consentment-detail-responsive-css';
    mobileStyle.textContent = `
/* ConsentMent CMP Detail UI - Mobile Responsive CSS */
@media screen and (max-width: 768px) {
  #uc-detail-dialog {
    max-width: 95% !important;
    max-height: 90vh !important;
  }
  
  .detail-content {
    max-height: calc(60vh - 120px) !important;
  }
  
  .detail-buttons {
    flex-direction: column !important;
    padding: 10px 15px !important;
  }
  
  .detail-buttons button {
    margin: 5px 0 !important;
    width: 95% !important;
    align-self: center !important;
  }
  
  .detail-header {
    padding: 10px 15px !important;
  }
  
  .detail-tabs {
    overflow-x: auto !important;
  }
  
  .detail-tab-button {
    padding: 10px 15px !important;
    min-width: 90px !important;
  }
}`;
    document.head.appendChild(mobileStyle);
    
    console.log('[ConsentMent-DetailUI] Mobile responsive styles for detail view loaded');
}   
    
    
    /**
     * Remove detail UI elements from DOM
     */
    function removeDetailUI() {
        console.log('[ConsentMent-DetailUI] Hiding detail UI elements');
        
        // Toggle back to wall UI instead of removing
        window.ConsentMent.toggleDetailView(false);
    }
    
    console.log('[ConsentMent-DetailUI] Detail UI script initialized');
})();