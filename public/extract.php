<?php
/**
 * Improved Cookie Database Extractor
 * 
 * This script extracts the complete database from cookiedatabase.org API
 * Focuses on getting ALL services, not just the ones we search for
 */

// Basic configuration
$apiBaseUrl = 'https://cookiedatabase.org/wp-json/cookiedatabase/v1/';
$language = 'en';
$outputDir = 'cookie_data';
$csvFile = 'cookie_database.csv';

// Create output directory
if (!file_exists($outputDir)) {
    mkdir($outputDir, 0755, true);
}

// Set up logging
$logFile = "$outputDir/extraction_log.txt";
file_put_contents($logFile, "Improved Cookie Database Extraction started: " . date('Y-m-d H:i:s') . "\n\n");

// Helper function to log messages
function log_message($message, $logFile) {
    $timeStamp = date('Y-m-d H:i:s');
    echo "[$timeStamp] $message\n";
    file_put_contents($logFile, "[$timeStamp] $message\n", FILE_APPEND);
}

// Helper function to make GET requests
function makeGetRequest($url, $logFile) {
    log_message("Making GET request to: $url", $logFile);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
    
    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        $errorMsg = curl_error($ch);
        curl_close($ch);
        throw new Exception("Curl error: $errorMsg");
    }
    
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode >= 400) {
        throw new Exception("HTTP error: $httpCode for URL: $url");
    }
    
    $data = json_decode($response);
    if (!$data) {
        throw new Exception("Failed to decode JSON from URL: $url");
    }
    
    return $data;
}

// Helper function to make POST requests
function makePostRequest($url, $postData, $logFile) {
    log_message("Making POST request to: $url", $logFile);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($postData)
    ]);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
    
    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        $errorMsg = curl_error($ch);
        curl_close($ch);
        throw new Exception("Curl error: $errorMsg");
    }
    
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode >= 400) {
        throw new Exception("HTTP error: $httpCode for URL: $url");
    }
    
    $data = json_decode($response);
    if (!$data) {
        throw new Exception("Failed to decode JSON from URL: $url");
    }
    
    return $data;
}

// Helper function to map service types to categories
function mapServiceTypeToCategory($serviceType, $serviceTypeNames) {
    if (empty($serviceType) || !isset($serviceTypeNames[$serviceType])) {
        return 'other';
    }
    
    $typeName = strtolower($serviceTypeNames[$serviceType]);
    
    // Mapping based on service type names
    $mapping = [
        'statistics' => 'analytics',
        'analytic' => 'analytics',
        'analytics' => 'analytics',
        'marketing' => 'marketing',
        'advertisement' => 'marketing',
        'advertising' => 'marketing',
        'functional' => 'functional',
        'functionality' => 'functional',
        'necessary' => 'essential',
        'essential' => 'essential',
        'required' => 'essential',
        'social media' => 'social_media',
        'social' => 'social_media',
        'social share' => 'social_media'
    ];
    
    foreach ($mapping as $key => $value) {
        if (strpos($typeName, $key) !== false) {
            return $value;
        }
    }
    
    return 'other';
}

