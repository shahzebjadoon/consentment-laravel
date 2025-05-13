/**

 * ConsentMent CMP Loader

 * Enhanced script and cookie blocking with Google Consent Mode integration

 * Version: 2.0.0

 */

(function() {

    'use strict';

    

    // Debug mode - set to false in production

    const DEBUG = true;

    

    // Logger function that respects debug mode

    const log = function(message, data) {

        if (DEBUG) {

            if (data) {

                console.log('[ConsentMent-Loader] ' + message, data);

            } else {

                console.log('[ConsentMent-Loader] ' + message);

            }

        }

    };

    

    log('Initializing enhanced loader...');

    

    // Store original methods that we'll override

    const originalCreateElement = document.createElement;

    const originalAppendChild = Node.prototype.appendChild;

    const originalInsertBefore = Node.prototype.insertBefore;

    const originalWrite = document.write;

    const originalWriteln = document.writeln;

    

    // Script registry to track blocked scripts

    const blockedScripts = [];

    const executedScripts = [];

    

    // Cookie registry to track cookies

    const cookieRegistry = {

        allowed: {},

        blocked: {}

    };

    

    // Initialize state

    let hasConsent = false;

    let consentData = null;

    let settingsId = null;

    let baseUrl = null;

    

    // Get script tag that loaded this loader

    const getLoaderScriptTag = function() {

        log('Finding loader script tag');

        const currentScript = document.currentScript || (function() {

            const scripts = document.getElementsByTagName('script');

            return scripts[scripts.length - 1];

        })();

        

        // Validate the script tag

        if (!currentScript) {

            log('ERROR: Unable to find loader script tag');

            return null;

        }

        

        // Extract configuration from script tag

        settingsId = currentScript.getAttribute('data-settings-id');

        if (!settingsId) {

            log('ERROR: Missing settings ID attribute');

            return null;

        }

        

        log('Settings ID:', settingsId);

        

        // Get base URL for loading other scripts

        baseUrl = currentScript.src.substring(0, currentScript.src.lastIndexOf('/') + 1);

        log('Base URL:', baseUrl);

        

        return currentScript;

    };

    

    // Check if consent is already given in storage

    const checkExistingConsent = function() {

        try {

            log('Checking for existing consent');

            

            // Try localStorage first

            const consentStr = localStorage.getItem('consentment_consent');

            if (consentStr) {

                const storedConsent = JSON.parse(consentStr);

                

                // Validate stored consent

                if (storedConsent && storedConsent.version && 

                    storedConsent.timestamp && storedConsent.services) {

                    

                    // Check if consent is still valid (not expired)

                    const consentDate = new Date(storedConsent.timestamp);

                    const now = new Date();

                    const daysDiff = (now - consentDate) / (1000 * 60 * 60 * 24);

                    

                    // Consent expires after 365 days (or configure as needed)

                    if (daysDiff < 365) {

                        log('Valid consent found:', storedConsent);

                        return storedConsent;

                    } else {

                        log('Expired consent found, will request new consent');

                        return null;

                    }

                }

            }

            

            // Try cookie fallback if localStorage isn't available

            const allCookies = document.cookie.split(';');

            for (let i = 0; i < allCookies.length; i++) {

                const cookie = allCookies[i].trim();

                if (cookie.indexOf('consentment_consent=') === 0) {

                    try {

                        const cookieValue = decodeURIComponent(cookie.substring('consentment_consent='.length));

                        const storedConsent = JSON.parse(cookieValue);

                        

                        if (storedConsent && storedConsent.version && 

                            storedConsent.timestamp && storedConsent.services) {

                            log('Valid consent found in cookie:', storedConsent);

                            return storedConsent;

                        }

                    } catch (e) {

                        log('Error parsing consent cookie:', e);

                    }

                    break;

                }

            }

        } catch (e) {

            log('Error reading consent data:', e);

        }

        

        return null;

    };

    

    // Override document.cookie property with custom getter/setter

    const setupCookieBlocking = function() {

        log('Setting up cookie blocking');

        

        let documentCookieDescriptor = Object.getOwnPropertyDescriptor(Document.prototype, 'cookie') || 

                                       Object.getOwnPropertyDescriptor(HTMLDocument.prototype, 'cookie');

        

        if (documentCookieDescriptor && documentCookieDescriptor.configurable) {

            // Save the original cookie getter and setter

            const originalCookieGetter = documentCookieDescriptor.get;

            const originalCookieSetter = documentCookieDescriptor.set;

            

            // Override cookie getter/setter

            Object.defineProperty(document, 'cookie', {

                get: function() {

                    // Call original getter

                    return originalCookieGetter.call(document);

                },

                set: function(cookieStr) {

                    // Check if consent is needed for this cookie

                    const cookieParts = cookieStr.split(';');

                    const nameValue = cookieParts[0].trim().split('=');

                    

                    if (nameValue.length >= 2) {

                        const cookieName = nameValue[0].trim();

                        const cookieValue = nameValue[1].trim();

                        

                        // Special case: allow the consent cookie itself

                        if (cookieName === 'consentment_consent') {

                            log('Allowing consent cookie:', cookieName);

                            return originalCookieSetter.call(document, cookieStr);

                        }

                        

                        // Check if cookie requires consent

                        if (requiresConsent(cookieName, 'cookie')) {

                            // Check if we have appropriate consent

                            if (!hasConsent || !canSetCookie(cookieName)) {

                                log('Blocking cookie:', cookieName);

                                

                                // Track the blocked cookie

                                cookieRegistry.blocked[cookieName] = {

                                    value: cookieValue,

                                    blocked: new Date().toISOString(),

                                    cookieString: cookieStr

                                };

                                

                                // Don't set the cookie

                                return cookieStr;

                            }

                        }

                        

                        // If consent not required or consent given, set the cookie

                        log('Allowing cookie:', cookieName);

                        

                        // Track the allowed cookie

                        cookieRegistry.allowed[cookieName] = {

                            value: cookieValue,

                            allowed: new Date().toISOString()

                        };

                    }

                    

                    // Call original setter

                    return originalCookieSetter.call(document, cookieStr);

                },

                enumerable: true,

                configurable: true

            });

            

            log('Cookie blocking set up successfully');

        } else {

            log('Warning: Unable to override document.cookie - cookie blocking limited');

        }

    };

    

    // Setup localStorage and sessionStorage interception

    const setupStorageBlocking = function() {

        log('Setting up storage blocking');

        

        // Override localStorage

        const originalLocalStorageSetItem = Storage.prototype.setItem;

        

        Storage.prototype.setItem = function(key, value) {

            // Special case: allow the consent storage

            if (key === 'consentment_consent' || key.indexOf('consentment_') === 0) {

                return originalLocalStorageSetItem.call(this, key, value);

            }

            

            // Check if storage requires consent

            if (requiresConsent(key, 'storage')) {

                // Check if we have appropriate consent

                if (!hasConsent || !canSetStorage(key)) {

                    log('Blocking localStorage item:', key);

                    return;

                }

            }

            

            log('Allowing localStorage item:', key);

            return originalLocalStorageSetItem.call(this, key, value);

        };

        

        log('Storage blocking set up successfully');

    };

    

    // Override script creation and insertion methods

    const setupScriptBlocking = function() {

        log('Setting up enhanced script blocking');

        

        // Override document.createElement

        document.createElement = function(tagName) {

            const element = originalCreateElement.apply(document, arguments);

            

            if (tagName.toLowerCase() === 'script') {

                // Add a custom handler to the script element

                const originalSetAttribute = element.setAttribute;

                const originalGetAttribute = element.getAttribute;

                const originalSetProperty = Object.getOwnPropertyDescriptor(HTMLScriptElement.prototype, 'src').set;

                

                // Override setAttribute to catch src changes

                element.setAttribute = function(name, value) {

                    if (name === 'src') {

                        log('Script src attribute being set:', value);

                        

                        // Check if the script requires consent

                        if (requiresConsent(value, 'script')) {

                            log('Script requires consent:', value);

                            

                            // Check if we have consent for this script

                            if (!hasConsent || !canLoadScript(value)) {

                                log('Blocking script via setAttribute:', value);

                                

                                // Store the script URL as a data attribute instead

                                const scriptId = 'blocked-script-' + blockedScripts.length;

                                

                                blockedScripts.push({

                                    id: scriptId,

                                    src: value,

                                    element: element,

                                    method: 'setAttribute',

                                    blocked: new Date().toISOString()

                                });

                                

                                // Set data attributes instead of src

                                originalSetAttribute.call(this, 'data-blocked-src', value);

                                originalSetAttribute.call(this, 'data-blocked-id', scriptId);

                                originalSetAttribute.call(this, 'type', 'text/plain');

                                return;

                            }

                        }

                    }

                    

                    return originalSetAttribute.apply(this, arguments);

                };

                

                // Override src property

                Object.defineProperty(element, 'src', {

                    get: function() {

                        // Return the blocked src if it exists

                        const blockedSrc = originalGetAttribute.call(this, 'data-blocked-src');

                        if (blockedSrc) {

                            return blockedSrc;

                        }

                        // Otherwise return the normal src

                        return originalGetAttribute.call(this, 'src');

                    },

                    set: function(value) {

                        log('Script src property being set:', value);

                        

                        // Check if the script requires consent

                        if (requiresConsent(value, 'script')) {

                            log('Script requires consent:', value);

                            

                            // Check if we have consent for this script

                            if (!hasConsent || !canLoadScript(value)) {

                                log('Blocking script via property:', value);

                                

                                // Store the script URL as a data attribute instead

                                const scriptId = 'blocked-script-' + blockedScripts.length;

                                

                                blockedScripts.push({

                                    id: scriptId,

                                    src: value,

                                    element: element,

                                    method: 'property',

                                    blocked: new Date().toISOString()

                                });

                                

                                // Set data attributes instead of src

                                originalSetAttribute.call(this, 'data-blocked-src', value);

                                originalSetAttribute.call(this, 'data-blocked-id', scriptId);

                                originalSetAttribute.call(this, 'type', 'text/plain');

                                return;

                            }

                        }

                        

                        // If consent not required or consent given, set the src

                        return originalSetProperty.call(this, value);

                    },

                    enumerable: true,

                    configurable: true

                });

            }

            

            return element;

        };

        

        // Override appendChild to catch direct script insertions

        Node.prototype.appendChild = function(node) {

            // Only intercept script elements

            if (node.nodeName && node.nodeName.toLowerCase() === 'script' && node.src) {

                const scriptSrc = node.src;

                log('Script being appended:', scriptSrc);

                

                // Check if this script requires consent

                if (requiresConsent(scriptSrc, 'script')) {

                    log('Appended script requires consent:', scriptSrc);

                    

                    // Check if we have consent

                    if (!hasConsent || !canLoadScript(scriptSrc)) {

                        log('Blocking script append:', scriptSrc);

                        

                        // Create a placeholder element

                        const placeholder = originalCreateElement.call(document, 'script');

                        const scriptId = 'blocked-script-' + blockedScripts.length;

                        

                        blockedScripts.push({

                            id: scriptId,

                            src: scriptSrc,

                            element: node,

                            method: 'appendChild',

                            blocked: new Date().toISOString(),

                            parent: this

                        });

                        

                        placeholder.setAttribute('data-blocked-src', scriptSrc);

                        placeholder.setAttribute('data-blocked-id', scriptId);

                        placeholder.setAttribute('type', 'text/plain');

                        

                        // Return the placeholder instead of the actual script

                        return originalAppendChild.call(this, placeholder);

                    }

                }

            }

            

            // Default behavior for non-script nodes or allowed scripts

            return originalAppendChild.call(this, node);

        };

        

        // Override insertBefore to catch scripts inserted that way

        Node.prototype.insertBefore = function(node, referenceNode) {

            // Only intercept script elements

            if (node.nodeName && node.nodeName.toLowerCase() === 'script' && node.src) {

                const scriptSrc = node.src;

                log('Script being inserted:', scriptSrc);

                

                // Check if this script requires consent

                if (requiresConsent(scriptSrc, 'script')) {

                    log('Inserted script requires consent:', scriptSrc);

                    

                    // Check if we have consent

                    if (!hasConsent || !canLoadScript(scriptSrc)) {

                        log('Blocking script insert:', scriptSrc);

                        

                        // Create a placeholder element

                        const placeholder = originalCreateElement.call(document, 'script');

                        const scriptId = 'blocked-script-' + blockedScripts.length;

                        

                        blockedScripts.push({

                            id: scriptId,

                            src: scriptSrc,

                            element: node,

                            method: 'insertBefore',

                            blocked: new Date().toISOString(),

                            parent: this,

                            referenceNode: referenceNode

                        });

                        

                        placeholder.setAttribute('data-blocked-src', scriptSrc);

                        placeholder.setAttribute('data-blocked-id', scriptId);

                        placeholder.setAttribute('type', 'text/plain');

                        

                        // Return the placeholder instead of the actual script

                        return originalInsertBefore.call(this, placeholder, referenceNode);

                    }

                }

            }

            

            // Default behavior for non-script nodes or allowed scripts

            return originalInsertBefore.call(this, node, referenceNode);

        };

        

        // Override document.write to catch inline scripts

        document.write = function(markup) {

            if (markup && typeof markup === 'string' && markup.indexOf('<script') !== -1) {

                log('Potential script in document.write detected');

                

                // Simple pattern matching for script tags

                const scriptRegex = /<script\b[^>]*>([\s\S]*?)<\/script>/gi;

                let match;

                let modifiedMarkup = markup;

                

                // Check each script tag in the markup

                while ((match = scriptRegex.exec(markup)) !== null) {

                    let scriptTag = match[0];

                    

                    // Extract script source if present

                    const srcMatch = scriptTag.match(/src\s*=\s*["']([^"']+)["']/i);

                    if (srcMatch && srcMatch[1]) {

                        const scriptSrc = srcMatch[1];

                        

                        // Check if this script requires consent

                        if (requiresConsent(scriptSrc, 'script')) {

                            log('Script in document.write requires consent:', scriptSrc);

                            

                            // Check if we have consent

                            if (!hasConsent || !canLoadScript(scriptSrc)) {

                                log('Blocking script in document.write:', scriptSrc);

                                

                                // Replace script tag with a placeholder

                                const scriptId = 'blocked-script-' + blockedScripts.length;

                                

                                blockedScripts.push({

                                    id: scriptId,

                                    src: scriptSrc,

                                    method: 'document.write',

                                    originalTag: scriptTag,

                                    blocked: new Date().toISOString()

                                });

                                

                                const replacementTag = `<script type="text/plain" data-blocked-src="${scriptSrc}" data-blocked-id="${scriptId}"></script>`;

                                modifiedMarkup = modifiedMarkup.replace(scriptTag, replacementTag);

                            }

                        }

                    }

                }

                

                // Use the potentially modified markup

                return originalWrite.call(document, modifiedMarkup);

            }

            

            // Default behavior for non-script content

            return originalWrite.call(document, markup);

        };

        

        // Also override document.writeln with similar logic

        document.writeln = function(markup) {

            return document.write(markup + '\n');

        };

        

        log('Enhanced script blocking set up successfully');

    };

    

    // Setup a MutationObserver to catch dynamically inserted scripts

    const setupMutationObserver = function() {

        log('Setting up MutationObserver');

        

        // Create a MutationObserver instance

        const observer = new MutationObserver(function(mutations) {

            mutations.forEach(function(mutation) {

                // Check for added nodes

                if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {

                    Array.from(mutation.addedNodes).forEach(function(node) {

                        // Check if the node is a script element

                        if (node.nodeName && node.nodeName.toLowerCase() === 'script' && node.src) {

                            const scriptSrc = node.src;

                            

                            // Skip if this is one of our own scripts

                            if (scriptSrc.indexOf(baseUrl) === 0) {

                                return;

                            }

                            

                            log('Script detected via MutationObserver:', scriptSrc);

                            

                            // Check if this script was already processed

                            const alreadyProcessed = blockedScripts.some(s => s.src === scriptSrc) || 

                                                     executedScripts.some(s => s.src === scriptSrc);

                            

                            if (alreadyProcessed) {

                                log('Script already processed, skipping:', scriptSrc);

                                return;

                            }

                            

                            // Check if this script requires consent

                            if (requiresConsent(scriptSrc, 'script')) {

                                log('MutationObserver script requires consent:', scriptSrc);

                                

                                // Check if we have consent

                                if (!hasConsent || !canLoadScript(scriptSrc)) {

                                    log('Blocking script via MutationObserver:', scriptSrc);

                                    

                                    // Create a placeholder element

                                    const scriptId = 'blocked-script-' + blockedScripts.length;

                                    

                                    blockedScripts.push({

                                        id: scriptId,

                                        src: scriptSrc,

                                        element: node,

                                        method: 'MutationObserver',

                                        blocked: new Date().toISOString(),

                                        parent: node.parentNode

                                    });

                                    

                                    // Replace with placeholder

                                    if (node.parentNode) {

                                        const placeholder = originalCreateElement.call(document, 'script');

                                        placeholder.setAttribute('data-blocked-src', scriptSrc);

                                        placeholder.setAttribute('data-blocked-id', scriptId);

                                        placeholder.setAttribute('type', 'text/plain');

                                        

                                        node.parentNode.replaceChild(placeholder, node);

                                    }

                                }

                            } else {

                                // Track executed scripts even if they don't require consent

                                executedScripts.push({

                                    src: scriptSrc,

                                    executed: new Date().toISOString()

                                });

                            }

                        }

                    });

                }

            });

        });

        

        // Start observing the document

        observer.observe(document, {

            childList: true,

            subtree: true

        });

        

        log('MutationObserver set up successfully');

    };

    

    // Check if a script requires consent based on URL pattern

    const requiresConsent = function(url, type) {

        // Default patterns list

        const consentRequiredPatterns = [

            'google-analytics',

            'googletagmanager',

            'gtm.js',

            'analytics',

            'facebook.net',

            'fb.js',

            'twitter',

            'doubleclick.net',

            'pixel',

            'tracking',

            'matomo',

            'piwik',

            'hotjar',

            'clarity.ms',

            'adsbygoogle.js',

            'amazon-adsystem',

            'advertising',

            'ads',

            'criteo',

            'taboola',

            'outbrain',

            'linkedin'

        ];

        

        // If we have dynamic patterns from the server, use those instead

        // This would be populated from the configuration once it's loaded

        const patterns = window.ConsentMent && window.ConsentMent.config && 

                         window.ConsentMent.config.scriptPatterns ? 

                         window.ConsentMent.config.scriptPatterns : consentRequiredPatterns;

        

        // For cookies and storage, we use different logic

        if (type === 'cookie' || type === 'storage') {

            // Skip consent for essential cookies

            const essentialCookiePatterns = [

                'consentment_',

                'csrf',

                'session',

                'PHPSESSID',

                'necessary',

                'essential'

            ];

            

            // Check if this is an essential cookie

            for (let i = 0; i < essentialCookiePatterns.length; i++) {

                if (url.indexOf(essentialCookiePatterns[i]) !== -1) {

                    return false; // This is an essential cookie, no consent required

                }

            }

            

            // All other cookies require consent

            return true;

        }

        

        // For scripts, check against the patterns list

        for (let i = 0; i < patterns.length; i++) {

            if (url.indexOf(patterns[i]) !== -1) {

                return true;

            }

        }

        

        // If no pattern matches, default to not requiring consent

        return false;

    };

    

    // Check if a script can be loaded based on current consent

    const canLoadScript = function(scriptUrl) {

        // If no consent data is stored, block the script

        if (!consentData || !consentData.services) {

            return false;

        }

        

        // Map script URL patterns to service categories

        const scriptCategories = {

            'google-analytics': 'analytics',

            'googletagmanager': 'analytics',

            'gtm.js': 'analytics',

            'facebook.net': 'marketing',

            'fb.js': 'marketing',

            'twitter': 'marketing',

            'doubleclick.net': 'marketing',

            'ads': 'marketing',

            'amazon-adsystem': 'marketing',

            'pixel': 'marketing',

            'analytics': 'analytics',

            'tracking': 'functional',

            'matomo': 'analytics',

            'piwik': 'analytics',

            'hotjar': 'analytics',

            'clarity.ms': 'analytics',

            'adsbygoogle.js': 'marketing',

            'advertising': 'marketing',

            'criteo': 'marketing',

            'taboola': 'marketing',

            'outbrain': 'marketing',

            'linkedin': 'marketing'

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

        return consentData.services[category] === true;

    };

    

    // Check if a cookie can be set based on current consent

    const canSetCookie = function(cookieName) {

        // If no consent data is stored, block the cookie

        if (!consentData || !consentData.services) {

            return false;

        }

        

        // Map cookie patterns to service categories

        const cookieCategories = {

            '_ga': 'analytics',

            '_gid': 'analytics',

            '_gat': 'analytics',

            '_fbp': 'marketing',

            '_fbc': 'marketing',

            'fr': 'marketing',

            'guest_id': 'marketing', // Twitter

            'personalization_id': 'marketing', // Twitter

            '_hjid': 'analytics', // Hotjar

            '_hjIncludedInSample': 'analytics',

            'MUID': 'analytics', // Microsoft

            '_uetsid': 'marketing', // Microsoft UET

            'NID': 'marketing', // Google

            'IDE': 'marketing', // Google DoubleClick

            'test_cookie': 'marketing', // DoubleClick

            'GPS': 'analytics', // YouTube

            'VISITOR_INFO1_LIVE': 'analytics', // YouTube

            'YSC': 'analytics', // YouTube

            'APISID': 'marketing', // Google

            'SSID': 'marketing', // Google

            'HSID': 'marketing', // Google

            'SID': 'marketing' // Google

        };

        

        // Find which category this cookie belongs to

        let category = 'functional'; // Default category

        for (const pattern in cookieCategories) {

            if (cookieName.indexOf(pattern) === 0) {

                category = cookieCategories[pattern];

                break;

            }

        }

        

        // Check if consent was given for this category

        return consentData.services[category] === true;

    };

    

    // Similar check for storage operations

    const canSetStorage = function(key) {

        // Storage operations generally use the same consent as cookies

        return canSetCookie(key);

    };

    

    // Initialize Google Consent Mode

    const setupGoogleConsentMode = function() {

        log('Setting up Google Consent Mode');

        

        // Initialize dataLayer if it doesn't exist

        window.dataLayer = window.dataLayer || [];

        

        // Google Consent Mode v2 - Default all to denied

        window.dataLayer.push(['consent', 'default', {

            ad_storage: 'denied',

            analytics_storage: 'denied',

            functionality_storage: 'denied',

            personalization_storage: 'denied',

            security_storage: 'granted' // This is always granted as it's essential

        }]);

        

        log('Google Consent Mode initialized with default denied state');

    };

    

    // Update Google Consent Mode based on user consent

    const updateGoogleConsentMode = function() {

        log('Updating Google Consent Mode with consent data');

        

        if (!consentData || !consentData.services) {

            log('No consent data available, keeping default denied state');

            return;

        }

        

        // Map our service categories to Google Consent Mode categories

        const googleConsent = {

            ad_storage: consentData.services.marketing === true ? 'granted' : 'denied',

            analytics_storage: consentData.services.analytics === true ? 'granted' : 'denied',

            functionality_storage: consentData.services.functional === true ? 'granted' : 'denied',

            personalization_storage: consentData.services.marketing === true ? 'granted' : 'denied',

            security_storage: 'granted' // Always granted

        };

        

        // Update Google Consent Mode

        window.dataLayer = window.dataLayer || [];

        window.dataLayer.push(['consent', 'update', googleConsent]);

        

        log('Google Consent Mode updated:', googleConsent);

    };

    

    // Load the main script

    const loadMainScript = function() {

        log('Loading main script');

        

        // Create script element

        const script = originalCreateElement.call(document, 'script');

        script.async = true;

        script.src = baseUrl + 'main.js';

        

        // When main script loads, initialize it

        script.onload = function() {

            log('Main script loaded, initializing ConsentMent');

            

            // Check if CMP was successfully loaded

            if (window.ConsentMent) {

                // Register consent update callback

                window.ConsentMent.onConsentChanged = function(newConsent) {

                    log('Consent changed callback triggered');

                    

                    // Update our consent data

                    consentData = newConsent;

                    hasConsent = true;

                    

                    // Update Google Consent Mode

                    updateGoogleConsentMode();

                    

                    // Unblock scripts based on new consent

                    unblockScripts();

                };

                

                // Initialize the main CMP

                window.ConsentMent.init({

                    settingsId: settingsId,

                    baseUrl: baseUrl,

                    // apiBaseUrl: 'https://app.consentment.com',
                    apiBaseUrl:' http://127.0.0.1:8000',

                    existingConsent: consentData,

                    cookieRegistry: cookieRegistry,

                    scriptRegistry: {

                        blocked: blockedScripts,

                        executed: executedScripts

                    }

                });

            } else {

                log('ERROR: Main script failed to initialize ConsentMent object');

            }

        };

        

        // Handle loading errors

        script.onerror = function() {

            log('ERROR: Failed to load main script');

        };

        

        // Add script to head

        originalAppendChild.call(document.head, script);

        log('Main script tag added to document head');

    };

    

    // Unblock scripts based on consent

    const unblockScripts = function() {

        log('Unblocking approved scripts');

        

        // Process all blocked scripts

        for (let i = 0; i < blockedScripts.length; i++) {

            const blockedScript = blockedScripts[i];

            

            // Check if we can now load this script based on consent

            if (canLoadScript(blockedScript.src)) {

                log('Unblocking script:', blockedScript.src);

                

                // Create a new script element

                const newScript = originalCreateElement.call(document, 'script');

                newScript.src = blockedScript.src;

                

                // Mark as executed

                executedScripts.push({

                    id: blockedScript.id,

                    src: blockedScript.src,

                    unblocked: new Date().toISOString(),

                    originallyBlockedAt: blockedScript.blocked

                });

                

                // Handle different insertion methods

                switch (blockedScript.method) {

                    case 'appendChild':

                        if (blockedScript.parent && blockedScript.parent.nodeType === Node.ELEMENT_NODE) {

                            // Get the placeholder element

                            const placeholder = document.querySelector(`script[data-blocked-id="${blockedScript.id}"]`);

                            if (placeholder && placeholder.parentNode) {

                                // Replace the placeholder with the new script

                                placeholder.parentNode.replaceChild(newScript, placeholder);

                            } else {

                                // If placeholder is gone, just append to the original parent

                                originalAppendChild.call(blockedScript.parent, newScript);

                            }

                        }

                        break;

                        

                    case 'insertBefore':

                        if (blockedScript.parent && blockedScript.parent.nodeType === Node.ELEMENT_NODE) {

                            // Get the placeholder and reference node

                            const placeholder = document.querySelector(`script[data-blocked-id="${blockedScript.id}"]`);

                            let referenceNode = blockedScript.referenceNode;

                            

                            // If the reference node is no longer in the DOM, use the next sibling of the placeholder

                            if (!referenceNode || !referenceNode.parentNode) {

                                if (placeholder && placeholder.nextSibling) {

                                    referenceNode = placeholder.nextSibling;

                                }

                            }

                            

                            if (placeholder && placeholder.parentNode && referenceNode) {

                                // Replace with insertBefore to maintain order

                                originalInsertBefore.call(placeholder.parentNode, newScript, referenceNode);

                                placeholder.parentNode.removeChild(placeholder);

                            } else if (placeholder && placeholder.parentNode) {

                                // If no valid reference node, just replace

                                placeholder.parentNode.replaceChild(newScript, placeholder);

                            }

                        }

                        break;

                        

                    case 'setAttribute':

                    case 'property':

                        if (blockedScript.element) {

                            // For these methods, we update the existing element

                            const placeholder = document.querySelector(`script[data-blocked-id="${blockedScript.id}"]`);

                            if (placeholder && placeholder.parentNode) {

                                placeholder.parentNode.replaceChild(newScript, placeholder);

                            }

                        }

                        break;

                        

                    case 'document.write':

                        // For document.write, we need to recreate the script tag since the document has already been modified

                        // This is more complex and less reliable - might need another approach

                        log('Warning: Unblocking scripts inserted via document.write is less reliable');

                        

                        // Append to head as a fallback

                        originalAppendChild.call(document.head, newScript);

                        break;

                        

                    case 'MutationObserver':

                        // For MutationObserver, get the placeholder and replace it

                        const placeholder = document.querySelector(`script[data-blocked-id="${blockedScript.id}"]`);

                        if (placeholder && placeholder.parentNode) {

                            placeholder.parentNode.replaceChild(newScript, placeholder);

                        } else if (blockedScript.parent) {

                            // Fallback: append to the original parent

                            originalAppendChild.call(blockedScript.parent, newScript);

                        } else {

                            // Last resort: append to head

                            originalAppendChild.call(document.head, newScript);

                        }

                        break;

                        

                    default:

                        // Unknown method, append to head

                        log('Warning: Unknown insertion method, appending to head:', blockedScript.method);

                        originalAppendChild.call(document.head, newScript);

                }

                

                // Remove from blocked scripts array (by marking as processed)

                blockedScript.processed = true;

            }

        }

        

        // Clean up the blocked scripts array

        const remainingBlocked = blockedScripts.filter(script => !script.processed);

        blockedScripts.length = 0;

        remainingBlocked.forEach(script => blockedScripts.push(script));

        

        log('Script unblocking complete. Remaining blocked scripts:', blockedScripts.length);

    };

    

    // Main initialization function

    const initialize = function() {

        // Get script tag and configuration

        const scriptTag = getLoaderScriptTag();

        if (!scriptTag) {

            log('ERROR: Failed to initialize - missing script tag or settings');

            return;

        }

        

        // Check for existing consent

        consentData = checkExistingConsent();

        hasConsent = (consentData !== null);

        

        // Setup Google Consent Mode

        setupGoogleConsentMode();

        

        // If we have consent already, update Google Consent Mode

        if (hasConsent) {

            updateGoogleConsentMode();

        }

        

        // Setup cookie blocking

        setupCookieBlocking();

        

        // Setup local storage blocking

        setupStorageBlocking();

        

        // Setup script blocking

        setupScriptBlocking();

        

        // Setup mutation observer

        setupMutationObserver();

        

        // Load main script

        loadMainScript();

        

        // If consent was already given, unblock scripts right away

        if (hasConsent) {

            log('Consent already exists, unblocking appropriate scripts');

            unblockScripts();

        }

        

        log('Initialization complete');

    };

    

    // Start initialization

    initialize();

})();

                

