<?php

namespace App\Services;

use App\Models\DpsScan;
use App\Models\DpsLibrary;
use App\Models\Configuration;
use App\Models\DataProcessingServices;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use DOMDocument;
use DOMXPath;

class DpsScanner
{
    protected $client;
    protected $knownServices = null;
    
    /**
     * Constructor - initialize HTTP client and load known services
     */
    public function __construct()
    {
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
            ]
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
                   
            // Get the main page HTML
            $response = $this->client->get($domain);
            $html = (string) $response->getBody();
            
            // Process the HTML
            $results = $this->processHtml($html, $domain, $configuration);
            
            // Also check the cookie consent/privacy page if it exists
            $privacyUrls = $this->findPrivacyPages($html, $domain);
            
            foreach ($privacyUrls as $privacyUrl) {
                try {
                    $privacyResponse = $this->client->get($privacyUrl);
                    $privacyHtml = (string) $privacyResponse->getBody();
                    $this->processHtml($privacyHtml, $privacyUrl, $configuration, $domain);
                } catch (RequestException $e) {
                    Log::error("Error scanning privacy page: " . $e->getMessage());
                }
            }
            
            // Also scan JavaScript files for additional services
            $this->scanJavaScriptFiles($html, $domain, $configuration);
            
            // Add detected services to the DataProcessingServices table
            $this->addDetectedServicesToDPS($configuration);
            
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
            
