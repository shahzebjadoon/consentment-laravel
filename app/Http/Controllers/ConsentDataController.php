<?php

namespace App\Http\Controllers;

use App\Models\ConsentAnalytics;
use App\Models\ConsentHistory;
use App\Models\Configuration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Log;

class ConsentDataController extends Controller
{
    /**
     * Record consent analytics data
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function recordAnalytics(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'settings_id' => 'required',
            'consent' => 'required|array',
            'consent.choice' => 'required|string'
        ]);

        if ($validator->fails()) {
            Log::error('Invalid consent analytics data', [
                'errors' => $validator->errors()->toArray(),
                'data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Invalid data provided',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            // Find the configuration directly by ID
            // The settings_id from the client is actually just the configuration ID
            $configuration = Configuration::find($request->settings_id);
            
            if (!$configuration) {
                Log::error('Configuration not found for consent analytics', [
                    'configuration_id' => $request->settings_id
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Configuration not found'
                ], 404);
            }

            // Parse user agent for device information
            $agent = new Agent();
            $agent->setUserAgent($request->user_agent ?? $request->header('User-Agent'));
            
            $deviceType = $agent->isPhone() ? 'phone' : 
                         ($agent->isTablet() ? 'tablet' : 
                         ($agent->isDesktop() ? 'desktop' : 'other'));
            
            $browser = $agent->browser();
            $platform = $agent->platform();
            
            // Get country from IP (you may need to install a geolocation package)
            $country = $this->getCountryFromIP($request->ip());
            
            // Determine what kind of consent choice was made
            $choice = $request->consent['choice'];
            
            // Update daily analytics - using firstOrCreate to handle the aggregation correctly
            $analytics = ConsentAnalytics::firstOrCreate(
                [
                    'company_id' => $configuration->company_id,
                    'configuration_id' => $configuration->id,
                    'date' => now()->toDateString(),
                    'device_type' => $deviceType,
                    'os' => $platform,
                    'browser' => $browser,
                    'country' => $country ?? 'XX'
                ],
                [
                    'displays' => 0,
                    'interactions' => 0,
                    'ignores' => 0,
                    'accept_all' => 0,
                    'deny_all' => 0,
                    'custom_choice' => 0
                ]
            );
            
            // Increment the appropriate counter
            if ($choice === 'acceptAll') {
                $analytics->accept_all += 1;
            } elseif ($choice === 'denyAll') {
                $analytics->deny_all += 1;
            } elseif ($choice === 'custom') {
                $analytics->custom_choice += 1;
            }
            
            // Also increment displays and interactions
            $analytics->displays += 1;
            $analytics->interactions += 1;
            
            $analytics->save();
            
            // Also record detailed consent history
            $consentHistory = new ConsentHistory([
                'company_id' => $configuration->company_id,
                'configuration_id' => $configuration->id,
                'user_id' => $request->consent_id ?? ($request->consent['consent_id'] ?? null),
                'ip_address' => $request->ip(),
                'user_agent' => $request->user_agent ?? $request->header('User-Agent'),
                'consent_data' => $request->consent
            ]);
            
            $consentHistory->save();
            
            Log::info('Consent analytics recorded', [
                'configuration_id' => $configuration->id,
                'choice' => $choice
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Consent analytics recorded successfully'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error recording consent analytics', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'settings_id' => $request->settings_id
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to record consent analytics: ' . $e->getMessage()
            ], 500);
        }
    }
    
 /**
 * Get country code from IP address
 *
 * @param string $ip
 * @return string
 */
private function getCountryFromIP($ip)
{
    // Skip lookup for localhost
    if ($ip === '127.0.0.1' || $ip === '::1') {
        return 'Localhost'; // Localhost
    }
    
    try {
        $reader = new \GeoIp2\Database\Reader(storage_path('app/GeoLite2-Country.mmdb'));
        $record = $reader->country($ip);
        
        // Return full country name instead of ISO code
        return $record->country->name;
    } catch (\Exception $e) {
        Log::warning('Failed to get country from IP', [
            'ip' => $ip,
            'error' => $e->getMessage()
        ]);
        return 'Unknown';
    }
}
}