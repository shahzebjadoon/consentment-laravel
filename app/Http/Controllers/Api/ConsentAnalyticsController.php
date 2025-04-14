<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Analytics;
use App\Models\ConsentHistory;

class ConsentAnalyticsController extends Controller
{
    /**
     * Record analytics data
     */
    public function recordAnalytics(Request $request)
    {
        // Validate the request
        $request->validate([
            'settings_id' => 'required|exists:configurations,id',
            'consent' => 'required|array',
            'consent.choice' => 'required|string',
        ]);
        
        try {
            // Get the configuration
            $configId = $request->input('settings_id');
            $configuration = \App\Models\Configuration::findOrFail($configId);
            
            // Record consent history
            $consentHistory = new ConsentHistory();
            $consentHistory->company_id = $configuration->company_id;
            $consentHistory->configuration_id = $configId;
            $consentHistory->user_id = md5($request->ip() . $request->header('User-Agent'));
            $consentHistory->ip_address = $request->ip();
            $consentHistory->user_agent = $request->header('User-Agent');
            $consentHistory->consent_data = json_encode($request->input('consent'));
            $consentHistory->save();
            
            // Record analytics data for today
            $today = date('Y-m-d');
            
            // Get device info
            $deviceType = $this->getDeviceType($request->header('User-Agent'));
            $browser = $this->getBrowser($request->header('User-Agent'));
            $os = $this->getOS($request->header('User-Agent'));
            
            // Get country code (simplified, would need a proper GeoIP service in production)
            $country = 'US'; // Default to US for simplicity
            
            // Find or create analytics record
            $analytics = Analytics::firstOrNew([
                'company_id' => $configuration->company_id,
                'configuration_id' => $configId,
                'date' => $today,
                'country' => $country,
                'device_type' => $deviceType,
                'os' => $os,
                'browser' => $browser
            ]);
            
            // Increment the appropriate counter
            $analytics->displays = ($analytics->displays ?? 0) + 1;
            
            switch ($request->input('consent.choice')) {
                case 'acceptAll':
                    $analytics->accept_all = ($analytics->accept_all ?? 0) + 1;
                    break;
                case 'denyAll':
                    $analytics->deny_all = ($analytics->deny_all ?? 0) + 1;
                    break;
                case 'custom':
                    $analytics->custom_choice = ($analytics->custom_choice ?? 0) + 1;
                    break;
                default:
                    $analytics->interactions = ($analytics->interactions ?? 0) + 1;
                    break;
            }
            
            $analytics->save();
            
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Get device type from user agent
     */
    private function getDeviceType($userAgent)
    {
        if (preg_match('/(tablet|ipad|playbook|silk)|(android(?!.*mobile))/i', $userAgent)) {
            return 'tablet';
        }
        
        if (preg_match('/Mobile|Android|iP(hone|od)|IEMobile|BlackBerry|Kindle|Silk-Accelerated|Opera M(obi|ini)|Obigo|NetFront|Nokia|PalmSource|PocketPC|SonyEricsson|Symbian|SymbianOS|UP\.Browser|UP\.Link|Windows CE|Windows Phone/i', $userAgent)) {
            return 'mobile';
        }
        
        return 'desktop';
    }
    
    /**
     * Get browser name from user agent
     */
    private function getBrowser($userAgent)
    {
        if (preg_match('/MSIE|Trident/i', $userAgent)) {
            return 'Internet Explorer';
        }
        
        if (preg_match('/Firefox/i', $userAgent)) {
            return 'Firefox';
        }
        
        if (preg_match('/Chrome/i', $userAgent)) {
            return 'Chrome';
        }
        
        if (preg_match('/Safari/i', $userAgent)) {
            return 'Safari';
        }
        
        if (preg_match('/Opera|OPR/i', $userAgent)) {
            return 'Opera';
        }
        
        if (preg_match('/Edge/i', $userAgent)) {
            return 'Edge';
        }
        
        return 'Unknown';
    }
    
    /**
     * Get OS from user agent
     */
    private function getOS($userAgent)
    {
        if (preg_match('/Windows/i', $userAgent)) {
            return 'Windows';
        }
        
        if (preg_match('/Mac OS X/i', $userAgent)) {
            return 'Mac OS X';
        }
        
        if (preg_match('/Linux/i', $userAgent)) {
            return 'Linux';
        }
        
        if (preg_match('/Android/i', $userAgent)) {
            return 'Android';
        }
        
        if (preg_match('/iOS|iPhone|iPad|iPod/i', $userAgent)) {
            return 'iOS';
        }
        
        return 'Unknown';
    }
}