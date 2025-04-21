<?php

namespace App\Services;

use App\Models\DpsScan;
use App\Models\DpsLibrary;
use App\Models\Configuration;
use App\Models\DataProcessingServices;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Support\Facades\Log;
use DOMDocument;
use DOMXPath;

class DpsScanner
{
    protected $client;
    protected $knownServices = null;
    protected $cookieJar;
    
    /**
     * Constructor - initialize HTTP client and load known services
     */
    public function __construct()
    {
        // Create a cookie jar to store cookies
        $this->cookieJar = new CookieJar();
        
        $this->client = new Client([
            'timeout' => 30,
            'verify' => false,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                'Accept-Language' => 'en-US,en;q=0.5',
                'Connection' => 'keep-alive',
                'Upgrade-Insecure-Requests' => '1',
                'Cache-Control' => 'max-age=0',
            ],
            'cookies' => $this->cookieJar,
            'allow_redirects' => [
                'max' => 5,
                'track_redirects' => true,
            ],
            'debug' => false
        ]);
        
        // Load services from database
        $this->loadKnownServices();
    }
    
    /**
     * Load known services from database
     */
    protected function loadKnownServices()
    {
        $this->knownServices = [];
        
        // Load from database
        $libraryServices = DpsLibrary::all();
        
        foreach ($libraryServices as $service) {
            $this->knownServices[$service->domain_pattern] = [
                'id' => $service->id,
                'name' => $service->name,
                'category' => $service->category,
                'description' => $service->description,
                'provider_name' => $service->provider_name,
                'provider_url' => $service->provider_url,
                'privacy_policy_url' => $service->privacy_policy_url,
                'script_patterns' => $service->script_patterns,
                'cookie_patterns' => $service->cookie_patterns,
                'data_collected' => $service->data_collected,
                'data_retention' => $service->data_retention,
                'data_sharing' => $service->data_sharing,
            ];
        }
    }
    
    /**
     * Run a scan on the specified domain for the configuration
     *
     * @param \App\Models\Configuration $configuration
     * @return array
     */
    public function scanDomain(Configuration $configuration)
{
    $domain = $configuration->domain;
    
    if (empty($domain)) {
        return [
            'success' => false,
            'message' => 'No domain specified in the configuration',
            'results' => []
        ];
    }
    
    // Ensure domain has protocol
    if (!preg_match('/^https?:\/\//', $domain)) {
        $domain = 'https://' . $domain;
    }
    
    try {
        // Clear any existing 'todo' scans for this configuration
        DpsScan::where('configuration_id', $configuration->id)
               ->where('status', 'todo')
               ->delete();
               
        // Get the main page HTML and track cookies
        $response = $this->client->get($domain, [
            'on_headers' => function (\Psr\Http\Message\ResponseInterface $response) use ($configuration, $domain) {
                // Extract and process Set-Cookie headers
                $this->processCookieHeaders($response, $configuration, $domain);
            }
        ]);
        
        $html = (string) $response->getBody();
        
        // Process the HTML
        $results = $this->processHtml($html, $domain, $configuration);
        
        // Process cookies from the cookie jar
        $this->processCookieJar($configuration, $domain);
        
        // Also check the cookie consent/privacy page if it exists
        $privacyUrls = $this->findPrivacyPages($html, $domain);
        
        foreach ($privacyUrls as $privacyUrl) {
            try {
                $privacyResponse = $this->client->get($privacyUrl, [
                    'on_headers' => function (\Psr\Http\Message\ResponseInterface $response) use ($configuration, $privacyUrl) {
                        // Extract and process Set-Cookie headers from privacy pages
                        $this->processCookieHeaders($response, $configuration, $privacyUrl);
                    }
                ]);
                $privacyHtml = (string) $privacyResponse->getBody();
                $this->processHtml($privacyHtml, $privacyUrl, $configuration, $domain);
                
                // Process cookies after visiting privacy page
                $this->processCookieJar($configuration, $privacyUrl);
            } catch (RequestException $e) {
                Log::error("Error scanning privacy page: " . $e->getMessage());
            }
        }
        
        // Also scan JavaScript files for additional services
        $this->scanJavaScriptFiles($html, $domain, $configuration);
        
        // Add detected services to the DataProcessingServices table
        $this->addDetectedServicesToDPS($configuration);
        
        // Generate the scan report
        $this->generateScanReport($configuration);
        
        // Update display names for all detected services/cookies
        $updatedCount = $this->updateDisplayNames($configuration);
        Log::info("Updated display names for {$updatedCount} services/cookies");
        
        // Add successful completion message
        return [
            'success' => true,
            'message' => 'Domain scan completed successfully',
            'results' => $results
        ];
    } catch (RequestException $e) {
        Log::error("Error scanning domain: " . $e->getMessage());
        return [
            'success' => false,
            'message' => 'Failed to scan domain: ' . $e->getMessage(),
            'results' => []
        ];
    }
}
    
    /**
     * Process Set-Cookie headers from HTTP responses
     * 
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \App\Models\Configuration $configuration
     * @param string $sourceUrl
     */
    protected function processCookieHeaders($response, $configuration, $sourceUrl)
    {
        $setCookieHeaders = $response->getHeader('Set-Cookie');
        $parsedSourceUrl = parse_url($sourceUrl);
        $sourceDomain = $parsedSourceUrl['host'] ?? '';
        
        foreach ($setCookieHeaders as $cookieHeader) {
            // Parse the cookie header
            $cookieData = $this->parseCookieHeader($cookieHeader);
            
            if (!empty($cookieData['name'])) {
                $cookieName = $cookieData['name'];
                $cookieDomain = $cookieData['domain'] ?? $sourceDomain;
                
                // Detect service based on cookie name
                $detectedService = null;
                
                foreach ($this->knownServices as $domainPattern => $service) {
                    // Check if cookie patterns are defined and in the correct format
                    if (isset($service['cookie_patterns']) && !empty($service['cookie_patterns'])) {
                        $patterns = is_array($service['cookie_patterns']) 
                            ? $service['cookie_patterns'] 
                            : json_decode($service['cookie_patterns'], true);
                        
                        if (is_array($patterns)) {
                            foreach ($patterns as $cookiePattern) {
                                if (!empty($cookiePattern) && $this->matchPattern($cookieName, $cookiePattern)) {
                                    $detectedService = $service;
                                    break 2; // Found a match, exit both loops
                                }
                            }
                        }
                    }
                }
                
                if ($detectedService) {
                    // Save detected service
                    $this->saveDetectedService(
                        $configuration,
                        $detectedService['name'],
                        'cookie:' . $cookieName, // Use prefix to indicate it's a cookie
                        $detectedService['category'],
                        $sourceUrl,
                        $detectedService['id']
                    );
                } else {
                    // Unknown cookie - save with domain as identifier
                    $this->saveDetectedService(
                        $configuration,
                        null, // We'll add a placeholder name in saveDetectedService
                        'cookie:' . $cookieName,
                        'other', // Default category
                        $sourceUrl
                    );
                }
                
                Log::debug("Detected cookie from HTTP header: {$cookieName} on domain {$cookieDomain}");
            }
        }
    }
    
    /**
     * Parse a Set-Cookie header into components
     * 
     * @param string $cookieHeader
     * @return array
     */
    protected function parseCookieHeader($cookieHeader)
    {
        $parts = explode(';', $cookieHeader);
        $cookieData = [];
        
        // First part is always name=value
        if (!empty($parts[0])) {
            $nameValue = explode('=', $parts[0], 2);
            if (count($nameValue) === 2) {
                $cookieData['name'] = trim($nameValue[0]);
                $cookieData['value'] = trim($nameValue[1]);
            }
        }
        
        // Process attributes
        for ($i = 1; $i < count($parts); $i++) {
            $part = trim($parts[$i]);
            if (strpos($part, '=') !== false) {
                list($name, $value) = explode('=', $part, 2);
                $cookieData[strtolower(trim($name))] = trim($value);
            } else {
                $cookieData[strtolower($part)] = true;
            }
        }
        
        return $cookieData;
    }
    
    /**
     * Process cookies from the cookie jar
     * 
     * @param \App\Models\Configuration $configuration
     * @param string $sourceUrl
     */
    protected function processCookieJar($configuration, $sourceUrl)
    {
        // Get the host from the source URL
        $parsedUrl = parse_url($sourceUrl);
        $host = $parsedUrl['host'] ?? '';
        
        if (empty($host)) {
            Log::warning("Empty host when processing cookie jar for URL: {$sourceUrl}");
            return;
        }
        
        // Extract cookies for this domain
        // Use the correct method to get cookies from the jar
        // The CookieJar class does not support getting all cookies by domain directly with getCookieByName(null, $host)
        // So we'll get all cookies and filter manually
        $cookies = [];
        foreach ($this->cookieJar as $cookie) {
            if ($cookie->matchesDomain($host)) {
                $cookies[] = $cookie;
            }
        }
        
        if (!empty($cookies)) {
            foreach ($cookies as $cookie) {
                $cookieName = $cookie->getName();
                
                // Skip if we've already recorded this cookie
                $existingCookie = DpsScan::where('configuration_id', $configuration->id)
                    ->where('service_url', 'cookie:' . $cookieName)
                    ->first();
                
                if ($existingCookie) {
                    continue;
                }
                
                // Detect service based on cookie name
                $detectedService = null;
                
                foreach ($this->knownServices as $domainPattern => $service) {
                    // Check if cookie patterns are defined and in the correct format
                    if (isset($service['cookie_patterns']) && !empty($service['cookie_patterns'])) {
                        $patterns = is_array($service['cookie_patterns']) 
                            ? $service['cookie_patterns'] 
                            : json_decode($service['cookie_patterns'], true);
                        
                        if (is_array($patterns)) {
                            foreach ($patterns as $cookiePattern) {
                                if (!empty($cookiePattern) && $this->matchPattern($cookieName, $cookiePattern)) {
                                    $detectedService = $service;
                                    break 2; // Found a match, exit both loops
                                }
                            }
                        }
                    }
                }
                
                if ($detectedService) {
                    // Save detected service
                    $this->saveDetectedService(
                        $configuration,
                        $detectedService['name'],
                        'cookie:' . $cookieName, // Use prefix to indicate it's a cookie
                        $detectedService['category'],
                        $sourceUrl,
                        $detectedService['id']
                    );
                } else {
                    // Unknown cookie - save with domain as identifier
                    $this->saveDetectedService(
                        $configuration,
                        null, // We'll add a placeholder name in saveDetectedService
                        'cookie:' . $cookieName,
                        'other', // Default category
                        $sourceUrl
                    );
                }
                
                Log::debug("Detected cookie from cookie jar: {$cookieName} on domain {$host}");
            }
        }
    }
    
    /**
     * Check if a host is the same domain or subdomain of another
     *
     * @param string $host
     * @param string $domainToCheck
     * @return bool
     */
    protected function isOwnDomainOrSubdomain($host, $domainToCheck)
    {
        // Extract domain parts for comparison
        $hostParts = explode('.', $host);
        $domainParts = explode('.', $domainToCheck);
        
        // Get base domain (last two parts)
        $hostBase = array_slice($hostParts, -2);
        $domainBase = array_slice($domainParts, -2);
        
        // If base domains match, or one is a subdomain of the other
        if (implode('.', $hostBase) === implode('.', $domainBase)) {
            return true;
        }
        
        // Check if one is fully contained in the other (subdomain check)
        return (strpos($host, $domainToCheck) !== false) || (strpos($domainToCheck, $host) !== false);
    }
    
    /**
     * Scan JavaScript files for additional services
     * 
     * @param string $html
     * @param string $domainUrl
     * @param \App\Models\Configuration $configuration
     */
    protected function scanJavaScriptFiles($html, $domainUrl, $configuration)
    {
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $xpath = new DOMXPath($dom);
        
        // Get all script tags with src attribute
        $scriptTags = $xpath->query('//script[@src]');
        foreach ($scriptTags as $script) {
            $src = $script->getAttribute('src');
            
            // Make relative URLs absolute
            $src = $this->makeUrlAbsolute($src, $domainUrl);
            
            // Only scan JavaScript files on the same domain
            $parsedScriptUrl = parse_url($src);
            $parsedDomainUrl = parse_url($domainUrl);

            if (isset($parsedScriptUrl['host']) && 
                $this->isOwnDomainOrSubdomain($parsedScriptUrl['host'], $parsedDomainUrl['host'])) {
                try {
                    $jsResponse = $this->client->get($src);
                    $jsContent = (string) $jsResponse->getBody();
                    
                    // Search for known services in the JavaScript content
                    $this->scanCodeForServices($jsContent, $domainUrl, $configuration);
                    
                    // Also scan for cookie-setting code
                    $this->scanForCookieCode($jsContent, $domainUrl, $configuration);
                } catch (RequestException $e) {
                    Log::error("Error scanning JavaScript file: " . $e->getMessage());
                }
            }
        }
    }
    
    /**
     * Scan for cookie-setting code in JavaScript
     * 
     * @param string $code
     * @param string $domainUrl
     * @param \App\Models\Configuration $configuration
     */
    protected function scanForCookieCode($code, $domainUrl, $configuration)
    {
        // Expanded patterns for cookie setting in JavaScript
        $patterns = [
            // Basic document.cookie patterns
            '/document\.cookie\s*=\s*[\'"]([^=]+)=/i',
            '/document\.cookie\s*=\s*[\'"](.*?)[\'"].*?[+]/i', // Handle concatenation
            
            // Common cookie library function calls
            '/setCookie\s*\(\s*[\'"]([^\'",]+)[\'",]/i',
            '/\.cookie\s*\(\s*[\'"]([^\'",]+)[\'",]/i',
            
            // jQuery cookie plugin
            '/\$\.cookie\s*\(\s*[\'"]([^\'",]+)[\'",]/i',
            '/\$\.cookieStorage\s*\(\s*[\'"]([^\'",]+)[\'",]/i',
            
            // Common JS cookie libraries
            '/Cookies\.set\s*\(\s*[\'"]([^\'",]+)[\'",]/i',
            '/js-cookie[^)]*\.set\s*\(\s*[\'"]([^\'",]+)[\'",]/i',
            
            // CookieConsent and other consent libraries
            '/CookieConsent\.setCookie\s*\(\s*[\'"]([^\'",]+)[\'",]/i',
            '/\.setCookieConsent\s*\(\s*[\'"]([^\'",]+)[\'",]/i',
            
            // LocalStorage used as cookies
            '/localStorage\.setItem\s*\(\s*[\'"]([^\'",]+)[\'",]/i',
            
            // Browser storage API
            '/cookieStore\.set\s*\(\s*{\s*name\s*:\s*[\'"]([^\'",]+)[\'"],/i',
            
            // Generic Storage API
            '/Storage\.set\s*\(\s*[\'"]([^\'",]+)[\'",]/i',
            
            // Other common patterns
            '/createCookie\s*\(\s*[\'"]([^\'",]+)[\'",]/i',
            '/addCookie\s*\(\s*[\'"]([^\'",]+)[\'",]/i',
            '/writeCookie\s*\(\s*[\'"]([^\'",]+)[\'",]/i',
            '/saveCookie\s*\(\s*[\'"]([^\'",]+)[\'",]/i',
            
            // Shopify specific
            '/ShopifyAnalytics\.lib\.setCookies\s*\(\s*[\'"]([^\'",]+)[\'",]/i',
            
            // Google Tag Manager
            '/gtm\.setTags\s*\(\s*[\'"]([^\'",]+)[\'",]/i',
            
            // Common e-commerce platforms
            '/Shopify\.(\w+)\.set\s*\(\s*[\'"]([^\'",]+)[\'",]/i',
            '/WooCommerce\.(\w+)\.setCookie\s*\(\s*[\'"]([^\'",]+)[\'",]/i',
            '/Magento\.Cookies\.set\s*\(\s*[\'"]([^\'",]+)[\'",]/i'
        ];
        
        // Process each pattern
        foreach ($patterns as $pattern) {
            if (preg_match_all($pattern, $code, $matches)) {
                foreach ($matches[1] as $cookieName) {
                    // Skip empty cookie names
                    if (empty(trim($cookieName))) {
                        continue;
                    }
                    
                    // Try to identify the service based on cookie patterns
                    $detectedService = null;
                    
                    foreach ($this->knownServices as $domainPattern => $service) {
                        // Check if cookie patterns are defined and in the correct format
                        if (isset($service['cookie_patterns']) && !empty($service['cookie_patterns'])) {
                            $patterns = is_array($service['cookie_patterns']) 
                                ? $service['cookie_patterns'] 
                                : json_decode($service['cookie_patterns'], true);
                            
                            if (is_array($patterns)) {
                                foreach ($patterns as $cookiePattern) {
                                    if (!empty($cookiePattern) && $this->matchPattern($cookieName, $cookiePattern)) {
                                        $detectedService = $service;
                                        break 2; // Found a match, exit both loops
                                    }
                                }
                            }
                        }
                    }
                    
                    if ($detectedService) {
                        // Save detected service
                        $this->saveDetectedService(
                            $configuration,
                            $detectedService['name'],
                            'cookie:' . $cookieName, // Use prefix to indicate it's a cookie
                            $detectedService['category'],
                            $domainUrl,
                            $detectedService['id']
                        );
                    } else {
                        // Unknown cookie - save with domain as identifier
                        $this->saveDetectedService(
                            $configuration,
                            null, // We'll add a placeholder name in saveDetectedService
                            'cookie:' . $cookieName,
                            'other', // Default category
                            $domainUrl
                        );
                    }
                }
            }
        }
        
        // Check for cookie consent libraries - these often set cookies but in more complex ways
        $consentLibraries = [
            'cookieconsent.js' => 'Cookie Consent',
            'cookiebot' => 'Cookiebot',
            'gdpr-cookie' => 'GDPR Cookie Consent',
            'cookie-law-info' => 'Cookie Law Info',
            'cookieControl' => 'Cookie Control',
            'cookieLaw' => 'Cookie Law', 
            'OneTrust' => 'OneTrust',
            'CookiePro' => 'CookiePro',
            'usercentrics' => 'Usercentrics',
            'cookieNotice' => 'Cookie Notice',
            'cookieyes' => 'CookieYes',
            'iubenda' => 'Iubenda',
            'termly' => 'Termly',
            'complianz' => 'Complianz',
            'cookieHub' => 'CookieHub',
            'CookieScript' => 'CookieScript',
            'ccm19' => 'CCM19'
        ];
        
        foreach ($consentLibraries as $pattern => $name) {
            if (stripos($code, $pattern) !== false) {
                // Found a consent library - save it
                $this->saveDetectedService(
                    $configuration,
                    $name,
                    'tool:' . $pattern, // Use prefix to indicate it's a tool
                    'functional', // Most cookie consent tools are functional
                    $domainUrl
                );
                
                // Log the detection
                Log::debug("Detected cookie consent library: {$name} at {$domainUrl}");
            }
        }
    }
    
    /**
     * Check if a string matches a pattern (supports wildcards)
     * 
     * @param string $string
     * @param string $pattern
     * @return bool
     */
    protected function matchPattern($string, $pattern)
    {
        // Convert wildcard pattern to regex
        $regex = str_replace(['*', '.'], ['.*', '\.'], $pattern);
        return (bool) preg_match('/^' . $regex . '$/i', $string);
    }
    
    /**
     * Makes a relative URL absolute
     * 
     * @param string $url
     * @param string $baseUrl
     * @return string
     */
    protected function makeUrlAbsolute($url, $baseUrl)
    {
        if (strpos($url, 'http') !== 0) {
            if (strpos($url, '//') === 0) {
                // Protocol-relative URL
                $parsedDomain = parse_url($baseUrl);
                $url = $parsedDomain['scheme'] . ':' . $url;
            } else if (strpos($url, '/') === 0) {
                // Absolute path
                $parsedDomain = parse_url($baseUrl);
                $url = $parsedDomain['scheme'] . '://' . $parsedDomain['host'] . $url;
            } else {
                // Relative path
                $url = rtrim($baseUrl, '/') . '/' . $url;
            }
        }
        
        return $url;
    }
    
    /**
     * Scan code for services by matching patterns from library
     * 
     * @param string $code
     * @param string $domainUrl
     * @param \App\Models\Configuration $configuration
     */
    protected function scanCodeForServices($code, $domainUrl, $configuration)
    {
        foreach ($this->knownServices as $domainPattern => $service) {
            if (strpos($code, $domainPattern) !== false) {
                $this->saveDetectedService(
                    $configuration,
                    $service['name'],
                    $domainPattern,
                    $service['category'],
                    $domainUrl,
                    $service['id'] // Pass library ID for reference
                );
            }
            
            // Check for script patterns
            if (isset($service['script_patterns']) && !empty($service['script_patterns'])) {
                $patterns = is_array($service['script_patterns']) 
                    ? $service['script_patterns'] 
                    : json_decode($service['script_patterns'], true);
                
                if (is_array($patterns)) {
                    foreach ($patterns as $pattern) {
                        if (!empty($pattern) && strpos($code, $pattern) !== false) {
                            $this->saveDetectedService(
                                $configuration,
                                $service['name'],
                                $domainPattern,
                                $service['category'],
                                $domainUrl,
                                $service['id'] // Pass library ID for reference
                            );
                            break; // Found a match, no need to check more patterns
                        }
                    }
                }
            }
        }
    }
    
    /**
     * Process HTML content to find third-party services
     *
     * @param string $html
     * @param string $url
     * @param \App\Models\Configuration $configuration
     * @param string|null $sourceDomain
     * @return array
     */
    protected function processHtml($html, $url, $configuration, $sourceDomain = null)
    {
        $results = [];
        $sourceDomain = $sourceDomain ?: $url;
        $currentDomainHost = parse_url($url, PHP_URL_HOST);
        
        // Force encoding to UTF-8
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
        
        // Parse the HTML
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $xpath = new DOMXPath($dom);
        
        // Extract all script tags
        $scriptTags = $xpath->query('//script');
        foreach ($scriptTags as $script) {
            // Check for inline scripts that could contain document.cookie or tracking code
            if (!$script->hasAttribute('src')) {
                $content = $script->textContent;
                $this->scanForCookieCode($content, $url, $configuration);
                $this->scanCodeForServices($content, $url, $configuration);
                continue;
            }
            
            $src = $script->getAttribute('src');
            $src = $this->makeUrlAbsolute($src, $url);
            
            $parsedUrl = parse_url($src);
            
            // Only process external resources
            if (isset($parsedUrl['host']) && 
                !$this->isOwnDomainOrSubdomain(strtolower($parsedUrl['host']), strtolower($currentDomainHost)) &&
                !$this->isOwnDomainOrSubdomain(strtolower($currentDomainHost), strtolower($parsedUrl['host']))) {
                $serviceData = $this->identifyServiceFromUrl($src);
                
                if ($serviceData) {
                    $results[] = $this->saveDetectedService(
                        $configuration,
                        $serviceData['name'],
                        $src,
                        $serviceData['category'],
                        $sourceDomain,
                        $serviceData['id'] // Pass library ID for reference
                    );
                } else {
                    // Unknown external service - mark as unidentified but with a placeholder name
                    $results[] = $this->saveDetectedService(
                        $configuration,
                        null, // We'll add a placeholder name in saveDetectedService
                        $src,
                        'other', // Default category for unknown services
                        $sourceDomain
                    );
                }
            }
        }
        
        // Extract iframes
        $iframes = $xpath->query('//iframe');
        foreach ($iframes as $iframe) {
            if (!$iframe->hasAttribute('src')) continue;
            
            $src = $iframe->getAttribute('src');
            $src = $this->makeUrlAbsolute($src, $url);
            
            $parsedUrl = parse_url($src);
            
            // Only process external resources
            if (isset($parsedUrl['host']) && strtolower($parsedUrl['host']) !== strtolower($currentDomainHost)) {
                $serviceData = $this->identifyServiceFromUrl($src);
                
                if ($serviceData) {
                    $results[] = $this->saveDetectedService(
                        $configuration,
                        $serviceData['name'],
                        $src,
                        $serviceData['category'],
                        $sourceDomain,
                        $serviceData['id'] // Pass library ID for reference
                    );
                } else {
                    // Unknown external service - mark with a placeholder name
                    $results[] = $this->saveDetectedService(
                        $configuration,
                        null,
                        $src,
                        'other',
                        $sourceDomain
                    );
                }
            }
        }
        
        // Extract links to third-party services (exclude CSS files)
        $links = $xpath->query('//link');
        foreach ($links as $link) {
            if (!$link->hasAttribute('href')) continue;
            
            $href = $link->getAttribute('href');
            
            // Skip stylesheet links (CSS files)
            if ($link->hasAttribute('rel') && strtolower($link->getAttribute('rel')) === 'stylesheet') {
                continue;
            }
            
            $href = $this->makeUrlAbsolute($href, $url);
            
            $parsedUrl = parse_url($href);
            
            // Only process external resources
            if (isset($parsedUrl['host']) && strtolower($parsedUrl['host']) !== strtolower($currentDomainHost)) {
                $serviceData = $this->identifyServiceFromUrl($href);
                
                if ($serviceData) {
                    $results[] = $this->saveDetectedService(
                        $configuration,
                        $serviceData['name'],
                        $href,
                        $serviceData['category'],
                        $sourceDomain,
                        $serviceData['id'] // Pass library ID for reference
                    );
                } else {
                    // Unknown external service
                    $results[] = $this->saveDetectedService(
                        $configuration,
                        null,
                        $href,
                        'other',
                        $sourceDomain
                    );
                }
            }
        }
        
        // Extract img tags with third-party domains (skip processing most image files)
        $images = $xpath->query('//img');
        foreach ($images as $img) {
            if (!$img->hasAttribute('src')) continue;
            
            $src = $img->getAttribute('src');
            
            // Skip common image files
            $lowerSrc = strtolower($src);
            if (preg_match('/\.(jpg|jpeg|png|gif|svg|webp)(\?|$)/', $lowerSrc)) {
                continue;
            }
            
            $src = $this->makeUrlAbsolute($src, $url);
            
            $parsedUrl = parse_url($src);
            
            // Only process external resources
            if (isset($parsedUrl['host']) && strtolower($parsedUrl['host']) !== strtolower($currentDomainHost)) {
                $serviceData = $this->identifyServiceFromUrl($src);
                
                if ($serviceData) {
                    $results[] = $this->saveDetectedService(
                        $configuration,
                        $serviceData['name'],
                        $src,
                        $serviceData['category'],
                        $sourceDomain,
                        $serviceData['id'] // Pass library ID for reference
                    );
                } else {
                    // Unknown external service
                    $results[] = $this->saveDetectedService(
                        $configuration,
                        null,
                        $src,
                        'other',
                        $sourceDomain
                    );
                }
            }
        }
        
        // Also check for other resources like video, audio, embed, object
        $otherResources = $xpath->query('//video[@src]|//audio[@src]|//embed[@src]|//object[@data]');
        foreach ($otherResources as $resource) {
            $attrName = $resource->nodeName === 'object' ? 'data' : 'src';
            if (!$resource->hasAttribute($attrName)) continue;
            
            $src = $resource->getAttribute($attrName);
            $src = $this->makeUrlAbsolute($src, $url);
            
            $parsedUrl = parse_url($src);
            
            // Only process external resources
            if (isset($parsedUrl['host']) && strtolower($parsedUrl['host']) !== strtolower($currentDomainHost)) {
                $serviceData = $this->identifyServiceFromUrl($src);
                
                if ($serviceData) {
                    $results[] = $this->saveDetectedService(
                        $configuration,
                        $serviceData['name'],
                        $src,
                        $serviceData['category'],
                        $sourceDomain,
                        $serviceData['id'] // Pass library ID for reference
                    );
                } else {
                    // Unknown external service
                    $results[] = $this->saveDetectedService(
                        $configuration,
                        null,
                        $src,
                        'other',
                        $sourceDomain
                    );
                }
            }
        }
        
        // Check for data attributes that might contain tracking scripts or cookies
        $elementsWithDataAttrs = $xpath->query('//*[@*[starts-with(name(), "data-")]]');
        foreach ($elementsWithDataAttrs as $element) {
            $attributes = $element->attributes;
            foreach ($attributes as $attr) {
                if (strpos($attr->name, 'data-') === 0) {
                    $value = $attr->value;
                    
                    // Data attributes that commonly indicate tracking
                    $trackingAttrs = ['data-tracking', 'data-analytics', 'data-ga', 'data-gtm', 'data-pixel', 
                                     'data-fb', 'data-facebook', 'data-track', 'data-cookie', 'data-consent'];
                    
                    if (in_array(strtolower($attr->name), $trackingAttrs) || 
                        preg_match('/(facebook|google|analytics|tracking|pixel|cookie|consent)/i', $attr->name)) {
                        
                        // This might be a tracking-related data attribute
                        $serviceName = ucfirst(str_replace(['data-', '-', '_'], ['', ' ', ' '], $attr->name));
                        $serviceName = trim($serviceName . ' Tracking');
                        
                        // Save as a potential tracking service
                        $this->saveDetectedService(
                            $configuration,
                            $serviceName,
                            'data-attr:' . $attr->name,
                            'analytics', // Default to analytics for tracking attributes
                            $sourceDomain
                        );
                        
                        // Check the value for URLs or service names
                        if (filter_var($value, FILTER_VALIDATE_URL)) {
                            $serviceData = $this->identifyServiceFromUrl($value);
                            if ($serviceData) {
                                $this->saveDetectedService(
                                    $configuration,
                                    $serviceData['name'],
                                    $value,
                                    $serviceData['category'],
                                    $sourceDomain,
                                    $serviceData['id']
                                );
                            }
                        }
                    }
                }
            }
        }
        
        // Check for meta tags that might indicate trackers or cookies
        $metaTags = $xpath->query('//meta[@name or @property]');
        foreach ($metaTags as $meta) {
            $nameOrProperty = $meta->hasAttribute('name') ? $meta->getAttribute('name') : $meta->getAttribute('property');
            $content = $meta->hasAttribute('content') ? $meta->getAttribute('content') : '';
            
            // Look for meta tags related to tracking or cookies
            $trackingMetaTags = [
                'facebook-domain-verification', 'facebook-pixel', 'fb:app_id',
                'google-site-verification', 'google-analytics', 'gtm-id',
                'analytics', 'tracking', 'cookie-policy', 'cookie-notice',
                'cookieconsent', 'cookie-consent', 'gdpr'
            ];
            
            foreach ($trackingMetaTags as $trackingTag) {
                if (stripos($nameOrProperty, $trackingTag) !== false) {
                    // This is a tracking-related meta tag
                    $serviceName = ucfirst(str_replace(['-', '_', ':'], [' ', ' ', ' '], $nameOrProperty));
                    
                    // Save as a potential tracking service
                    $this->saveDetectedService(
                        $configuration,
                        $serviceName,
                        'meta-tag:' . $nameOrProperty,
                        'analytics', // Default to analytics for tracking meta tags
                        $sourceDomain
                    );
                    
                    // If content is a URL, check it too
                    if (filter_var($content, FILTER_VALIDATE_URL)) {
                        $serviceData = $this->identifyServiceFromUrl($content);
                        if ($serviceData) {
                            $this->saveDetectedService(
                                $configuration,
                                $serviceData['name'],
                                $content,
                                $serviceData['category'],
                                $sourceDomain,
                                $serviceData['id']
                            );
                        }
                    }
                    
                    break;
                }
            }
        }
        
        // Look for JSON-LD scripts containing tracking or cookie information
        $jsonLdScripts = $xpath->query('//script[@type="application/ld+json"]');
        foreach ($jsonLdScripts as $script) {
            $content = trim($script->textContent);
            if (!empty($content)) {
                try {
                    $jsonData = json_decode($content, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($jsonData)) {
                        // Look for tracking-related properties in JSON-LD
                        $this->scanJsonLdForTrackingServices($jsonData, $configuration, $sourceDomain);
                    }
                } catch (\Exception $e) {
                    // Ignore JSON parsing errors
                    Log::debug("Error parsing JSON-LD: " . $e->getMessage());
                }
            }
        }
        
        return $results;
    }
    
    /**
     * Scan JSON-LD data for tracking services
     * 
     * @param array $jsonData
     * @param \App\Models\Configuration $configuration
     * @param string $sourceDomain
     */
    protected function scanJsonLdForTrackingServices($jsonData, $configuration, $sourceDomain)
    {
        // Keywords that might indicate tracking services
        $trackingKeywords = [
            'tracking', 'analytics', 'pixel', 'facebook', 'google', 'tag', 'cookie', 
            'consent', 'gdpr', 'privacy', 'marketing', 'advertisement'
        ];
        
        // Recursive function to search through JSON structure
        $searchJson = function($data, $path = '') use (&$searchJson, $trackingKeywords, $configuration, $sourceDomain) {
            if (is_array($data)) {
                foreach ($data as $key => $value) {
                    $currentPath = empty($path) ? $key : $path . '.' . $key;
                    
                    // Check if the key contains tracking keywords
                    foreach ($trackingKeywords as $keyword) {
                        if (stripos($key, $keyword) !== false) {
                            if (is_string($value)) {
                                // Found a potential tracking service reference
                                $serviceName = ucfirst(str_replace(['_', '-'], [' ', ' '], $key));
                                
                                // Save as a potential tracking service
                                $this->saveDetectedService(
                                    $configuration,
                                    $serviceName,
                                    'json-ld:' . $currentPath,
                                    'analytics', // Default to analytics
                                    $sourceDomain
                                );
                                
                                // If value is a URL, check it too
                                if (filter_var($value, FILTER_VALIDATE_URL)) {
                                    $serviceData = $this->identifyServiceFromUrl($value);
                                    if ($serviceData) {
                                        $this->saveDetectedService(
                                            $configuration,
                                            $serviceData['name'],
                                            $value,
                                            $serviceData['category'],
                                            $sourceDomain,
                                            $serviceData['id']
                                        );
                                    }
                                }
                            }
                            break;
                        }
                    }
                    
                    // Recursively search nested arrays and objects
                    if (is_array($value)) {
                        $searchJson($value, $currentPath);
                    }
                }
            }
        };
        
        // Start recursive search
        $searchJson($jsonData);
    }
    
    /**
     * Find privacy pages from the HTML
     *
     * @param string $html
     * @param string $baseDomain
     * @return array
     */
    protected function findPrivacyPages($html, $baseDomain)
    {
        $pages = [];
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $xpath = new DOMXPath($dom);
        
        // Common terms for privacy and cookie pages
        $terms = [
            'privacy', 'cookie', 'cookies', 'gdpr', 'ccpa', 'data', 'datenschutz', 
            'privacidad', 'confidentialité', 'privacy-policy', 'cookie-policy', 
            'terms', 'tos', 'legal', 'impressum', 'datenschutzerklärung'
        ];
        
        // Create XPath query for links containing these terms
        $query = '//a[';
        $conditions = [];
        
        foreach ($terms as $term) {
            $conditions[] = "contains(translate(text(), 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'), '$term')";
            $conditions[] = "contains(translate(@href, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'), '$term')";
        }
        
        $query .= implode(' or ', $conditions) . ']';
        
        // Find all matching links
        $links = $xpath->query($query);
        
        foreach ($links as $link) {
            $href = $link->getAttribute('href');
            $href = $this->makeUrlAbsolute($href, $baseDomain);
            $pages[] = $href;
        }
        
        return array_unique($pages);
    }
    