// Helper function to generate UUID
function generateUUID() {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

try {
    // 1. Open CSV file for writing
    $csvHandle = fopen("$outputDir/$csvFile", 'w');
    
    // Write CSV header
    $header = ['ID', 'Platform', 'Category', 'Cookie / Data Key name', 'Domain', 
               'Description', 'Retention period', 'Data Controller', 
               'User Privacy & GDPR Rights Portals', 'Wildcard match'];
    fputcsv($csvHandle, $header);
    
    // 2. Get service types
    log_message("Getting service types...", $logFile);
    $serviceTypesUrl = $apiBaseUrl . "servicetypes/$language";
    $serviceTypesResponse = makeGetRequest($serviceTypesUrl, $logFile);
    
    if (!isset($serviceTypesResponse->data) || !is_object($serviceTypesResponse->data)) {
        throw new Exception("Invalid service types response structure");
    }
    
    // Create a lookup for service type names by ID
    $serviceTypeNames = [];
    foreach ($serviceTypesResponse->data as $id => $name) {
        $serviceTypeNames[$id] = $name;
    }
    
    file_put_contents("$outputDir/service_types.json", json_encode($serviceTypeNames, JSON_PRETTY_PRINT));
    log_message("Retrieved " . count($serviceTypeNames) . " service types.", $logFile);
    
    // 3. Get cookie purposes
    log_message("Getting cookie purposes...", $logFile);
    $cookiePurposesUrl = $apiBaseUrl . "cookiepurposes/$language";
    $cookiePurposesResponse = makeGetRequest($cookiePurposesUrl, $logFile);
    
    if (!isset($cookiePurposesResponse->data) || !is_object($cookiePurposesResponse->data)) {
        throw new Exception("Invalid cookie purposes response structure");
    }
    
    $cookiePurposes = [];
    foreach ($cookiePurposesResponse->data as $id => $name) {
        $cookiePurposes[$id] = $name;
    }
    
    file_put_contents("$outputDir/cookie_purposes.json", json_encode($cookiePurposes, JSON_PRETTY_PRINT));
    log_message("Retrieved " . count($cookiePurposes) . " cookie purposes.", $logFile);
    
    // 4. Get a comprehensive list of services using a special approach
    log_message("Fetching COMPLETE list of services...", $logFile);
    
    // First, try to get all services at once by using wildcards 
    $servicesUrl = $apiBaseUrl . "services";
    $allServices = [];
    $csvRows = 0;
    
    // Attempt to get all services with a wildcard query
    $wildcardQueries = ["%", "*", "a*", "b*", "c*", "d*", "e*", "f*", "g*", "h*", "i*", "j*", "k*", "l*", 
                      "m*", "n*", "o*", "p*", "q*", "r*", "s*", "t*", "u*", "v*", "w*", "x*", "y*", "z*"];
    
    foreach ($wildcardQueries as $query) {
        try {
            log_message("Trying to get all services with query: '$query'", $logFile);
            $postData = json_encode([$language => [$query]]);
            $response = makePostRequest($servicesUrl, $postData, $logFile);
            
            if (isset($response->data) && isset($response->data->$language)) {
                $servicesData = $response->data->$language;
                
                log_message("Found " . count((array)$servicesData) . " services with query: '$query'", $logFile);
                
                // Process each service
                foreach ($servicesData as $serviceName => $serviceData) {
                    if (!isset($serviceData->service)) {
                        continue;
                    }
                    
                    $service = $serviceData->service;
                    $serviceId = isset($service->ID) ? $service->ID : generateUUID();
                    
                    // Skip if we already have this service
                    if (isset($allServices[$serviceId])) {
                        continue;
                    }
                    
                    // Get service type/category
                    $serviceTypeId = isset($service->serviceTypeID) ? $service->serviceTypeID : null;
                    $category = mapServiceTypeToCategory($serviceTypeId, $serviceTypeNames);
                    
                    // Get domain
                    $domain = isset($service->domain) && !empty($service->domain) 
                        ? $service->domain 
                        : strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $serviceName)) . '.com';
                    
                    // Get privacy URL
                    $privacyUrl = isset($service->privacyStatementURL) ? $service->privacyStatementURL : '';
                    
                    // Save service in our collection
                    $allServices[$serviceId] = [
                        'id' => $serviceId,
                        'name' => $serviceName,
                        'typeId' => $serviceTypeId,
                        'category' => $category,
                        'domain' => $domain,
                        'description' => isset($service->serviceDescription) ? $service->serviceDescription : '',
                        'provider' => isset($service->serviceProvider) ? $service->serviceProvider : '',
                        'privacyUrl' => $privacyUrl,
                        'cookies' => isset($serviceData->cookies) ? $serviceData->cookies : []
                    ];
                    
                    // Write service entry to CSV
                    $serviceRow = [
                        $serviceId, // ID
                        $serviceName, // Platform
                        $category, // Category
                        '', // No cookie name for the service entry
                        $domain, // Domain
                        isset($service->serviceDescription) ? $service->serviceDescription : '', // Description
                        '', // No retention period for the service itself
                        isset($service->serviceProvider) ? $service->serviceProvider : '', // Provider
                        $privacyUrl, // Privacy policy URL
                        '0' // Not a wildcard
                    ];
                    
                    fputcsv($csvHandle, $serviceRow);
                    $csvRows++;
                    
                    log_message("Added service: $serviceName", $logFile);
                    
                    // Process cookies if available
                    if (isset($serviceData->cookies) && is_array($serviceData->cookies) && !empty($serviceData->cookies)) {
                        foreach ($serviceData->cookies as $cookie) {
                            if (!isset($cookie->name)) {
                                continue;
                            }
                            
                            $cookieId = isset($cookie->id) ? $cookie->id : generateUUID();
                            $cookieName = $cookie->name;
                            $cookieDomain = isset($cookie->domain) && !empty($cookie->domain) ? $cookie->domain : $domain;
                            
                            // Write cookie entry to CSV
                            $cookieRow = [
                                $cookieId, // ID
                                $serviceName, // Platform
                                $category, // Category
                                $cookieName, // Cookie name
                                $cookieDomain, // Domain
                                isset($cookie->cookieFunction) ? $cookie->cookieFunction : '', // Description
                                isset($cookie->cookieExpiry) ? $cookie->cookieExpiry : '', // Retention period
                                isset($service->serviceProvider) ? $service->serviceProvider : '', // Provider
                                $privacyUrl, // Privacy policy URL
                                isset($cookie->isRegularExpression) && $cookie->isRegularExpression ? '1' : '0' // Wildcard flag
                            ];
                            
                            fputcsv($csvHandle, $cookieRow);
                            $csvRows++;
                        }
                    }
                }
            }
            
            // Add a delay to avoid rate limiting
            sleep(1);
            
        } catch (Exception $e) {
            log_message("Error getting services with query '$query': " . $e->getMessage(), $logFile);
            
            // If rate limited, wait longer
            if (strpos($e->getMessage(), '429') !== false) {
                log_message("Rate limit hit, waiting 30 seconds...", $logFile);
                sleep(30);
            } else {
                sleep(2);
            }
        }
    }
    
    // 5. Fallback: If we didn't get many services, try with more specific searches
    if (count($allServices) < 100) {
        log_message("Not enough services found. Trying with specific service names...", $logFile);
        
        // List of common services to search for specifically
        $specificServices = [
            // Analytics
            'Google Analytics', 'Matomo', 'Hotjar', 'Mixpanel', 'Amplitude', 'Heap', 'Piwik', 'Plausible',
            'Countly', 'Google Tag Manager', 'Segment', 'Kissmetrics', 'Adobe Analytics', 'Cloudflare Analytics',
            'Comscore', 'Hubspot Analytics', 'Yandex Metrica', 'Parse.ly', 'StatCounter', 'Chartbeat',
            
            // Marketing
            'Google Ads', 'Facebook Pixel', 'LinkedIn Insight', 'Twitter Pixel', 'Pinterest Tag', 'AdRoll',
            'Criteo', 'Outbrain', 'Taboola', 'Snapchat Pixel', 'TikTok Pixel', 'Reddit Pixel', 'Microsoft Advertising',
            'Hubspot', 'Marketo', 'Mailchimp', 'Pardot', 'ActiveCampaign', 'GetResponse', 'Campaign Monitor',
            'Constant Contact', 'Sendinblue', 'Omnisend', 'Klaviyo', 'SendGrid', 'Amazon SES',
            
            // Social Media
            'Facebook', 'Twitter', 'LinkedIn', 'Pinterest', 'Instagram', 'YouTube', 'TikTok', 'Snapchat',
            'Reddit', 'Tumblr', 'Vimeo', 'Flickr', 'Discord', 'VK', 'WhatsApp', 'Telegram',
            
            // Functional
            'Shopify', 'WooCommerce', 'Magento', 'BigCommerce', 'PrestaShop', 'OpenCart', 'Wix',
            'Squarespace', 'WordPress', 'Joomla', 'Drupal', 'Webflow', 'Ghost', 'Cloudflare', 'Akamai',
            'Fastly', 'AWS', 'Azure', 'GCP', 'DigitalOcean', 'Heroku', 'Netlify', 'Vercel',
            
            // Payment
            'PayPal', 'Stripe', 'Square', 'Braintree', 'Adyen', 'Authorize.Net', 'Klarna', 'Afterpay',
            'Affirm', 'Apple Pay', 'Google Pay', 'Amazon Pay', 'Alipay', 'Venmo', 'Wise', 'TransferWise',
            
            // CMS/Hosting
            'WordPress', 'Wix', 'Squarespace', 'Shopify', 'Webflow', 'Ghost', 'Drupal', 'Joomla',
            'Magento', 'BigCommerce', 'PrestaShop', 'OpenCart', 'Contentful', 'Sanity', 'Strapi',
            
            // Support/Chat
            'Intercom', 'Zendesk', 'Salesforce', 'Drift', 'Crisp', 'LiveChat', 'Olark', 'Help Scout',
            'Freshchat', 'Tawk.to', 'Zoho Desk', 'Front', 'Tidio', 'SnapEngage', 'Userlike',
            
            // A/B Testing
            'Optimizely', 'VWO', 'Google Optimize', 'AB Tasty', 'Convert', 'Kameleoon', 'SiteSpect',
            'Unbounce', 'LaunchDarkly', 'Split.io', 'GrowthBook', 'Apptimize',
            
            // Consent Management
            'Cookiebot', 'OneTrust', 'TrustArc', 'Usercentrics', 'Osano', 'CookiePro', 'Termly',
            'Iubenda', 'Securiti.ai', 'Quantcast Choice', 'Civic Cookie Control', 'Didomi',
            
            // Security
            'reCAPTCHA', 'hCaptcha', 'Cloudflare', 'Akamai', 'Imperva', 'Sift', 'Forter',
            'Signifyd', 'Shape Security', 'Darktrace', 'Crowdstrike', 'Okta', 'Auth0', 'AWS WAF',
            
            // CRM
            'Salesforce', 'HubSpot', 'Zoho CRM', 'Microsoft Dynamics', 'Pipedrive', 'Freshsales',
            'Agile CRM', 'Monday.com', 'Close', 'Copper', 'Nimble', 'SugarCRM', 'Bitrix24',
            
            // Error Tracking
            'Sentry', 'Bugsnag', 'Rollbar', 'TrackJS', 'LogRocket', 'New Relic', 'Datadog',
            'Honeybadger', 'Raygun', 'AppSignal', 'Airbrake', 'FullStory', 'Loggly'
        ];
        
        foreach ($specificServices as $serviceName) {
            try {
                log_message("Searching for specific service: '$serviceName'", $logFile);
                $postData = json_encode([$language => [$serviceName]]);
                $response = makePostRequest($servicesUrl, $postData, $logFile);
                
                if (isset($response->data) && isset($response->data->$language)) {
                    $servicesData = $response->data->$language;
                    
                    // Process each service (there might be multiple matches)
                    foreach ($servicesData as $foundServiceName => $serviceData) {
                        if (!isset($serviceData->service)) {
                            continue;
                        }
                        
                        $service = $serviceData->service;
                        $serviceId = isset($service->ID) ? $service->ID : generateUUID();
                        
                        // Skip if we already have this service
                        if (isset($allServices[$serviceId])) {
                            continue;
                        }
                        
                        // Process this service (same logic as above)
                        $serviceTypeId = isset($service->serviceTypeID) ? $service->serviceTypeID : null;
                        $category = mapServiceTypeToCategory($serviceTypeId, $serviceTypeNames);
                        
                        $domain = isset($service->domain) && !empty($service->domain) 
                            ? $service->domain 
                            : strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $foundServiceName)) . '.com';
                        
                        $privacyUrl = isset($service->privacyStatementURL) ? $service->privacyStatementURL : '';
                        
                        $allServices[$serviceId] = [
                            'id' => $serviceId,
                            'name' => $foundServiceName,
                            'typeId' => $serviceTypeId,
                            'category' => $category,
                            'domain' => $domain,
                            'description' => isset($service->serviceDescription) ? $service->serviceDescription : '',
                            'provider' => isset($service->serviceProvider) ? $service->serviceProvider : '',
                            'privacyUrl' => $privacyUrl,
                            'cookies' => isset($serviceData->cookies) ? $serviceData->cookies : []
                        ];
                        
                        // Write service entry to CSV
                        $serviceRow = [
                            $serviceId,
                            $foundServiceName,
                            $category,
                            '',
                            $domain,
                            isset($service->serviceDescription) ? $service->serviceDescription : '',
                            '',
                            isset($service->serviceProvider) ? $service->serviceProvider : '',
                            $privacyUrl,
                            '0'
                        ];
                        
                        fputcsv($csvHandle, $serviceRow);
                        $csvRows++;
                        
                        log_message("Added service: $foundServiceName", $logFile);
                        
                        // Process cookies if available
                        if (isset($serviceData->cookies) && is_array($serviceData->cookies) && !empty($serviceData->cookies)) {
                            foreach ($serviceData->cookies as $cookie) {
                                if (!isset($cookie->name)) {
                                    continue;
                                }
                                
                                $cookieId = isset($cookie->id) ? $cookie->id : generateUUID();
                                $cookieName = $cookie->name;
                                $cookieDomain = isset($cookie->domain) && !empty($cookie->domain) ? $cookie->domain : $domain;
                                
                                // Write cookie entry to CSV
                                $cookieRow = [
                                    $cookieId,
                                    $foundServiceName,
                                    $category,
                                    $cookieName,
                                    $cookieDomain,
                                    isset($cookie->cookieFunction) ? $cookie->cookieFunction : '',
                                    isset($cookie->cookieExpiry) ? $cookie->cookieExpiry : '',
                                    isset($service->serviceProvider) ? $service->serviceProvider : '',
                                    $privacyUrl,
                                    isset($cookie->isRegularExpression) && $cookie->isRegularExpression ? '1' : '0'
                                ];
                                
                                fputcsv($csvHandle, $cookieRow);
                                $csvRows++;
                            }
                        }
                    }
                }
                
                // Add a delay to avoid rate limiting
                sleep(1);
                
            } catch (Exception $e) {
                log_message("Error searching for service '$serviceName': " . $e->getMessage(), $logFile);
                
                // If rate limited, wait longer
                if (strpos($e->getMessage(), '429') !== false) {
                    log_message("Rate limit hit, waiting 30 seconds...", $logFile);
                    sleep(30);
                } else {
                    sleep(2);
                }
            }
        }
    }
    
    // Save all services to JSON
    file_put_contents("$outputDir/all_services.json", json_encode($allServices, JSON_PRETTY_PRINT));
    
    // Close CSV file
    fclose($csvHandle);
    
    log_message("Extraction complete!", $logFile);
    log_message("Total services: " . count($allServices), $logFile);
    log_message("Total CSV rows: $csvRows", $logFile);
    log_message("CSV file saved to: $outputDir/$csvFile", $logFile);
    
} catch (Exception $e) {
    log_message("ERROR: " . $e->getMessage(), $logFile);
    
    if (isset($csvHandle) && is_resource($csvHandle)) {
        fclose($csvHandle);
    }
}