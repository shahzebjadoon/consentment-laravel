/**
 * ConsentMent CMP Loader
 * This is the first script loaded and sets up script blocking and loads main.js
 * Version: 1.0.0
 */
(function() {
    console.log('[ConsentMent-Loader] Initializing...');
    
    // Get script tag that loaded this loader
    const currentScript = document.currentScript || (function() {
        console.log('[ConsentMent-Loader] Finding current script');
        const scripts = document.getElementsByTagName('script');
        return scripts[scripts.length - 1];
    })();
    
    // Get settings ID from script tag
    const settingsId = currentScript.getAttribute('data-settings-id');
    if (!settingsId) {
        console.error('[ConsentMent-Loader] ERROR: Missing settings ID attribute');
        return;
    }
    console.log('[ConsentMent-Loader] Settings ID:', settingsId);
    
    // Get base URL for loading other scripts
    const baseUrl = currentScript.src.substring(0, currentScript.src.lastIndexOf('/') + 1);
    console.log('[ConsentMent-Loader] Base URL:', baseUrl);
    
    // Check if consent is already given
    const hasConsent = checkExistingConsent();
    console.log('[ConsentMent-Loader] Existing consent found:', hasConsent);
    
    // Setup script blocking
    setupScriptBlocking();
    
    // Load main script
    loadMainScript(baseUrl, settingsId, hasConsent);
    
    /**
     * Check if consent has already been given
     */
    function checkExistingConsent() {
        try {
            console.log('[ConsentMent-Loader] Checking for existing consent in localStorage');
            const consentData = localStorage.getItem('consentment_consent');
            if (consentData) {
                return JSON.parse(consentData);
            }
        } catch (e) {
            console.error('[ConsentMent-Loader] Error reading localStorage:', e);
        }
        return null;
    }
    
    /**
     * Setup script blocking mechanism
     */
    function setupScriptBlocking() {
        console.log('[ConsentMent-Loader] Setting up script blocking');
        
        // Store original createElement method
        const originalCreateElement = document.createElement;
        
        // Override createElement to intercept script creation
        document.createElement = function(tagName) {
            const element = originalCreateElement.apply(document, arguments);
            
            if (tagName.toLowerCase() === 'script') {
                // Add a custom handler to the script element
                const originalSetAttribute = element.setAttribute;
                element.setAttribute = function(name, value) {
                    if (name === 'src') {
                        console.log('[ConsentMent-Loader] Script detected:', value);
                        // Check if the script requires consent
                        if (requiresConsent(value)) {
                            console.log('[ConsentMent-Loader] Script requires consent:', value);
                            // Check if we have consent
                            const consent = checkExistingConsent();
                            if (!consent || !canLoadScript(consent, value)) {
                                console.log('[ConsentMent-Loader] Blocking script:', value);
                                // Instead of setting src, store it as a data attribute
                                return originalSetAttribute.call(this, 'data-blocked-src', value);
                            }
                        }
                    }
                    return originalSetAttribute.apply(this, arguments);
                };
            }
            
            return element;
        };
        
        console.log('[ConsentMent-Loader] Script blocking set up successfully');
    }
    
    /**
     * Check if a script requires consent
     */
    function requiresConsent(scriptUrl) {
        // List of domains/patterns that require consent
        const consentRequiredPatterns = [
            'google-analytics',
            'googletagmanager',
            'facebook.net',
            'doubleclick.net',
            'analytics',
            'tracking',
            'pixel',
        ];
        
        return consentRequiredPatterns.some(pattern => scriptUrl.indexOf(pattern) !== -1);
    }
    
    /**
     * Check if a script can be loaded based on consent
     */
    function canLoadScript(consent, scriptUrl) {
        // If no consent choices are stored, block the script
        if (!consent.services) {
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
        return consent.services[category] === true;
    }
    
    /**
     * Load the main script
     */
    function loadMainScript(baseUrl, settingsId, existingConsent) {
        console.log('[ConsentMent-Loader] Loading main script');
        
        // Create script element
        const script = document.createElement('script');
        script.async = true;
        script.src = baseUrl + 'main.js';
        
        // When main script loads, initialize it
        script.onload = function() {
            console.log('[ConsentMent-Loader] Main script loaded, initializing ConsentMent');
            // Check if CMP was successfully loaded
            if (window.ConsentMent) {
                window.ConsentMent.init({
                    settingsId: settingsId,
                    baseUrl: baseUrl,
                    apiBaseUrl: 'https://app.consentment.com',
                    existingConsent: existingConsent
                });
            } else {
                console.error('[ConsentMent-Loader] ERROR: Main script failed to initialize ConsentMent object');
            }
        };
        
        // Handle loading errors
        script.onerror = function() {
            console.error('[ConsentMent-Loader] ERROR: Failed to load main script');
        };
        
        // Add script to head
        document.head.appendChild(script);
        console.log('[ConsentMent-Loader] Main script tag added to document head');
    }
})();