/**
 * Save a detected service to the database
 *
 * @param \App\Models\Configuration $configuration
 * @param string|null $serviceName
 * @param string|null $serviceUrl
 * @param string|null $category
 * @param string $sourceDomain
 * @param int|null $libraryId 
 * @return \App\Models\DpsScan|null
 */
protected function saveDetectedService($configuration, $serviceName, $serviceUrl, $category, $sourceDomain, $libraryId = null)
{
    // Extract domain from URL if possible
    $domain = $serviceUrl;
    $cookieName = null;
    $displayName = null;
    
    // Check if this is a prefixed URL (cookie:)
    $prefix = '';
    if (strpos($serviceUrl, 'cookie:') === 0) {
        $prefix = 'cookie:';
        $cookieName = substr($serviceUrl, strlen($prefix));
        $domain = $configuration->domain; // Use configuration domain for cookies
        
        // Generate display name for the cookie
        $displayName = $this->generateDisplayName($cookieName, 'cookie');
        
        // Categorize cookies properly based on naming patterns
        if (empty($category)) {
            $category = $this->categorizeCookie($cookieName);
        }
    } else if ($serviceUrl) {
        $parsedUrl = parse_url($serviceUrl);
        if (isset($parsedUrl['host'])) {
            $domain = $parsedUrl['host'];
            
            // Generate display name for the service based on domain
            $displayName = $this->generateDisplayName($domain, 'service');
        }
    }
    
    // Set a default name if none is provided
    if (empty($serviceName) && !empty($domain)) {
        // For cookies, use the display name as the service name
        if ($cookieName && $displayName) {
            $serviceName = 'Cookie: ' . $displayName;
        } 
        // Check for jQuery domains
        else if (stripos($domain, 'jquery') !== false || 
            (isset($serviceUrl) && stripos($serviceUrl, 'jquery') !== false)) {
            // Skip this or mark as processed but don't add to actual results
            return null;
        } else {
            // Try to extract a meaningful name from the domain
            $domainParts = explode('.', $domain);
            if (count($domainParts) >= 2) {
                // Just use the domain name without "Unknown:" prefix
                $serviceName = $displayName ?: ucfirst($domainParts[count($domainParts) - 2]);
            } else {
                $serviceName = $displayName ?: 'External Service';
            }
        }
    } else if (empty($serviceName)) {
        $serviceName = $displayName ?: 'External Service';
    }
    
    // Determine detection type
    $detectionType = 'other';
    if ($cookieName) {
        $detectionType = 'cookie';
    } else if (strpos($serviceUrl, '.js') !== false || strpos($serviceUrl, '/js/') !== false) {
        $detectionType = 'script';
    } else if (strpos($serviceUrl, 'iframe') !== false) {
        $detectionType = 'iframe';
    } else if ($domain && !$serviceUrl) {
        $detectionType = 'domain';
    }
    
    // For cookies, check if this exact cookie already exists
    if ($cookieName) {
        $existingScan = DpsScan::where('configuration_id', $configuration->id)
            ->where('service_url', $serviceUrl)
            ->first();
            
        if ($existingScan) {
            // Update status to ensure it's processed correctly
            if ($existingScan->status === 'todo') {
                $existingScan->detection_type = 'cookie';
                $existingScan->cookie_name = $cookieName;
                $existingScan->category = $category; // Ensure category is updated
                $existingScan->display_name = $displayName; // Update display name
                $existingScan->save();
            }
            return $existingScan;
        }
    } else {
        // For non-cookies, check if a similar service exists
        $existingScan = DpsScan::where('configuration_id', $configuration->id)
            ->where(function($query) use ($serviceName, $domain, $serviceUrl) {
                // If service name exists, use that for matching, otherwise use domain
                if (!empty($serviceName)) {
                    $query->where('service_name', $serviceName);
                } else {
                    $query->where('domain', $domain);
                }
            })
            ->first();
        
        if ($existingScan) {
            // Update existing scan if we have more information now
            if (!empty($serviceName) && empty($existingScan->service_name)) {
                $existingScan->service_name = $serviceName;
                $existingScan->category = $category;
                $existingScan->library_id = $libraryId;
                $existingScan->detection_type = $detectionType;
                $existingScan->display_name = $displayName; // Update display name
                $existingScan->save();
            }
            
            return $existingScan;
        }
    }
    
    // Create new scan record with proper detection type, cookie name and display name
    return DpsScan::create([
        'company_id' => $configuration->company_id,
        'configuration_id' => $configuration->id,
        'domain' => $domain,
        'service_name' => $serviceName,
        'service_url' => $serviceUrl,
        'source_domain' => $sourceDomain,
        'category' => $category,
        'library_id' => $libraryId, // Store reference to library
        'status' => 'todo', // Mark as todo until added to DPS
        'detection_type' => $detectionType,
        'cookie_name' => $cookieName,
        'display_name' => $displayName,
        'scan_date' => now(),
    ]);
}