            if (isset($parsedScriptUrl['host']) && $parsedScriptUrl['host'] === $parsedDomainUrl['host']) {
                try {
                    $jsResponse = $this->client->get($src);
                    $jsContent = (string) $jsResponse->getBody();
                    
                    // Search for known services in the JavaScript content
                    $this->scanCodeForServices($jsContent, $domainUrl, $configuration);
                } catch (RequestException $e) {
                    Log::error("Error scanning JavaScript file: " . $e->getMessage());
                }
            }
        }
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
            // Skip if no src attribute
            if (!$script->hasAttribute('src')) continue;
            
            $src = $script->getAttribute('src');
            $src = $this->makeUrlAbsolute($src, $url);
            
            $parsedUrl = parse_url($src);
            
            // Only process external resources
            if (isset($parsedUrl['host']) && strtolower($parsedUrl['host']) !== strtolower($currentDomainHost)) {
                $serviceData = $this->identifyService($src);
                
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
                $serviceData = $this->identifyService($src);
                
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
                $serviceData = $this->identifyService($href);
                
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
                $serviceData = $this->identifyService($src);
                
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
                $serviceData = $this->identifyService($src);
                
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
        
        // Scan for inline scripts that could contain tracking pixel code
        $inlineScripts = $xpath->query('//script[not(@src)]');
        foreach ($inlineScripts as $script) {
            $content = $script->textContent;
            $this->scanCodeForServices($content, $url, $configuration);
        }
        
        return $results;
    }
    
    /**
     * Identify a service from a URL by matching with the library
     *
     * @param string $url
     * @return array|null
     */
    protected function identifyService($url)
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
     * @return \App\Models\DpsScan
     */
    protected function saveDetectedService($configuration, $serviceName, $serviceUrl, $category, $sourceDomain, $libraryId = null)
    {
        // Extract domain from URL if possible
        $domain = $serviceUrl;
        if ($serviceUrl) {
            $parsedUrl = parse_url($serviceUrl);
            if (isset($parsedUrl['host'])) {
                $domain = $parsedUrl['host'];
            }
        }
        
        // Set a default name if none is provided
        if (empty($serviceName) && !empty($domain)) {
            // Try to extract a meaningful name from the domain
            $domainParts = explode('.', $domain);
            if (count($domainParts) >= 2) {
                $serviceName = 'Unknown: ' . ucfirst($domainParts[count($domainParts) - 2]);
            } else {
                $serviceName = 'Unknown Service';
            }
        } else if (empty($serviceName)) {
            $serviceName = 'Unknown Service';
        }
        
        // Check if this service already exists for this configuration
        $existingScan = DpsScan::where('configuration_id', $configuration->id)
            ->where(function($query) use ($serviceName, $domain) {
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
                $existingScan->save();
            }
            
            return $existingScan;
        }
        
        // Create new scan record
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
            'scan_date' => now(),
        ]);
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
        
        // Get all pending scans for this configuration
        $pendingScans = DpsScan::where('configuration_id', $configuration->id)
            ->where('status', 'todo')
            ->get();
        
        // Group by service name to avoid duplicates
        $uniqueServices = [];
        foreach ($pendingScans as $scan) {
            $serviceName = $scan->service_name ?? ('Unknown: ' . $scan->domain);
            
            // Skip certain resource files we don't want to track
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
                    'template_id' => null,
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
        
        return $added;
    }
    
    /**
     * Process unidentified services - either match with existing ones or mark as unknown
     * 
     * @param int $configId
     * @return array
     */
    public function processUnidentifiedServices($configId)
    {
        $processed = [];
        
        // Get all unidentified services
        $unidentifiedScans = DpsScan::where('configuration_id', $configId)
            ->whereNotNull('domain')
            ->whereNull('service_name')
            ->where('status', 'todo')
            ->get();
        
        // Try to identify based on known patterns
        foreach ($unidentifiedScans as $scan) {
            $matched = false;
            
            // Try to match with any known domain patterns
            foreach ($this->knownServices as $domainPattern => $service) {
                if (strpos($scan->domain, $domainPattern) !== false) {
                    // Found a match in the library
                    $scan->service_name = $service['name'];
                    $scan->category = $service['category'];
                    $scan->library_id = $service['id'];
                    $scan->save();
                    
                    $processed[] = $scan;
                    $matched = true;
                    break;
                }
            }
            
            // If no direct match was found, try parent service matching using the library
            if (!$matched) {
                $domainParts = explode('.', $scan->domain);
                
                foreach ($domainParts as $part) {
                    $part = strtolower($part);
                    
                    // Check each service in the library to see if the domain part matches
                    foreach ($this->knownServices as $domainPattern => $service) {
                        $patternParts = explode('.', strtolower($domainPattern));
                        
                        if (in_array($part, $patternParts)) {
                            $scan->service_name = $service['name'];
                            $scan->category = $service['category'];
                            $scan->library_id = $service['id'];
                            $scan->save();
                            
                            $processed[] = $scan;
                            $matched = true;
                            break 2; // Break both loops
                        }
                    }
                }
            }
            
            // If still no match was found, mark as Unknown with the domain name
            if (!$matched) {
                // Extract domain name for generic service name
                $domainParts = explode('.', $scan->domain);
                if (count($domainParts) >= 2) {
                    $serviceName = 'Unknown: ' . ucfirst($domainParts[count($domainParts) - 2]);
                    $scan->service_name = $serviceName;
                    $scan->category = 'other'; // Default category for unknown services
                    $scan->save();
                    
                    $processed[] = $scan;
                } else {
                    // For really weird domains that don't have enough parts
                    $scan->service_name = 'Unknown Service';
                    $scan->category = 'other';
                    $scan->save();
                    
                    $processed[] = $scan;
                }
            }
        }
        
        return $processed;
    }
    
    /**
     * Learn new services from scanning
     * Modified to match services with library instead of creating new entries
     * 
     * @param int $configId
     * @return array
     */
    public function learnNewServices($configId)
    {
        $added = [];
        
        // Get configuration info
        $configInfo = Configuration::find($configId);
        if (!$configInfo) return $added;
        
        // First, process any unidentified services
        $this->processUnidentifiedServices($configId);
        
        // Then add all services to DPS
        $added = $this->addDetectedServicesToDPS($configInfo);
        
        return $added;
    }
}