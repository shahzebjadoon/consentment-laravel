/**
 * ConsentMent CMP Main Script
 * Enhanced with TCF support, better consent management, and improved unblocking
 * Version: 2.0.0
 */
(function () {
    'use strict';
    // Debug mode - set to false in production
    const DEBUG = true;
    
    // Logger function that respects debug mode
    const log = function (message, data) {
        if (DEBUG) {
            if (data) {
                console.log('[ConsentMent-Main] ' + message, data);
            } else {
                console.log('[ConsentMent-Main] ' + message);
            }
        }
    };
    log('Main script initializing...');
    // Define the global ConsentMent object
    window.ConsentMent = {
        // Core properties
        config: null,
        consent: {},
        options: {},
        tcfApi: null,
        googleConsentMode: false,
        // Registry data from loader
        scriptRegistry: { blocked: [], executed: [] },
        cookieRegistry: { allowed: {}, blocked: {} },
        // Event callbacks
        onConsentChanged: null, // Callback function used by loader.js
        /**
         * Initialize the CMP
         */
        init: function (options) {

            this.showDetailIcon(options); // Show detail icon for toggling detail view


            
            log('Initializing with options:', options);
            this.options = options;
            // Store registries from loader
            if (options.scriptRegistry) {
                log('Script registry received from loader', options.scriptRegistry);
                this.scriptRegistry = options.scriptRegistry;
            }
            if (options.cookieRegistry) {
                log('Cookie registry received from loader', options.cookieRegistry);
                this.cookieRegistry = options.cookieRegistry;
            }
            // Load existing consent if available
            if (options.existingConsent) {
                log('Using existing consent:', options.existingConsent);
                this.consent = options.existingConsent;
                this.googleConsentMode = true; // Assume Google Consent Mode is active
                // No need to show UI if consent is already given
                log('Consent already established, no UI needed');
                this.detailScript(); // Load detail UI script
                this.unblockScripts();
                // Initialize TCF API if needed
                this.initializeTcfApi();
                return;
            }
            
          



            // Load configuration and then display UI
            this.loadConfig()
                .then(() => {
                    log('Configuration loaded successfully');
                    // Initialize TCF API if needed
                    this.initializeTcfApi();
                    log('Loading consent UI');
                    this.loadConsentUI();
                })
                .catch(error => {
                    console.error('[ConsentMent-Main] Error loading configuration:', error);
                    // Attempt to show a fallback UI in case of error
                    this.loadFallbackUI();
                });

        },
        /**
         * Load configuration from server
         */
        loadConfig: function () {
            const url = this.options.apiBaseUrl + '/consent/config/' + this.options.settingsId;
            log('Loading configuration from:', url);
            return fetch(url)
                .then(response => {
                    if (!response.ok) {
                        log('Failed to load configuration:', response.status);
                        throw new Error('Failed to load configuration: ' + response.status);
                    }
                    return response.json();
                })
                .then(config => {
                    log('Configuration loaded:', config);
                    this.config = config;
                    // Process and organize service categories
                    this.processServiceCategories();
                    // Add some script patterns from configuration if available
                    if (config.scriptPatterns) {
                        log('Script patterns loaded from config', config.scriptPatterns);
                    }
                    return config;
                });
        },
        /**
         * Process service categories from configuration
         */
        processServiceCategories: function () {
            // Check if we have services in the config
            if (!this.config || !this.config.services) {
                log('No services found in configuration');
                return;
            }
            log('Processing service categories');
            // Organize services by category
            this.servicesByCategory = {};
            this.config.services.forEach(service => {
                if (!this.servicesByCategory[service.category]) {
                    this.servicesByCategory[service.category] = [];
                }
                this.servicesByCategory[service.category].push(service);
                log(`Service ${service.name} added to category ${service.category}`);
            });
            log('Service categories processed:', this.servicesByCategory);
        },
        /**
         * Load the appropriate UI based on layout_type from configuration
         */
        loadConsentUI: function () {
       
            log('Loading consent UI');
            // log('Loading UI scripts');
            // Determine which layout to use based on configuration
            const layoutType = this.config.appearance && this.config.appearance.layout_type
                ? this.config.appearance.layout_type
                : 'dialog'; // Default to dialog if not specified
            log('Using layout type:', layoutType);
            // Load the appropriate UI script based on layout type
            const uiScriptName = layoutType + '-ui.js';
            const uiScript = document.createElement('script');
            uiScript.src = this.options.baseUrl + uiScriptName;
            uiScript.async = true;
            uiScript.onload = () => {
                log(`${layoutType} UI script loaded successfully`);




                  this.showConsentUI(layoutType);
                // After UI is loaded, load detail UI script
                const detailScript = document.createElement('script');
                detailScript.src = this.options.baseUrl + 'detail-ui.js';
                detailScript.async = true;
                detailScript.onload = () => {
                    log('Detail UI script loaded successfully');
                };
                detailScript.onerror = (e) => {
                    console.error('[ConsentMent-Main] Failed to load detail UI script:', e);
                    // Try to continue without detail UI
                };
                document.head.appendChild(detailScript);
            };
            uiScript.onerror = (e) => {
                console.error(`[ConsentMent-Main] Failed to load ${layoutType} UI script:`, e);
                // Fallback to dialog UI if the specified layout script fails to load
                if (layoutType !== 'dialog') {
                    log('Falling back to dialog UI');
                    this.loadFallbackUI('dialog');
                } else {
                    // If even dialog fails, try a basic emergency UI
                    this.showEmergencyUI();
                }
            };
            document.head.appendChild(uiScript);
            log(`UI script ${uiScriptName} added to document`);


            // Show detail icon for toggling detail view
           
        },
        /**
         * Show emergency UI when no other UI could be loaded
         */

        detailScript : function(){
            log('Loading detail UI script loading .........');
        const detailScript = document.createElement('script');
                detailScript.src = this.options.baseUrl + 'detail-ui.js';
                detailScript.async = true;
                detailScript.onload = () => {
                    log('Detail UI script loaded successfully');
                };
                detailScript.onerror = (e) => {
                    console.error('[ConsentMent-Main] Failed to load detail UI script:', e);
                    // Try to continue without detail UI
                };
                document.head.appendChild(detailScript);
            

        },


        showEmergencyUI: function () {
            log('Showing emergency UI due to script loading failures');
            // Create a simple dialog with minimal styling
            const emergencyDialog = document.createElement('div');
            emergencyDialog.style.cssText = `
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 400px;
                max-width: 90%;
                background: white;
                box-shadow: 0 0 10px rgba(0,0,0,0.3);
                z-index: 2147483647;
                font-family: Arial, sans-serif;
                padding: 20px;
                border-radius: 5px;
            `;
            const title = document.createElement('h2');
            title.textContent = 'Privacy Settings';
            title.style.margin = '0 0 15px 0';
            const message = document.createElement('p');
            message.textContent = 'This website uses cookies and similar technologies. Please choose your privacy settings:';
            message.style.marginBottom = '20px';
            const buttonContainer = document.createElement('div');
            buttonContainer.style.cssText = 'display: flex; justify-content: space-between;';
            const rejectButton = document.createElement('button');
            rejectButton.textContent = 'Reject All';
            rejectButton.style.cssText = 'padding: 8px 15px; background: #f1f1f1; border: 1px solid #ccc; border-radius: 3px; cursor: pointer;';
            rejectButton.onclick = () => this.handleEmergencyConsent('denyAll');
            const acceptButton = document.createElement('button');
            acceptButton.textContent = 'Accept All';
            acceptButton.style.cssText = 'padding: 8px 15px; background: #4285F4; color: white; border: none; border-radius: 3px; cursor: pointer;';
            acceptButton.onclick = () => this.handleEmergencyConsent('acceptAll');
            buttonContainer.appendChild(rejectButton);
            buttonContainer.appendChild(acceptButton);
            emergencyDialog.appendChild(title);
            emergencyDialog.appendChild(message);
            emergencyDialog.appendChild(buttonContainer);
            document.body.appendChild(emergencyDialog);
            log('Emergency UI displayed');
        },
        /**
         * Handle consent from emergency UI
         */
        handleEmergencyConsent: function (choice) {
            log('Emergency consent choice:', choice);
            // Generate basic consent data
            const consentData = {
                version: 1,
                timestamp: new Date().toISOString(),
                choice: choice,
                services: {
                    'essential': true,
                    'functional': choice === 'acceptAll',
                    'analytics': choice === 'acceptAll',
                    'marketing': choice === 'acceptAll'
                }
            };
            // Handle consent
            this.handleConsent(consentData);
            // Remove emergency UI
            const emergencyDialog = document.querySelector('div[style*="z-index: 2147483647"]');
            if (emergencyDialog) {
                emergencyDialog.parentNode.removeChild(emergencyDialog);
            }
        },
        /**
         * Load fallback UI in case the primary UI script fails to load
         */
        loadFallbackUI: function (fallbackType = 'dialog') {
            log(`Loading fallback ${fallbackType} UI`);
            const fallbackScript = document.createElement('script');
            fallbackScript.src = this.options.baseUrl + fallbackType + '-ui.js';
            fallbackScript.async = true;
            fallbackScript.onload = () => {
                log(`Fallback ${fallbackType} UI script loaded successfully`);
                this.showConsentUI(fallbackType);
            };
            fallbackScript.onerror = (e) => {
                console.error(`[ConsentMent-Main] Failed to load fallback ${fallbackType} UI script:`, e);
                // If all UIs fail, show emergency UI
                this.showEmergencyUI();
            };
            document.head.appendChild(fallbackScript);
        },
        /**
         * Show the appropriate UI based on layout type
         */
        showConsentUI: function (layoutType) {
            const renderMethodName = 'render' + this.capitalizeFirstLetter(layoutType) + 'UI';
            if (typeof this[renderMethodName] === 'function') {
                log(`Rendering ${layoutType} UI`);
                this[renderMethodName](this.config);
            } else {
                console.error(`[ConsentMent-Main] ${layoutType} UI renderer not available`);
                // If not the default layout and renderer not available, try to fall back
                if (layoutType !== 'dialog') {
                    this.loadFallbackUI('dialog');
                } else {
                    // Last resort: show emergency UI
                    this.showEmergencyUI();
                }
            }
        },
        /**
         * Helper method to capitalize first letter for method name construction
         */
        capitalizeFirstLetter: function (string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        },
        /**
         * Initialize TCF API if needed
         */
        initializeTcfApi: function () {
            log('Initializing TCF API');
            // Check if TCF is enabled in config
            const tcfEnabled = this.config && this.config.integrations &&
                this.config.integrations.tcf &&
                this.config.integrations.tcf.enabled;
            if (!tcfEnabled) {
                log('TCF integration not enabled in configuration');
                return;
            }
            log('Setting up TCF API');
            // Create the TCF API
            window.__tcfapi = this.tcfApi = function (command, version, callback, parameter) {
                log(`TCF API called: ${command}, v${version}`, { parameter });
                // Define default TC data based on current consent
                const tcData = {
                    tcString: '', // Will be generated properly in production
                    gdprApplies: true, // This should be determined based on user's region
                    purpose: {
                        consents: {},
                        legitimateInterests: {}
                    },
                    vendor: {
                        consents: {},
                        legitimateInterests: {}
                    },
                    eventStatus: 'tcloaded',
                    cmpStatus: 'loaded',
                    cmpId: 123, // Your assigned CMP ID for production
                    cmpVersion: 1,
                    isServiceSpecific: true,
                    useNonStandardStacks: false,
                    publisherCC: 'DE', // Publisher country code, should be dynamic
                    purposeOneTreatment: false
                };
                // Fill in purpose consents based on our consent model
                if (this.consent && this.consent.services) {
                    // Example mapping (would need proper integration in production)
                    tcData.purpose.consents = {
                        1: true, // Always allow information storage (necessary)
                        2: !!this.consent.services.functional, // Basic functionality 
                        3: !!this.consent.services.functional, // Personalized content
                        4: !!this.consent.services.analytics, // Select content
                        5: !!this.consent.services.analytics, // Technical measurement
                        6: !!this.consent.services.analytics, // Cross-site measurement
                        7: !!this.consent.services.marketing, // Advertising performance
                        8: !!this.consent.services.marketing, // Detailed profiles
                        9: !!this.consent.services.marketing, // Market research
                        10: !!this.consent.services.marketing // Product development
                    };
                    // Would need proper vendor mapping for production
                    // This is just a placeholder for vendors like Google
                    tcData.vendor.consents = {
                        1: !!this.consent.services.marketing, // Google
                        2: !!this.consent.services.marketing, // Other vendor
                        // etc.
                    };
                }
                // Handle TCF commands
                switch (command) {
                    case 'getTCData':
                        log('TCF: Providing TC data');
                        callback(tcData, true);
                        break;
                    case 'addEventListener':
                        log('TCF: Adding event listener');
                        // In production, would store callbacks to notify on consent changes
                        callback(tcData, true);
                        break;
                    case 'removeEventListener':
                        log('TCF: Removing event listener');
                        // In production, would remove stored callbacks
                        callback(true);
                        break;
                    default:
                        log(`TCF: Unsupported command: ${command}`);
                        callback(null, false);
                }
            }.bind(this);
            log('TCF API initialized successfully');
        },
        /**
         * Handle consent being given
         */
        handleConsent: function (consentData) {
            log('Consent given:', consentData);
            // Store consent data
            this.consent = {
                version: consentData.version || 1,
                timestamp: consentData.timestamp || new Date().toISOString(),
                choice: consentData.choice,
                services: consentData.services
            };
            // Save to localStorage and cookie for resilience
            this.saveConsent();
            // Send analytics data
            this.sendAnalytics();
            // Call the loader callback if it exists
            if (typeof this.onConsentChanged === 'function') {
                log('Calling consent changed callback');
                this.onConsentChanged(this.consent);
            } else {
                // If no callback is set (older implementation), unblock scripts directly
                log('No consent callback registered, unblocking scripts directly');
                this.unblockScripts();
            }
        },
        /**
         * Save consent to localStorage and cookie for redundancy
         */
        saveConsent: function () {
            try {
                log('Saving consent to localStorage and cookie');
                // Serialize consent data
                const consentStr = JSON.stringify(this.consent);
                // Save to localStorage
                try {
                    localStorage.setItem('consentment_consent', consentStr);
                    log('Consent saved to localStorage');
                } catch (e) {
                    console.error('[ConsentMent-Main] Error saving to localStorage:', e);
                }
                // Also save to cookie as backup with 1 year expiration
                const expiryDate = new Date();
                expiryDate.setFullYear(expiryDate.getFullYear() + 1);
                // Format cookie with expiration and path
                const cookieValue = `consentment_consent=${encodeURIComponent(consentStr)}; expires=${expiryDate.toUTCString()}; path=/; SameSite=Lax`;
                // Set the cookie
                document.cookie = cookieValue;
                log('Consent saved to cookie');
            } catch (e) {
                console.error('[ConsentMent-Main] Error saving consent:', e);
            }
        },
        /**
         * Send analytics data to server
         */
        sendAnalytics: function () {
            log('Sending analytics data');
            const data = {
                settings_id: this.options.settingsId, // Make sure this is the actual configuration ID
                consent: this.consent,
                timestamp: new Date().toISOString(),
                user_agent: navigator.userAgent,
                scriptRegistry: {
                    blocked: this.scriptRegistry.blocked.length,
                    executed: this.scriptRegistry.executed.length
                },
                cookieRegistry: {
                    blocked: Object.keys(this.cookieRegistry.blocked).length,
                    allowed: Object.keys(this.cookieRegistry.allowed).length
                }
            };
            log('Analytics payload:', data);
            fetch(this.options.apiBaseUrl + '/consent/analytics', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Analytics submission failed: ' + response.status);
                    }
                    return response.json();
                })
                .then(result => {
                    log('Analytics data sent successfully:', result);
                })
                .catch(error => {
                    console.error('[ConsentMent-Main] Error sending analytics data:', error);
                });
        },
        /**
         * Unblock scripts based on consent
         */
        unblockScripts: function () {
            log('Unblocking approved scripts based on consent');
            // The actual unblocking is now primarily handled by the loader callback
            // but we keep this as a fallback for compatibility
            // Find all script elements that were blocked
            const scripts = document.querySelectorAll('script[data-blocked-src]');
            log(`Found ${scripts.length} blocked scripts in DOM`);
            scripts.forEach(script => {
                const src = script.getAttribute('data-blocked-src');
                log('Processing blocked script:', src);
                // Check if we can load this script based on consent
                if (this.canLoadScript(src)) {
                    log('Unblocking script:', src);
                    // Create a new script element
                    const newScript = document.createElement('script');
                    newScript.src = src;
                    // Copy other attributes
                    Array.from(script.attributes).forEach(attr => {
                        if (attr.name !== 'data-blocked-src' && attr.name !== 'data-blocked-id' && attr.name !== 'type') {
                            newScript.setAttribute(attr.name, attr.value);
                        }
                    });
                    // Track script execution
                    this.scriptRegistry.executed.push({
                        src: src,
                        unblocked: new Date().toISOString()
                    });
                    // Replace the blocked script with the new one
                    script.parentNode.replaceChild(newScript, script);
                    log('Script successfully unblocked:', src);
                } else {
                    log('Script remains blocked due to consent settings:', src);
                }
            });
            log('Script unblocking complete');
            // Also handle blocked cookies if possible
            this.processBlockedCookies();
        },
        /**
         * Process cookies that were blocked but now might be allowed
         */
        processBlockedCookies: function () {
            log('Processing previously blocked cookies');
            // Go through blocked cookies and set them if allowed now
            const blockedCookies = this.cookieRegistry.blocked;
            let cookiesSet = 0;
            for (const cookieName in blockedCookies) {
                if (this.canSetCookie(cookieName)) {
                    log('Setting previously blocked cookie:', cookieName);
                    // Get the original cookie string
                    const cookieStr = blockedCookies[cookieName].cookieString;
                    if (cookieStr) {
                        // Set the cookie
                        document.cookie = cookieStr;
                        // Move from blocked to allowed
                        this.cookieRegistry.allowed[cookieName] = {
                            value: blockedCookies[cookieName].value,
                            allowed: new Date().toISOString(),
                            previouslyBlocked: true
                        };
                        delete this.cookieRegistry.blocked[cookieName];
                        cookiesSet++;
                    }
                }
            }
            log(`Processed blocked cookies: ${cookiesSet} now allowed`);
        },
        /**
         * Check if a script can be loaded based on current consent
         */
        canLoadScript: function (scriptUrl) {
            log('Checking if script can be loaded:', scriptUrl);
            // If no consent choices are stored, block the script
            if (!this.consent || !this.consent.services) {
                log('No consent data available, script blocked');
                return false;
            }
            // Map script URL patterns to service categories
            const scriptCategories = {
                // Analytics
                'google-analytics': 'analytics',
                'googletagmanager': 'analytics',
                'gtm.js': 'analytics',
                'analytics': 'analytics',
                'matomo': 'analytics',
                'piwik': 'analytics',
                'hotjar': 'analytics',
                'clarity.ms': 'analytics',
                'stats': 'analytics',
                'segment': 'analytics',
                // Marketing
                'facebook.net': 'marketing',
                'fb.js': 'marketing',
                'twitter': 'marketing',
                'doubleclick.net': 'marketing',
                'ads': 'marketing',
                'amazon-adsystem': 'marketing',
                'pixel': 'marketing',
                'advertising': 'marketing',
                'adsbygoogle.js': 'marketing',
                'criteo': 'marketing',
                'taboola': 'marketing',
                'outbrain': 'marketing',
                'linkedin': 'marketing',
                // Functional
                'tracking': 'functional',
                'livechat': 'functional',
                'intercom': 'functional',
                'zendesk': 'functional',
                'freshdesk': 'functional',
                'uservoice': 'functional',
                'optimize': 'functional',
                'recaptcha': 'functional'
            };
            // Check if the script is from the same domain as the page
            const currentDomain = window.location.hostname;
            let scriptDomain = '';
            try {
                const urlObj = new URL(scriptUrl);
                scriptDomain = urlObj.hostname;
                // If it's the same domain, it's probably essential
                if (scriptDomain === currentDomain) {
                    log('Script is from same domain, categorizing as essential');
                    return true;
                }
            } catch (e) {
                // If URL parsing fails, continue with pattern matching
                log('Error parsing script URL, continuing with pattern matching', e);
            }
            // Find which category this script belongs to
            let category = 'functional'; // Default category
            for (const pattern in scriptCategories) {
                if (scriptUrl.indexOf(pattern) !== -1) {
                    category = scriptCategories[pattern];
                    log(`Script matched pattern "${pattern}", categorized as: ${category}`);
                    break;
                }
            }
            // Check if consent was given for this category
            const isAllowed = this.consent.services[category] === true;
            log(`Script ${isAllowed ? 'allowed' : 'blocked'} based on ${category} consent: ${this.consent.services[category]}`);
            return isAllowed;
        },
        /**
         * Check if a cookie can be set based on current consent
         */
        canSetCookie: function (cookieName) {
            log('Checking if cookie can be set:', cookieName);
            // If no consent choices are stored, block the cookie
            if (!this.consent || !this.consent.services) {
                log('No consent data available, cookie blocked');
                return false;
            }
            // Special case for own consent cookie
            if (cookieName === 'consentment_consent' || cookieName.indexOf('consentment_') === 0) {
                log('ConsentMent cookie, always allowed');
                return true;
            }
            // Essential cookies
            const essentialCookies = [
                'PHPSESSID',
                'JSESSIONID',
                'ASP.NET_SessionId',
                'CSRF-TOKEN',
                'XSRF-TOKEN',
                'wordpress_',
                'wp-',
                'session',
                'csrf',
                'token'
            ];
            for (const pattern of essentialCookies) {
                if (cookieName.indexOf(pattern) === 0) {
                    log(`Cookie matched essential pattern "${pattern}", always allowed`);
                    return true;
                }
            }
            // Map cookie patterns to service categories
            const cookieCategories = {
                // Analytics cookies
                '_ga': 'analytics',
                '_gid': 'analytics',
                '_gat': 'analytics',
                '_hj': 'analytics',
                'matomo': 'analytics',
                'piwik': 'analytics',
                'clarity': 'analytics',
                'stats': 'analytics',
                // Marketing cookies
                '_fbp': 'marketing',
                '_fbc': 'marketing',
                'fr': 'marketing',
                'guest_id': 'marketing',
                'personalization_id': 'marketing',
                'DoubleClick': 'marketing',
                'google_experiment': 'marketing',
                'NID': 'marketing',
                'IDE': 'marketing',
                // Functional cookies
                'intercom': 'functional',
                'zendesk': 'functional',
                'freshdesk': 'functional',
                'livechat': 'functional',
                'uservoice': 'functional',
                'recaptcha': 'functional',
                'consent': 'functional'
            };
            // Find which category this cookie belongs to
            let category = 'functional'; // Default category
            for (const pattern in cookieCategories) {
                if (cookieName.indexOf(pattern) !== -1) {
                    category = cookieCategories[pattern];
                    log(`Cookie matched pattern "${pattern}", categorized as: ${category}`);
                    break;
                }
            }
            // Check if consent was given for this category
            const isAllowed = this.consent.services[category] === true;
            log(`Cookie ${isAllowed ? 'allowed' : 'blocked'} based on ${category} consent: ${this.consent.services[category]}`);
            return isAllowed;
        },
        /**
         * Toggle between consent UI and detail views
         */
        toggleDetailView: function (show) {
            log(`Toggling detail view: ${show ? 'show' : 'hide'}`);
            // Find all possible UI wrappers
            const layoutTypes = ['dialog', 'bar', 'wall', 'banner'];
            // Find the detail wrapper
            const detailWrapper = document.querySelector('.cmp-wrapper.cmp.detail');
            if (show) {
                // Hide all layout UIs and show detail UI
                layoutTypes.forEach(type => {
                    const wrapper = document.querySelector(`.cmp-wrapper.cmp.${type}`);
                    if (wrapper) {
                        wrapper.style.display = 'none';
                        log(`${type} UI hidden`);
                    }
                });
                if (detailWrapper) {
                    detailWrapper.style.display = 'block';
                    log('Detail UI shown');
                } else {
                    log('Warning: Detail UI wrapper not found');
                }
            } else {
                // Show the correct layout UI based on config and hide detail UI
                if (detailWrapper) {
                    detailWrapper.style.display = 'none';
                    log('Detail UI hidden');
                }
                const layoutType = this.config.appearance && this.config.appearance.layout_type
                    ? this.config.appearance.layout_type
                    : 'dialog';
                const activeWrapper = document.querySelector(`.cmp-wrapper.cmp.${layoutType}`);
                if (activeWrapper) {
                    activeWrapper.style.display = 'block';
                    log(`${layoutType} UI shown`);
                } else {
                    log(`Warning: ${layoutType} UI wrapper not found`);
                }
            }
        },

        /**
        Detail Icon visible bottom left corner
        */
        showDetailIcon: function (options) {
            log('Showing detail icon');
            // Create a floating icon element
            const icon = document.createElement('div');
            icon.className = 'cmp-detail-icon';
            icon.style.cssText = `
                position: fixed;
                bottom: 20px;
                left: 20px;
                width: 50px;
                height: 50px;
                color: white;
                border-radius: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                z-index: 2147483647; /* Ensure it is above other content */
            `;
            icon.innerHTML ="<img src='" + options.baseUrl + "../../img/fingerprint.png' alt='Details' style='width: 50px; height: 50px; color:white;'>"; // Use an icon image


                this.options = options || this.options; // Use provided options or default
                this.loadConfig().then(() => {

                    log('Configuration loaded successfully');
                   
                });
                

            const self = this; // Keep reference to main instance
    

            icon.addEventListener('click', () => {
                
             

                    if (!document.querySelector('.cmp-wrapper.cmp.detail')) {

                        window.ConsentMent.renderDetailUI(self.config);


                    } else {

                        // If already rendered, just toggle visibility

                        window.ConsentMent.toggleDetailView(true);

                    }


            



            });
            document.body.appendChild(icon);
            log('Detail icon added to the page');
        }


    };
    log('Main script loaded and ready');
})();





/**
 * Override UI selection based on device type
 * This function runs after the original main script is loaded
 */

(function () {
    // Keep a reference to the original loadConsentUI function
    const originalLoadConsentUI = window.ConsentMent.loadConsentUI;

               

    // Override the loadConsentUI function
    window.ConsentMent.loadConsentUI = function () {
        // Check if the device is mobile
        const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        if (isMobile) {
            console.log('[ConsentMent-Main] Mobile device detected. Forcing bar UI regardless of configuration.');
            // Force 'bar' layout type for mobile
            if (this.config && this.config.appearance) {
                this.config.appearance.layout_type = 'bar';
            }
        }
        // Call the original function with the potentially modified config
        return originalLoadConsentUI.call(this);
    };

    console.log('[ConsentMent-Main] Device-based UI override installed');
})();