/**
 * Update display names for existing scans
 * 
 * @param \App\Models\Configuration $configuration
 * @return int Number of records updated
 */
public function updateDisplayNames($configuration)
{
    $updated = 0;
    
    // Get all scans without display names
    $scans = DpsScan::where('configuration_id', $configuration->id)
                   ->whereNull('display_name')
                   ->get();
    
    foreach ($scans as $scan) {
        $displayName = null;
        
        if ($scan->detection_type === 'cookie' || !empty($scan->cookie_name)) {
            $cookieName = $scan->cookie_name ?: (strpos($scan->service_url, 'cookie:') === 0 ? 
                          substr($scan->service_url, 7) : null);
            
            if ($cookieName) {
                $displayName = $this->generateDisplayName($cookieName, 'cookie');
            }
        } else {
            // For services, use the domain
            $displayName = $this->generateDisplayName($scan->domain, 'service');
        }
        
        if ($displayName) {
            DpsScan::where('id', $scan->id)->update(['display_name' => $displayName]);
            $updated++;
        }
    }
    
    return $updated;
}

/**
 * Categorize a cookie based on its name and common patterns
 * 
 * @param string $cookieName
 * @return string
 */
protected function categorizeCookie($cookieName)
{
    // Common analytics cookies
    $analyticsCookies = [
        '_ga', '_gid', '_gat', '_utm', 'analytics', 'statistic', 'stats',
        'visitor', 'amplitude', 'mixpanel', 'matomo', 'piwik', 'hotjar',
        'optimizely', 'hubspot', '_hsq', '_fbp', '_fbc', 'intercom', 'crisp',
        'segment', 'clicky', 'crazy_egg', 'clarity', 'ahoy', '_shopify_s', '_shopify_y',
        'tracking', '_sp', 'heap', 'attribution', 'ganalytics'
    ];
    
    // Common functionality cookies
    $functionalityCookies = [
        'session', 'auth', 'login', 'user', 'csrf', 'xsrf', 'token', 'secure',
        'language', 'locale', 'timezone', 'currency', 'accessibility', 'legal',
        'consent', 'cookie_notice', 'gdpr', 'ccpa', 'preference', 'setting',
        'notification', 'frontend', 'theme', 'cart', 'checkout', 'wishlist',
        'localization', 'keep_alive'
    ];
    
    // Common targeting/advertising cookies
    $targetingCookies = [
        'ad', 'ads', 'advert', 'doubleclick', 'dcm', 'dsp', 'taboola', 'outbrain',
        'criteo', 'pubmatic', 'adroll', 'adform', 'appnexus', 'rubicon', 'openx',
        'mediamath', 'bidswitch', 'partner', 'affiliate', 'promotion', 'campaign',
        'recommendation', 'remarketing', 'retargeting', 'conversion', 'pixel'
    ];
    
    // Common necessary cookies
    $necessaryCookies = [
        'csrf', 'xsrf', 'token', 'essential', 'necessary', 'required', 'shopify_pay',
        'woocommerce_session', 'magento_session', 'wordpress_logged_in', 'phpsessid',
        'laravel_session', 'jsessionid', 'aspsessionid', '_orig_referrer', '_landing_page'
    ];
    
    // Check if the cookie matches any of the patterns
    foreach ($analyticsCookies as $pattern) {
        if (stripos($cookieName, $pattern) !== false) {
            return 'analytics';
        }
    }
    
    foreach ($targetingCookies as $pattern) {
        if (stripos($cookieName, $pattern) !== false) {
            return 'marketing';
        }
    }
    
    foreach ($necessaryCookies as $pattern) {
        if (stripos($cookieName, $pattern) !== false) {
            return 'necessary';
        }
    }
    
    foreach ($functionalityCookies as $pattern) {
        if (stripos($cookieName, $pattern) !== false) {
            return 'functional';
        }
    }
    
    // Special case for _tracking_consent which is necessary
    if ($cookieName === '_tracking_consent') {
        return 'necessary';
    }
    
    // If the cookie starts with underscore or is uppercase, it's often a system cookie
    if (strpos($cookieName, '_') === 0 || strtoupper($cookieName) === $cookieName) {
        return 'functional';
    }
    
    // Default category if no match is found
    return 'other';
}
    
    /**
     * Identify a service from a URL by matching with the library
     *
     * @param string $url
     * @return array|null
     */
    protected function identifyServiceFromUrl($url)
    {
        if (!$url) {
            return null;
        }
        
        // Parse the URL to get host and file extension
        $parsedUrl = parse_url($url);
        $urlHost = isset($parsedUrl['host']) ? $parsedUrl['host'] : '';
        
        // Skip certain resource types we don't want to track
        if (!empty($parsedUrl['path'])) {
            $path = strtolower($parsedUrl['path']);
            $skipExtensions = ['.css', '.jpg', '.jpeg', '.png', '.gif', '.svg', '.woff', '.woff2', '.ttf', '.eot'];
            
            foreach ($skipExtensions as $ext) {
                if (substr($path, -strlen($ext)) === $ext) {
                    return null; // Skip this resource
                }
            }
        }
        
        // Try direct domain match first
        foreach ($this->knownServices as $domainPattern => $service) {
            // Try exact domain match
            if (strtolower($urlHost) === strtolower($domainPattern)) {
                return $service;
            }
            
            // Try direct domain pattern match
            if (strpos(strtolower($urlHost), strtolower($domainPattern)) !== false) {
                return $service;
            }
            
            // Try full URL match
            if (strpos(strtolower($url), strtolower($domainPattern)) !== false) {
                return $service;
            }
        }
        
        // If no direct match, try parent service matching for subdomains
        // Extract domain parts for better matching
        $domainParts = explode('.', $urlHost);
        
        // Look for parent service matches in domain parts using the library instead of hardcoded list
        foreach ($domainParts as $part) {
            $part = strtolower($part);
            
            // Check each known service to see if this domain part matches one of their parts
            foreach ($this->knownServices as $domainPattern => $service) {
                $patternParts = explode('.', strtolower($domainPattern));
                
                // Check if this domain part matches the beginning of any pattern part
                foreach ($patternParts as $patternPart) {
                    if ($part === $patternPart) {
                        return $service;
                    }
                }
            }
        }
        
        // If we reach here, no matching parent service was found
        return null;
    }
    
