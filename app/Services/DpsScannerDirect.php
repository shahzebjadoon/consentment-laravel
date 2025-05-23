<?php

namespace App\Services;



use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Cookie\CookieJar;
use DOMDocument;
use DOMXPath;
use App\Models\DpsLibrary;
use Illuminate\Support\Facades\Log; // Import the Log class
use Exception;
use Illuminate\Support\Facades\Storage;

class DpsScannerDirect
{
    protected $client;
    protected $cookieJar;
    protected $knownServices = [];
    
    public function __construct()
    {
        $this->initializeHttpClient();
        $this->loadKnownServices();
    }
    
    protected function initializeHttpClient()
    {
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
    }
    

         

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

    // load known services without database
   /* protected function loadKnownServices()
    {
        // Load from config file instead of database
        $this->knownServices = config('dps_services.known_services', []);
        
        // Alternatively, you could hardcode common services here
        $this->knownServices = array_merge($this->knownServices, [
            'google-analytics.com' => [
                'name' => 'Google Analytics',
                'category' => 'analytics',
                'cookie_patterns' => ['_ga', '_gid', '_gat', '_gac_']
            ],
            'facebook.com' => [
                'name' => 'Facebook',
                'category' => 'marketing',
                'cookie_patterns' => ['_fbp', '_fbc', 'fr']
            ],
            // Add more services as needed
        ]);
    }
    */
    /**
     * Scan a domain and return results as array
     */
    public function scan(string $domain): array
    {
        if (!preg_match('/^https?:\/\//', $domain)) {
            $domain = 'https://' . $domain;
        }

        try {
            $results = [
                'domain' => $domain,
                'services' => [],
                'cookies' => [],
                'privacy_pages' => [],
                'scan_time' => now()->toDateTimeString()
            ];

            // Main page scan
            $response = $this->client->get($domain, [
                'on_headers' => function ($response) use (&$results, $domain) {
                    $results['cookies'] = array_merge(
                        $results['cookies'],
                        $this->processCookieHeaders($response, $domain)
                    );
                }
            ]);

            $html = (string)$response->getBody();
            $results['services'] = $this->processHtml($html, $domain);
            
            // Process cookies from jar
            $results['cookies'] = array_merge(
                $results['cookies'],
                $this->processCookieJar($domain)
            );

            // Privacy pages scan
            $results['privacy_pages'] = $this->findPrivacyPages($html, $domain);
            foreach ($results['privacy_pages'] as $privacyUrl) {
                try {
                    $privacyResponse = $this->client->get($privacyUrl);
                    $privacyHtml = (string)$privacyResponse->getBody();
                    
                    $results['services'] = array_merge(
                        $results['services'],
                        $this->processHtml($privacyHtml, $privacyUrl, $domain)
                    );
                    
                    $results['cookies'] = array_merge(
                        $results['cookies'],
                        $this->processCookieJar($privacyUrl)
                    );
                } catch (RequestException $e) {
                    continue;
                }
            }

            // JavaScript scan
            $results['services'] = array_merge(
                $results['services'],
                $this->scanJavaScriptFiles($html, $domain)
            );

            return $results;

        } catch (RequestException $e) {
            Log::error("Direct scan failed for {$domain}: " . $e->getMessage());
            return [
                'error' => true,
                'message' => 'Scan failed: ' . $e->getMessage(),
                'domain' => $domain
            ];
        }
    }
    
    // Add all the other helper methods from previous implementation
    // (processCookieHeaders, processCookieJar, processHtml, etc.)
    // but modified to work without database dependencies
    
