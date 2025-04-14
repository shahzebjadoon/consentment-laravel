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
                    console.log('[ConsentMent-Main] Configuration loaded, loading wall UI');
                    this.loadWallUI();
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
         * Load the Wall UI
         */
        loadWallUI: function() {
            console.log('[ConsentMent-Main] Loading UI scripts');
            
            // Load wall UI script
            const wallScript = document.createElement('script');
            wallScript.src = this.options.baseUrl + 'wall-ui.js';
            wallScript.async = true;
            
            wallScript.onload = () => {
                console.log('[ConsentMent-Main] Wall UI script loaded');
                this.showWallUI();
                
                // After wall UI is loaded, load detail UI script
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
            
            wallScript.onerror = () => {
                console.error('[ConsentMent-Main] Failed to load wall UI script');
            };
            
            document.head.appendChild(wallScript);
        },
        
        /**
         * Show the Wall UI
         */
        showWallUI: function() {
            if (typeof this.renderWallUI === 'function') {
                console.log('[ConsentMent-Main] Rendering wall UI');
                this.renderWallUI(this.config);
            } else {
                console.error('[ConsentMent-Main] Wall UI renderer not available');
            }
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
         * Toggle between wall and detail views
         */
        toggleDetailView: function(show) {
            const wallWrapper = document.querySelector('.cmp-wrapper.cmp.first');
            const detailWrapper = document.querySelector('.cmp-wrapper.cmp.detail');
            
            if (show) {
                // Hide wall UI and show detail UI
                if (wallWrapper) wallWrapper.style.display = 'none';
                if (detailWrapper) detailWrapper.style.display = 'block';
            } else {
                // Show wall UI and hide detail UI
                if (wallWrapper) wallWrapper.style.display = 'block';
                if (detailWrapper) detailWrapper.style.display = 'none';
            }
        }
    };
    
    console.log('[ConsentMent-Main] Main script loaded and ready');
})();