/**
 * Add all detected services from the scan to Data Processing Services
 * 
 * @param \App\Models\Configuration $configuration
 * @return array
 */
public function addDetectedServicesToDPS($configuration)
{
    $added = [];
    $processedCookies = [];
    
    // Get all pending scans for this configuration
    $pendingScans = DpsScan::where('configuration_id', $configuration->id)
        ->where('status', 'todo')
        ->get();
    
    // Process cookies first
    $cookieScans = $pendingScans->filter(function($scan) {
        return $scan->detection_type === 'cookie' || strpos($scan->service_url, 'cookie:') === 0;
    });
    
    // Add debugging
    Log::info("Processing cookies", [
        'domain' => $configuration->domain,
        'cookie_count' => $cookieScans->count()
    ]);
    
    foreach ($cookieScans as $scan) {
        // Extract cookie name
        $cookieName = $scan->cookie_name;
        if (empty($cookieName) && strpos($scan->service_url, 'cookie:') === 0) {
            $cookieName = substr($scan->service_url, 7); // Remove 'cookie:' prefix
            
            // Update the scan record with the cookie name
            DpsScan::where('id', $scan->id)->update([
                'cookie_name' => $cookieName,
                'detection_type' => 'cookie'
            ]);
        }
        
        // Skip if no valid cookie name
        if (empty($cookieName)) {
            continue;
        }
        
        // Categorize the cookie if not already categorized or if it's 'other'
        $category = $scan->category;
        if (empty($category) || $category === 'other') {
            // Try to categorize based on cookie name
            $category = $this->categorizeCookie($cookieName);
            
            // Update the scan with the better category
            DpsScan::where('id', $scan->id)->update(['category' => $category]);
        }
        
        // If we've already processed this cookie name, mark as duplicate and skip
        if (in_array($cookieName, $processedCookies)) {
            DpsScan::where('id', $scan->id)->update(['status' => 'duplicate']);
            continue;
        }
        
        // Process this cookie
        $processedCookies[] = $cookieName;
        
        // Always mark cookies as properly added
        DpsScan::where('id', $scan->id)->update(['status' => 'added']);
        
        // Check if this cookie needs to be added to DataProcessingServices
        $existingService = DataProcessingServices::where('configuration_id', $configuration->id)
            ->where(function($query) use ($cookieName, $scan) {
                $query->where('name', 'like', "%{$cookieName}%")
                    ->orWhere('name', $scan->service_name);
            })
            ->first();
        
        // If no existing service, create one
        if (!$existingService) {
            $serviceName = $scan->service_name ?? "Cookie: {$cookieName}";
            
            // Look for cookie info in library if possible
            if ($scan->library_id) {
                $libraryData = DpsLibrary::find($scan->library_id);
                if ($libraryData) {
                    $category = $libraryData->category ?? $category;
                }
            }
            
            $dpsData = [
                'company_id' => $configuration->company_id,
                'configuration_id' => $configuration->id,
                'name' => $serviceName,
                'category' => $category,
                'status' => 'active',
                'is_essential' => ($category === 'necessary' || $category === 'essential'),
                'data_sharing_eu' => false,
                'accepted_by_default' => ($category === 'necessary' || $category === 'essential')
            ];
            
            $service = DataProcessingServices::create($dpsData);
            $added[] = $service;
            
            Log::debug("Added cookie to DPS: {$cookieName} with category {$category}");
        }
    }
    
    // Now process non-cookie services
    $nonCookieScans = $pendingScans->filter(function($scan) {
        return $scan->detection_type !== 'cookie' && strpos($scan->service_url, 'cookie:') !== 0;
    });
    
    // Group by service name to avoid duplicates
    $uniqueServices = [];
    foreach ($nonCookieScans as $scan) {
        // Determine the detection type if not already set
        if (empty($scan->detection_type)) {
            $detectionType = 'other';
            if (strpos($scan->service_url, '.js') !== false || strpos($scan->service_url, '/js/') !== false) {
                $detectionType = 'script';
            } else if (strpos($scan->service_url, 'iframe') !== false) {
                $detectionType = 'iframe';
            } else if ($scan->domain && !$scan->service_url) {
                $detectionType = 'domain';
            }
            
            // Update detection type
            DpsScan::where('id', $scan->id)->update(['detection_type' => $detectionType]);
        }
        
        $serviceName = $scan->service_name ?? ('Unknown: ' . $scan->domain);
        
        // Skip certain resource files and jQuery we don't want to track
        if (!empty($scan->service_url) || !empty($scan->domain)) {
            // Check if it's jQuery - skip it
            if ((stripos($scan->domain, 'jquery') !== false) || 
                (!empty($scan->service_url) && stripos($scan->service_url, 'jquery') !== false)) {
                // Mark as processed but don't add to DPS
                DpsScan::where('id', $scan->id)->update(['status' => 'ignored']);
                continue;
            }
            
            // Skip resource files
            if (!empty($scan->service_url)) {
                $parsedUrl = parse_url($scan->service_url);
                if (!empty($parsedUrl['path'])) {
                    $path = strtolower($parsedUrl['path']);
                    $skipExtensions = ['.css', '.jpg', '.jpeg', '.png', '.gif', '.svg', '.woff', '.woff2', '.ttf', '.eot'];
                    
                    $skipResource = false;
                    foreach ($skipExtensions as $ext) {
                        if (substr($path, -strlen($ext)) === $ext) {
                            $skipResource = true;
                            break;
                        }
                    }
                    
                    if ($skipResource) {
                        // Mark as processed but don't add to DPS
                        DpsScan::where('id', $scan->id)->update(['status' => 'ignored']);
                        continue;
                    }
                }
            }
            
            // Skip own domain
            $configDomain = parse_url(Configuration::find($configuration->id)->domain, PHP_URL_HOST);
            if ($configDomain && $this->isOwnDomainOrSubdomain($scan->domain, $configDomain)) {
                // Mark as processed but don't add to DPS
                DpsScan::where('id', $scan->id)->update(['status' => 'ignored']);
                continue;
            }
        }
        
        // If we don't have this service yet or current scan has more info
        if (!isset($uniqueServices[$serviceName]) || 
            (empty($uniqueServices[$serviceName]['category']) && !empty($scan->category))) {
            $uniqueServices[$serviceName] = [
                'scan_id' => $scan->id,
                'name' => $scan->service_name,
                'domain' => $scan->domain,
                'category' => $scan->category,
                'library_id' => $scan->library_id
            ];
        }
    }
    
    // Add each unique service to DPS
    foreach ($uniqueServices as $serviceName => $serviceData) {
        // Skip if already exists in DPS
        $existingService = DataProcessingServices::where('configuration_id', $configuration->id)
            ->where('name', $serviceName)
            ->first();
            
        if (!$existingService) {
            // Fetch additional data from library if available
            $libraryData = null;
            if ($serviceData['library_id']) {
                $libraryData = DpsLibrary::find($serviceData['library_id']);
            } else if (!empty($serviceData['name'])) {
                $libraryData = DpsLibrary::where('name', $serviceData['name'])->first();
            }
            
            // Ensure the service has a proper name
            $name = $serviceData['name'] ?? 'Unknown Service: ' . $serviceData['domain'];
            
            // Prepare data for DPS
            $dpsData = [
                'company_id' => $configuration->company_id,
                'configuration_id' => $configuration->id,
                'name' => $name,
                'category' => $serviceData['category'] ?? 'other', // Default category
                'status' => 'active',
                'is_essential' => false,
                'data_sharing_eu' => false,
                'accepted_by_default' => false
            ];
            
            // Enhance with library data if available
            if ($libraryData) {
                $dpsData['category'] = $libraryData->category ?? $dpsData['category'];
                $dpsData['data_sharing_eu'] = $libraryData->data_sharing ?? false;
                
                // Add more details if your table supports these fields
                if (isset($dpsData['description'])) {
                    $dpsData['description'] = $libraryData->description;
                }
                if (isset($dpsData['provider_name'])) {
                    $dpsData['provider_name'] = $libraryData->provider_name;
                }
                if (isset($dpsData['privacy_policy_url'])) {
                    $dpsData['privacy_policy_url'] = $libraryData->privacy_policy_url;
                }
            }
            
            // Create the DPS entry
            $service = DataProcessingServices::create($dpsData);
            $added[] = $service;
        }
        
        // Update all related scans to 'added' status
        DpsScan::where('configuration_id', $configuration->id)
            ->where(function($query) use ($serviceData) {
                if (!empty($serviceData['name'])) {
                    $query->where('service_name', $serviceData['name']);
                } else {
                    $query->where('domain', $serviceData['domain']);
                }
            })
            ->update(['status' => 'added']);
    }
    
    // Log the final summary
    Log::info("DPS Processing Complete", [
        'domain' => $configuration->domain,
        'total_cookies_detected' => count($processedCookies),
        'cookies_processed' => $processedCookies,
        'total_services_added' => count($added)
    ]);
    
    return $added;
}
/**
 * Generate detailed scan report for debugging and monitoring
 * 
 * @param \App\Models\Configuration $configuration
 * @return array
 */
