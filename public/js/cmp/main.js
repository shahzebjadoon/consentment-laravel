/**
 * ConsentMent CMP Main Script
 * This is the main script that handles consent management
 * Version: 1.0.0
 */
(function() {
    console.log('[ConsentMent-Main] Main script initializing...');
    
    // Define the global ConsentMent object
    window.ConsentMent = {
        config: null,
        consent: {},
        options: {},
        
        /**
         * Initialize the CMP
         */
        init: function(options) {
            console.log('[ConsentMent-Main] Initializing with options:', options);
            this.options = options;
            
            // Load existing consent if available
            if (options.existingConsent) {
                console.log('[ConsentMent-Main] Using existing consent:', options.existingConsent);
                this.consent = options.existingConsent;
                // No need to show UI if consent is already given
                this.unblockScripts();
                return;
            }
            
            // Load configuration and then display UI
            this.loadConfig()
                .then(() => {
                    console.log('[ConsentMent-Main] Configuration loaded, loading consent UI');
                    this.loadConsentUI();
                })
                .catch(error => {
                    console.error('[ConsentMent-Main] Error loading configuration:', error);
                });
        },
        
        /**
         * Load configuration from server
         */
        loadConfig: function() {
            const url = this.options.apiBaseUrl + '/consent/config/' + this.options.settingsId;
            console.log('[ConsentMent-Main] Loading configuration from:', url);
            
            return fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to load configuration: ' + response.status);
                    }
                    return response.json();
                })
                .then(config => {
                    console.log('[ConsentMent-Main] Configuration loaded successfully:', config);
                    this.config = config;
                    return config;
                });
        },
        
        /**
         * Load the appropriate UI based on layout_type from configuration
         */
        loadConsentUI: function() {
            console.log('[ConsentMent-Main] Loading UI scripts');
            
            // Determine which layout to use based on configuration
            const layoutType = this.config.appearance && this.config.appearance.layout_type 
                ? this.config.appearance.layout_type 
                : 'dialog'; // Default to dialog if not specified
            
            console.log('[ConsentMent-Main] Using layout type:', layoutType);
            
            // Load the appropriate UI script based on layout type
            const uiScriptName = layoutType + '-ui.js';
            const uiScript = document.createElement('script');
            uiScript.src = this.options.baseUrl + uiScriptName;
            uiScript.async = true;
            
            uiScript.onload = () => {
                console.log(`[ConsentMent-Main] ${layoutType} UI script loaded`);
                this.showConsentUI(layoutType);
                
                // After UI is loaded, load detail UI script
                const detailScript = document.createElement('script');
                detailScript.src = this.options.baseUrl + 'detail-ui.js';
                detailScript.async = true;
                
                detailScript.onload = () => {
                    console.log('[ConsentMent-Main] Detail UI script loaded');
                };
                
                detailScript.onerror = () => {
                    console.error('[ConsentMent-Main] Failed to load detail UI script');
                };
                
                document.head.appendChild(detailScript);
            };
            
            uiScript.onerror = () => {
                console.error(`[ConsentMent-Main] Failed to load ${layoutType} UI script`);
                // Fallback to dialog UI if the specified layout script fails to load
                if (layoutType !== 'dialog') {
                    console.log('[ConsentMent-Main] Falling back to dialog UI');
                    this.loadFallbackUI('dialog');
                }
            };
            
            document.head.appendChild(uiScript);
        },
        
        /**
         * Load fallback UI in case the primary UI script fails to load
         */
        loadFallbackUI: function(fallbackType) {
            const fallbackScript = document.createElement('script');
            fallbackScript.src = this.options.baseUrl + fallbackType + '-ui.js';
            fallbackScript.async = true;
            
            fallbackScript.onload = () => {
                console.log(`[ConsentMent-Main] Fallback ${fallbackType} UI script loaded`);
                this.showConsentUI(fallbackType);
            };
            
            fallbackScript.onerror = () => {
                console.error(`[ConsentMent-Main] Failed to load fallback ${fallbackType} UI script`);
            };
            
            document.head.appendChild(fallbackScript);
        },
        
        /**
         * Show the appropriate UI based on layout type
         */
        showConsentUI: function(layoutType) {
            const renderMethodName = 'render' + this.capitalizeFirstLetter(layoutType) + 'UI';
            
            if (typeof this[renderMethodName] === 'function') {
                console.log(`[ConsentMent-Main] Rendering ${layoutType} UI`);
                this[renderMethodName](this.config);
            } else {
                console.error(`[ConsentMent-Main] ${layoutType} UI renderer not available`);
                
                // If not the default layout and renderer not available, try to fall back
                if (layoutType !== 'dialog') {
                    this.loadFallbackUI('dialog');
                }
            }
        },
        
        /**
         * Helper method to capitalize first letter for method name construction
         */
        capitalizeFirstLetter: function(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        },
        
        /**
         * Handle consent being given
         */
        handleConsent: function(consentData) {
            console.log('[ConsentMent-Main] Consent given:', consentData);
            
            // Store consent data
            this.consent = {
                version: 1,
                timestamp: new Date().toISOString(),
                choice: consentData.choice,
                services: consentData.services
            };
            
            // Save to localStorage
            this.saveConsent();
            
            // Send analytics data
            this.sendAnalytics();
            
            // Unblock scripts based on consent
            this.unblockScripts();
        },
        
        /**
         * Save consent to localStorage
         */
        saveConsent: function() {
            try {
                console.log('[ConsentMent-Main] Saving consent to localStorage');
                localStorage.setItem('consentment_consent', JSON.stringify(this.consent));
            } catch (e) {
                console.error('[ConsentMent-Main] Error saving to localStorage:', e);
            }
        },
        
        /**
         * Send analytics data to server
         */
        sendAnalytics: function() {
            console.log('[ConsentMent-Main] Sending analytics data');
            
            const data = {
                settings_id: this.options.settingsId,
                consent: this.consent
            };
            
            fetch(this.options.apiBaseUrl + '/consent/analytics', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                console.log('[ConsentMent-Main] Analytics data sent successfully:', result);
            })
            .catch(error => {
                console.error('[ConsentMent-Main] Error sending analytics data:', error);
            });
        },
        
        /**
         * Unblock scripts based on consent
         */
        unblockScripts: function() {
            console.log('[ConsentMent-Main] Unblocking approved scripts');
            
            // Find all script elements that were blocked
            const scripts = document.querySelectorAll('script[data-blocked-src]');
            
            scripts.forEach(script => {
                const src = script.getAttribute('data-blocked-src');
                console.log('[ConsentMent-Main] Found blocked script:', src);
                
                // Check if we can load this script based on consent
                if (this.canLoadScript(src)) {
                    console.log('[ConsentMent-Main] Unblocking script:', src);
                    
                    // Create a new script element
                    const newScript = document.createElement('script');
                    newScript.src = src;
                    
                    // Copy other attributes
                    Array.from(script.attributes).forEach(attr => {
                        if (attr.name !== 'data-blocked-src' && attr.name !== 'type') {
                            newScript.setAttribute(attr.name, attr.value);
                        }
                    });
                    
                    // Replace the blocked script with the new one
                    script.parentNode.replaceChild(newScript, script);
                } else {
                    console.log('[ConsentMent-Main] Script remains blocked due to consent settings:', src);
                }
            });
        },
        
        /**
         * Check if a script can be loaded based on current consent
         */
        canLoadScript: function(scriptUrl) {
            // If no consent choices are stored, block the script
            if (!this.consent.services) {
                return false;
            }
            
            // Map script URL patterns to service categories
            const scriptCategories = {
                'google-analytics': 'analytics',
                'googletagmanager': 'analytics',
                'facebook.net': 'marketing',
                'doubleclick.net': 'marketing',
                'pixel': 'marketing',
                'tracking': 'functional'
            };
            
            // Find which category this script belongs to
            let category = 'functional'; // Default category
            for (const pattern in scriptCategories) {
                if (scriptUrl.indexOf(pattern) !== -1) {
                    category = scriptCategories[pattern];
                    break;
                }
            }
            
            // Check if consent was given for this category
            return this.consent.services[category] === true;
        },
        
        /**
         * Toggle between consent UI and detail views
         */
        toggleDetailView: function(show) {
            // Find all possible UI wrappers
            const layoutTypes = ['dialog', 'bar', 'wall', 'banner'];
            
            // Find the detail wrapper
            const detailWrapper = document.querySelector('.cmp-wrapper.cmp.detail');
            
            if (show) {
                // Hide all layout UIs and show detail UI
                layoutTypes.forEach(type => {
                    const wrapper = document.querySelector(`.cmp-wrapper.cmp.${type}`);
                    if (wrapper) wrapper.style.display = 'none';
                });
                
                if (detailWrapper) detailWrapper.style.display = 'block';
            } else {
                // Show the correct layout UI based on config and hide detail UI
                if (detailWrapper) detailWrapper.style.display = 'none';
                
                const layoutType = this.config.appearance && this.config.appearance.layout_type 
                    ? this.config.appearance.layout_type 
                    : 'dialog';
                
                const activeWrapper = document.querySelector(`.cmp-wrapper.cmp.${layoutType}`);
                if (activeWrapper) activeWrapper.style.display = 'block';
            }
        }
    };
    
    console.log('[ConsentMent-Main] Main script loaded and ready');
})();