    protected function processCookieHeaders($response, $sourceUrl)
    {
        $setCookieHeaders = $response->getHeader('Set-Cookie');
        $cookies = [];
        
        foreach ($setCookieHeaders as $cookieHeader) {
            $cookieData = $this->parseCookieHeader($cookieHeader);
            
            if (!empty($cookieData['name'])) {
                $cookieName = $cookieData['name'];
                $service = $this->identifyServiceFromCookie($cookieName);
                
                $cookies[] = [
                    'name' => $cookieName,
                    'domain' => $cookieData['domain'] ?? parse_url($sourceUrl, PHP_URL_HOST),
                    'service' => $service ? $service['name'] : null,
                    'category' => $service ? $service['category'] : $this->categorizeCookie($cookieName),
                    'expires' => $cookieData['expires'] ?? null,
                    'http_only' => isset($cookieData['httponly']),
                    'secure' => isset($cookieData['secure']),
                    'value' => $cookieData['value'] ?? null,
                    'path' => $cookieData['path'] ?? '/'
                ];
            }
        }
        
        return $cookies;
    }
    
    protected function processCookieJar($domain)
    {
        $cookies = [];
        $host = parse_url($domain, PHP_URL_HOST);
        
        foreach ($this->cookieJar as $cookie) {
            if ($cookie->matchesDomain($host)) {
                $service = $this->identifyServiceFromCookie($cookie->getName());
                
                $cookies[] = [
                    'name' => $cookie->getName(),
                    'domain' => $cookie->getDomain(),
                    'service' => $service ? $service['name'] : null,
                    'category' => $service ? $service['category'] : $this->categorizeCookie($cookie->getName()),
                    'expires' => $cookie->getExpires(),
                    'http_only' => $cookie->getHttpOnly(),
                    'secure' => $cookie->getSecure(),
                    'value' => $cookie->getValue(),
                    'path' => $cookie->getPath()
                ];
            }
        }
        
        return $cookies;
    }
    
    protected function processHtml($html, $url, $sourceDomain = null)
    {
        $services = [];
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $xpath = new DOMXPath($dom);
        
        // Process scripts
        $scripts = $xpath->query('//script[@src]');
        foreach ($scripts as $script) {
            $src = $script->getAttribute('src');
            $src = $this->makeUrlAbsolute($src, $url);
            
            $service = $this->identifyServiceFromUrl($src);
            if ($service) {
                $services[] = [
                    'type' => 'script',
                    'url' => $src,
                    'name' => $service['name'],
                    'category' => $service['category'],
                    'provider' => $service['provider_name'] ?? null
                ];
            }
        }
        
        // Process iframes
        $iframes = $xpath->query('//iframe[@src]');
        foreach ($iframes as $iframe) {
            $src = $iframe->getAttribute('src');
            $src = $this->makeUrlAbsolute($src, $url);
            
            $service = $this->identifyServiceFromUrl($src);
            if ($service) {
                $services[] = [
                    'type' => 'iframe',
                    'url' => $src,
                    'name' => $service['name'],
                    'category' => $service['category'],
                    'provider' => $service['provider_name'] ?? null
                ];
            }
        }
        
        return $services;
    }
    
    protected function scanJavaScriptFiles($html, $domain)
    {
        $services = [];
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        $xpath = new DOMXPath($dom);
        
        $scripts = $xpath->query('//script[@src]');
        foreach ($scripts as $script) {
            $src = $script->getAttribute('src');
            $src = $this->makeUrlAbsolute($src, $domain);
            
            try {
                $jsResponse = $this->client->get($src);
                $jsContent = (string)$jsResponse->getBody();
                
                foreach ($this->knownServices as $service) {
                    if (isset($service['script_patterns'])) {
                        $patterns = is_array($service['script_patterns']) 
                            ? $service['script_patterns'] 
                            : json_decode($service['script_patterns'], true);
                            
                        if (is_array($patterns)) {
                            foreach ($patterns as $pattern) {
                                if (strpos($jsContent, $pattern) !== false) {
                                    $services[] = [
                                        'type' => 'embedded_script',
                                        'url' => $src,
                                        'name' => $service['name'],
                                        'category' => $service['category'],
                                        'provider' => $service['provider_name'] ?? null,
                                        'pattern_match' => $pattern
                                    ];
                                    break 2;
                                }
                            }
                        }
                    }
                }
            } catch (RequestException $e) {
                continue;
            }
        }
        
        return $services;
    }


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
  