public function generateScanReport($configuration)
{
    try {
        $report = [
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'domain' => $configuration->domain,
            'configuration_id' => $configuration->id,
            'summary' => [],
            'details' => []
        ];
        
        // Get all scans for this configuration
        $allScans = DpsScan::where('configuration_id', $configuration->id)->get();
        
        // Count by category
        $categoryCounts = [];
        foreach ($allScans as $scan) {
            $category = $scan->category ?? 'uncategorized';
            if (!isset($categoryCounts[$category])) {
                $categoryCounts[$category] = 0;
            }
            $categoryCounts[$category]++;
        }
        $report['summary']['by_category'] = $categoryCounts;
        
        // Count by detection type
        $typeCounts = [
            'script_tags' => 0,
            'cookies' => 0,
            'external_domains' => 0,
            'iframes' => 0,
            'others' => 0
        ];
        
        // Detailed lists by type
        $details = [
            'script_tags' => [],
            'cookies' => [],
            'external_domains' => [],
            'iframes' => [],
            'others' => []
        ];
        
        foreach ($allScans as $scan) {
            $serviceUrl = $scan->service_url ?? '';
            $type = 'others';
            
            // Determine the type based on service_url or other indicators
            if (strpos($serviceUrl, 'cookie:') === 0) {
                $type = 'cookies';
                $typeCounts['cookies']++;
                $details['cookies'][] = [
                    'name' => substr($serviceUrl, 7), // Remove 'cookie:' prefix
                    'service' => $scan->service_name,
                    'domain' => $scan->domain,
                    'category' => $scan->category
                ];
            } else if ($serviceUrl && (strpos($serviceUrl, '.js') !== false || 
                      strpos($serviceUrl, '/js/') !== false || 
                      strpos($serviceUrl, 'script') !== false)) {
                $type = 'script_tags';
                $typeCounts['script_tags']++;
                $details['script_tags'][] = [
                    'url' => $serviceUrl,
                    'service' => $scan->service_name,
                    'domain' => $scan->domain,
                    'category' => $scan->category
                ];
            } else if ($serviceUrl && (strpos($serviceUrl, 'iframe') !== false) || 
                      ($scan->domain && !$scan->service_url)) { // Often iframes are detected by domain only
                $type = 'iframes';
                $typeCounts['iframes']++;
                $details['iframes'][] = [
                    'url' => $serviceUrl,
                    'service' => $scan->service_name,
                    'domain' => $scan->domain,
                    'category' => $scan->category
                ];
            } else if ($scan->domain) {
                $type = 'external_domains';
                $typeCounts['external_domains']++;
                $details['external_domains'][] = [
                    'domain' => $scan->domain,
                    'service' => $scan->service_name,
                    'category' => $scan->category
                ];
            } else {
                $typeCounts['others']++;
                $details['others'][] = [
                    'url' => $serviceUrl,
                    'service' => $scan->service_name,
                    'domain' => $scan->domain,
                    'category' => $scan->category
                ];
            }
        }
        
        $report['summary']['by_type'] = $typeCounts;
        $report['details'] = $details;
        
        // Calculate statistics
        $report['summary']['total_services'] = count($allScans);
        $report['summary']['unique_services'] = DpsScan::where('configuration_id', $configuration->id)
            ->whereNotNull('service_name')
            ->distinct('service_name')
            ->count('service_name');
        $report['summary']['unique_domains'] = DpsScan::where('configuration_id', $configuration->id)
            ->whereNotNull('domain')
            ->distinct('domain')
            ->count('domain');
        
        // Output to log instead of console for web requests
        Log::info("DPS Scan Report for {$configuration->domain}", ['summary' => $report['summary']]);
        
        // Only output to console if running in CLI
        if (php_sapi_name() === 'cli') {
            $this->outputReportToConsole($report);
        }
        
        return $report;
        
    } catch (\Exception $e) {
        // Log the error but don't fail the whole scan process
        Log::error("Error generating scan report: " . $e->getMessage(), [
            'exception' => $e,
            'configuration_id' => $configuration->id
        ]);
        
        return [
            'error' => true,
            'message' => 'Error generating report: ' . $e->getMessage()
        ];
    }
}

/**
 * Output the scan report to console in a readable format
 * 
 * @param array $report
 * @return void
 */
private function outputReportToConsole($report)
{
    try {
        echo "\n";
        echo "======================================================================\n";
        echo "                 DPS SCAN REPORT: {$report['domain']}\n";
        echo "======================================================================\n";
        echo "Timestamp: {$report['timestamp']}\n";
        echo "----------------------------------------------------------------------\n";
        echo "SUMMARY:\n";
        echo "----------------------------------------------------------------------\n";
        echo "Total services detected: {$report['summary']['total_services']}\n";
        echo "Unique services: {$report['summary']['unique_services']}\n";
        echo "Unique domains: {$report['summary']['unique_domains']}\n\n";
        
        // Add a special count for cookies from the debug logs
        $cookieCount = DpsScan::where('configuration_id', $report['configuration_id'])
            ->where('service_url', 'like', 'cookie:%')
            ->count();
        
        echo "BY TYPE (CORRECTED):\n";
        $report['summary']['by_type']['cookies'] = $cookieCount; // Override with accurate count
        foreach ($report['summary']['by_type'] as $type => $count) {
            echo str_pad($type, 20) . ": " . $count . "\n";
        }
        echo "\n";
        
        echo "BY CATEGORY:\n";
        foreach ($report['summary']['by_category'] as $category => $count) {
            echo str_pad($category, 20) . ": " . $count . "\n";
        }
        
        echo "\n";
        echo "----------------------------------------------------------------------\n";
        echo "DETAILED LISTS:\n";
        echo "----------------------------------------------------------------------\n";
        
        // Retrieve all cookies directly from the database for more accurate reporting
        $allCookies = DpsScan::where('configuration_id', $report['configuration_id'])
            ->where('service_url', 'like', 'cookie:%')
            ->get();
        
        // Display cookies with enhanced detection
        if ($allCookies->count() > 0) {
            echo "\nCOOKIES DETECTED: " . $allCookies->count() . " (Including filtered ones)\n";
            echo "----------------------------------------------------------------------\n";
            
            foreach ($allCookies as $index => $cookie) {
                $cookieName = str_replace('cookie:', '', $cookie->service_url);
                echo ($index + 1) . ". " . $cookieName . "\n";
                echo "   Service: " . ($cookie->service_name ?? 'Unknown') . "\n";
                echo "   Domain: " . ($cookie->domain ?? 'N/A') . "\n";
                echo "   Category: " . ($cookie->category ?? 'Unknown') . "\n";
                echo "   Status: " . ($cookie->status ?? 'Unknown') . "\n";
                echo "\n";
            }
        } else {
            // Fall back to the original report data if no direct cookie records
            if (count($report['details']['cookies']) > 0) {
                echo "\nCOOKIES DETECTED: " . count($report['details']['cookies']) . "\n";
                echo "----------------------------------------------------------------------\n";
                foreach ($report['details']['cookies'] as $index => $cookie) {
                    echo ($index + 1) . ". " . $cookie['name'] . "\n";
                    echo "   Service: " . ($cookie['service'] ?? 'Unknown') . "\n";
                    echo "   Domain: " . ($cookie['domain'] ?? 'N/A') . "\n";
                    echo "   Category: " . ($cookie['category'] ?? 'Unknown') . "\n";
                    echo "\n";
                }
            } else {
                echo "\nCOOKIES DETECTED: 0\n";
                echo "No cookies were detected in this scan. If you expected cookies to be found, try:\n";
                echo "1. Make sure the site is using document.cookie or common cookie libraries\n";
                echo "2. Add cookie pattern detection to your DPS library\n";
                echo "\n";
            }
        }
        
        // Display script tags
        if (count($report['details']['script_tags']) > 0) {
            echo "\nSCRIPT TAGS DETECTED: " . count($report['details']['script_tags']) . "\n";
            echo "----------------------------------------------------------------------\n";
            foreach ($report['details']['script_tags'] as $index => $script) {
                echo ($index + 1) . ". " . ($script['service'] ?? 'Unknown Service') . "\n";
                echo "   URL: " . ($script['url'] ?? 'N/A') . "\n";
                echo "   Domain: " . ($script['domain'] ?? 'N/A') . "\n";
                echo "   Category: " . ($script['category'] ?? 'Unknown') . "\n";
                echo "\n";
            }
        }
        
        // Display external domains
        if (count($report['details']['external_domains']) > 0) {
            echo "\nEXTERNAL DOMAINS DETECTED: " . count($report['details']['external_domains']) . "\n";
            echo "----------------------------------------------------------------------\n";
            foreach ($report['details']['external_domains'] as $index => $domain) {
                echo ($index + 1) . ". " . ($domain['domain'] ?? 'N/A') . "\n";
                echo "   Service: " . ($domain['service'] ?? 'Unknown') . "\n";
                echo "   Category: " . ($domain['category'] ?? 'Unknown') . "\n";
                echo "\n";
            }
        }
        
        // Display iframes
        if (count($report['details']['iframes']) > 0) {
            echo "\nIFRAMES DETECTED: " . count($report['details']['iframes']) . "\n";
            echo "----------------------------------------------------------------------\n";
            foreach ($report['details']['iframes'] as $index => $iframe) {
                echo ($index + 1) . ". " . ($iframe['service'] ?? 'Unknown Service') . "\n";
                echo "   URL: " . ($iframe['url'] ?? 'N/A') . "\n";
                echo "   Domain: " . ($iframe['domain'] ?? 'N/A') . "\n";
                echo "   Category: " . ($iframe['category'] ?? 'Unknown') . "\n";
                echo "\n";
            }
        }
        
        // Add a section for cookies that were filtered out
        $filteredCookies = DpsScan::where('configuration_id', $report['configuration_id'])
            ->where('service_url', 'like', 'cookie:%')
            ->where('status', 'ignored')
            ->get();
            
        if ($filteredCookies->count() > 0) {
            echo "\nFILTERED COOKIES: " . $filteredCookies->count() . "\n";
            echo "----------------------------------------------------------------------\n";
            echo "These cookies were detected but filtered out in the final report:\n\n";
            
            foreach ($filteredCookies as $index => $cookie) {
                $cookieName = str_replace('cookie:', '', $cookie->service_url);
                echo ($index + 1) . ". " . $cookieName . " (domain: " . $cookie->domain . ")\n";
            }
            echo "\n";
        }
        
        echo "======================================================================\n";
        echo "                           END OF REPORT                              \n";
        echo "======================================================================\n";
    } catch (\Exception $e) {
        Log::error("Error outputting report to console: " . $e->getMessage());
        echo "Error generating detailed report. Check logs for information.\n";
    }
}  