    protected function identifyServiceFromCookie($cookieName)
{
    foreach ($this->knownServices as $service) {
        if (isset($service['cookie_patterns'])) {
            $patterns = is_array($service['cookie_patterns']) 
                ? $service['cookie_patterns'] 
                : json_decode($service['cookie_patterns'], true);
                
            if (is_array($patterns)) {
                foreach ($patterns as $pattern) {
                    if ($this->matchPattern($cookieName, $pattern)) {
                        return $service;
                    }
                }
            }
        }
    }
    return null;
}

protected function matchPattern($string, $pattern)

    {

        // Convert wildcard pattern to regex

        $regex = str_replace(['*', '.'], ['.*', '\.'], $pattern);

        return (bool) preg_match('/^' . $regex . '$/i', $string);

    }



    
    // Add all other necessary helper methods (identifyServiceFromUrl, matchPattern, etc.)


public function capture($url)
    {
        // Validate the URL (you might want to add validation here)
        
        $width = 1024;
        $height = 768;
        
        try {
            // Create a unique filename
            $filename = 'screenshot_' . md5($url . time()) . '.jpg';
            $storagePath = 'public/screenshots';
            $fullStoragePath = storage_path('app/' . $storagePath);
            
            // Make sure the directory exists with proper permissions
            if (!file_exists($fullStoragePath)) {
                Storage::makeDirectory($storagePath);
                // Ensure directory has proper permissions
                chmod($fullStoragePath, 0755);
            }
            
            $outputPath = $fullStoragePath . '/' . $filename;
            
            // Try different Chrome/Chromium executable names based on environment
            $possibleExecutables = [
                'chrome',                    // Common name on some systems
                'google-chrome',             // Linux/Ubuntu name
                'chromium',                  // Chromium name
                'chromium-browser',          // Another Chromium name
                '"C:\Program Files\Google\Chrome\Application\chrome.exe"', // Windows path
                '"C:\Program Files (x86)\Google\Chrome\Application\chrome.exe"', // Windows x86 path
            ];
            
            $returnCode = 1; // Default to error
            $output = [];
            
            // Try each possible executable until one works
            foreach ($possibleExecutables as $executable) {
                $command = "{$executable} --headless --disable-gpu --screenshot={$outputPath} ";
                $command .= "--window-size={$width},{$height} {$url} 2>&1";
                
                exec($command, $output, $returnCode);
                
                if ($returnCode === 0) {
                    // Command succeeded, break the loop
                    break;
                }
            }
            
            if ($returnCode !== 0) {
                throw new \Exception('Failed to capture screenshot: ' . implode("\n", $output));
            }
            
            // Check if the file was actually created
            if (!file_exists($outputPath)) {
                throw new \Exception('Screenshot file was not created');
            }
            
            // Ensure proper permissions for the file
            chmod($outputPath, 0644);
            
            // For security, verify the file is actually an image
            $fileInfo = getimagesize($outputPath);
            if (!$fileInfo) {
                unlink($outputPath); // Remove invalid file
                throw new \Exception('Created file is not a valid image');
            }
            
            // Convert the image to base64
            $imageData = file_get_contents($outputPath);
            $base64Image = base64_encode($imageData);
            
            // Get image mime type for proper data URI format
            $mimeType = $fileInfo['mime'] ?? 'image/jpeg';
            
            // Optionally remove the file since we've already encoded it
            // Uncomment this if you don't want to keep the file in storage
            unlink($outputPath);
            
            // Return the base64 encoded image
            return response()->json([
                'success' => true,
                'message' => 'Screenshot captured successfully',
                'image_base64' => 'data:' . $mimeType . ';base64,' . $base64Image,
                'filename' => $filename,
                'file_size' => strlen($imageData) . ' bytes',
                'mime_type' => $mimeType,
                'dimensions' => $fileInfo[0] . 'x' . $fileInfo[1]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to capture screenshot',
                'error' => $e->getMessage()
            ], 500);
        }
    }
 
}