/**
 * Generate a human-readable display name for a cookie or service
 * 
 * @param string $name Original name (cookie name or service name)
 * @param string $type Type ('cookie' or 'service')
 * @return string Formatted display name
 */
protected function generateDisplayName($name, $type = 'cookie')
{
    // Known cookie mappings
    $knownCookies = [
        // Shopify cookies
        '_shopify_y' => 'Shopify Analytics (Long-term)',
        '_shopify_s' => 'Shopify Analytics (Short-term)',
        '_tracking_consent' => 'Tracking Consent',
        'secure_customer_sig' => 'Secure Customer Signature',
        'localization' => 'Localization Settings',
        '_orig_referrer' => 'Original Referrer',
        '_landing_page' => 'Landing Page',
        'keep_alive' => 'Session Keep-Alive',
        
        // Google Analytics cookies
        '_ga' => 'Google Analytics',
        '_gid' => 'Google Analytics (User ID)',
        '_gat' => 'Google Analytics (Throttling)',
        '_gcl_au' => 'Google Conversion Linker',
        
        // Facebook cookies
        '_fbp' => 'Facebook Pixel',
        '_fbc' => 'Facebook Click Identifier',
        
        // Common functional cookies
        'session' => 'Session Cookie',
        'PHPSESSID' => 'PHP Session ID',
        'JSESSIONID' => 'Java Session ID',
        'ASP.NET_SessionId' => 'ASP.NET Session ID',
        'csrf_token' => 'CSRF Protection Token',
        'remember_web' => 'Remember Login',
        
        // Common third-party services
        'intercom-session' => 'Intercom Chat Session',
        'intercom-id' => 'Intercom User ID',
        'hubspotutk' => 'HubSpot User Token',
        '__hstc' => 'HubSpot Analytics',
        'crisp-client' => 'Crisp Chat Client'
    ];
    
    // Known service mappings
    $knownServices = [
        'google-analytics' => 'Google Analytics',
        'gtag' => 'Google Tag Manager',
        'gtm' => 'Google Tag Manager',
        'facebook' => 'Facebook',
        'fb' => 'Facebook',
        'twitter' => 'Twitter',
        'shopify' => 'Shopify',
        'googleapis' => 'Google APIs',
        'cloudflare' => 'Cloudflare',
        'gstatic' => 'Google Static Content',
        'youtube' => 'YouTube',
        'doubleclick' => 'Google DoubleClick',
        'hotjar' => 'Hotjar',
        'linkedin' => 'LinkedIn',
        'pinterest' => 'Pinterest',
        'intercom' => 'Intercom',
        'hubspot' => 'HubSpot',
        'zendesk' => 'Zendesk',
        'crisp' => 'Crisp Chat',
        'tiktok' => 'TikTok',
        'mailchimp' => 'Mailchimp',
        'stripe' => 'Stripe',
        'paypal' => 'PayPal',
        'akamai' => 'Akamai',
        'fontawesome' => 'Font Awesome',
        'jquery' => 'jQuery'
    ];
    
    // Check for known mappings first
    if ($type === 'cookie' && isset($knownCookies[$name])) {
        return $knownCookies[$name];
    } else if ($type === 'service' && isset($knownServices[$name])) {
        return $knownServices[$name];
    }
    
    // Try to extract service name from domain
    if ($type === 'service' && strpos($name, '.') !== false) {
        $domainParts = explode('.', $name);
        if (count($domainParts) >= 2) {
            $domain = $domainParts[count($domainParts) - 2];
            
            // Check if this domain part is in our known services
            if (isset($knownServices[strtolower($domain)])) {
                return $knownServices[strtolower($domain)];
            }
            
            // Otherwise just format the domain
            return $this->formatName($domain);
        }
    }
    
    // Apply general formatting
    return $this->formatName($name);
}

/**
 * Format a name to be human-readable
 * 
 * @param string $name Original name to format
 * @return string Formatted name
 */
protected function formatName($name)
{
    // Remove common prefixes
    $name = preg_replace('/^_+/', '', $name);
    
    // Handle special case for cookies that start with "_"
    if (strpos($name, '_') === 0) {
        $name = substr($name, 1);
    }
    
    // Split by common separators
    $parts = preg_split('/[_\-\.]+/', $name);
    
    // Capitalize each part
    $parts = array_map('ucfirst', $parts);
    
    // Handle special cases
    foreach ($parts as $i => $part) {
        // Convert common abbreviations to uppercase
        $upperCaseTerms = ['id', 'ip', 'ua', 'url', 'utm', 'ui', 'gd', 'ga', 'fb', 'eu', 'us', 'uk', 'cdn'];
        if (in_array(strtolower($part), $upperCaseTerms)) {
            $parts[$i] = strtoupper($part);
        }
    }
    
    // Join the parts with spaces
    return implode(' ', $parts);
}

}