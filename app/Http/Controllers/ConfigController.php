<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Configuration;
use App\Models\AppearanceSettings;
use App\Models\ContentSettings;
use App\Models\Analytics;
use App\Models\ConsentHistory;

class ConfigController extends Controller
{
    /**
     * Get configuration by settings ID
     */
    public function getConfig($settingsId)
    {
        // Find configuration
        $configuration = Configuration::where('hash_key', $settingsId)->first();
        
        if (!$configuration) {
            return response()->json(['error' => 'Configuration not found'], 404);
        }
        
        // Get appearance and content settings
        $appearance = AppearanceSettings::where('configuration_id', $configuration->id)->first();
        $content = ContentSettings::where('configuration_id', $configuration->id)->first();
        
        // Build config object
        $config = [
            'settings_id' => $settingsId,
            'framework_type' => $configuration->framework_type,
            'appearance' => $appearance ? $this->formatAppearance($appearance) : $this->getDefaultAppearance(),
            'content' => $content ? $this->formatContent($content) : $this->getDefaultContent(),
            'services' => $this->getServices($configuration->id),
        ];
        
        return response()->json($config);
    }
    
    /**
     * Record analytics data
     */
    public function recordAnalytics(Request $request)
    {
        try {
            // Get config ID
            $configId = $request->input('settings_id');
            $configuration = Configuration::findOrFail($configId);
            
            // Record consent in history
            $history = new ConsentHistory();
            $history->company_id = $configuration->company_id;
            $history->configuration_id = $configId;
            $history->user_id = md5($request->ip() . $request->header('User-Agent'));
            $history->ip_address = $request->ip();
            $history->user_agent = $request->header('User-Agent');
            $history->consent_data = json_encode($request->input('consent'));
            $history->save();
            
            // Record analytics
            $analytics = new Analytics();
            $analytics->company_id = $configuration->company_id;
            $analytics->configuration_id = $configId;
            $analytics->date = date('Y-m-d');
            $analytics->displays = 1;
            
            switch ($request->input('consent.choice')) {
                case 'acceptAll':
                    $analytics->accept_all = 1;
                    break;
                case 'denyAll':
                    $analytics->deny_all = 1;
                    break;
                case 'custom':
                    $analytics->custom_choice = 1;
                    break;
            }
            
            $analytics->save();
            
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    // Helper methods for formatting data...
   private function formatAppearance($appearance)
{
    // Map all appearance_settings fields to the response format
    return [
        'layout_type' => $appearance->layout_type ?? 'dialog',
        'show_deny_all' => (bool)($appearance->show_deny_all ?? 1),
        'more_info_type' => $appearance->more_info_type ?? 'link',
        'hide_language_switch' => (bool)($appearance->hide_language_switch ?? 0),
        'background_color' => $appearance->background_color ?? '#FFFFFF',
        'text_color' => $appearance->text_color ?? '#000000',
        'link_color' => $appearance->link_color ?? '#0066CC',
        'tab_color' => $appearance->tab_color ?? '#0066CC',
        'accent_color' => $appearance->accent_color ?? '#D9D9D9',
        'border_radius' => (int)($appearance->border_radius ?? 8),
        'background_shadow' => (bool)($appearance->background_shadow ?? 0),
        'background_overlay' => (bool)($appearance->background_overlay ?? 0),
        'overlay_color' => $appearance->overlay_color ?? '#000000',
        'overlay_opacity' => (int)($appearance->overlay_opacity ?? 70),
        'logo_url' => $appearance->logo_url,
        'logo_position' => $appearance->logo_position ?? 'left',
        'logo_alt_tag' => $appearance->logo_alt_tag,
        'font_family' => $appearance->font_family ?? 'System fonts',
        'font_size' => $appearance->font_size ?? 'Regular (14px)',
        'deny_button_bg' => $appearance->deny_button_bg ?? '#CF7A7A',
        'deny_button_text' => $appearance->deny_button_text ?? '#FFFFFF',
        'save_button_bg' => $appearance->save_button_bg ?? '#CF7A7A',
        'save_button_text' => $appearance->save_button_text ?? '#FFFFFF',
        'button_corner_radius' => (int)($appearance->button_corner_radius ?? 4),
        'active_toggle_bg' => $appearance->active_toggle_bg ?? '#888888',
        'active_toggle_icon' => $appearance->active_toggle_icon ?? '#FFFFFF',
        'inactive_toggle_bg' => $appearance->inactive_toggle_bg ?? '#696A80',
        'inactive_toggle_icon' => $appearance->inactive_toggle_icon ?? '#CF7A7A',
        'disabled_toggle_bg' => $appearance->disabled_toggle_bg ?? '#CF7A7A',
        'disabled_toggle_icon' => $appearance->disabled_toggle_icon ?? '#FFFFFF',
        'privacy_button_icon' => $appearance->privacy_button_icon ?? 'fingerprint',
        'privacy_button_bg' => $appearance->privacy_button_bg ?? '#0045A5',
        'privacy_button_icon_color' => $appearance->privacy_button_icon_color ?? '#FFFFFF',
        'privacy_button_desktop_size' => $appearance->privacy_button_desktop_size ?? 'Large - 50px',
        'privacy_button_mobile_size' => $appearance->privacy_button_mobile_size ?? 'Small - 48px',
        'custom_css_enabled' => (bool)($appearance->custom_css_enabled ?? 0),
        'custom_css' => $appearance->custom_css,
    ];
}
    
    private function formatContent($content)
{
    // Format content settings...
    return [
        'first_layer_title' => $content->first_layer_title ?? 'Privacy Settings',
        'first_layer_message' => $content->first_layer_message ?? 'This site uses cookies.',
        'accept_button_label' => $content->accept_button_label ?? 'Accept All',
'deny_button_label' => $content->deny_button_label ?? 'Deny',
'accept_all_button' => $content->accept_all_button ?? 'Accept All',
'deny_all_button' => $content->deny_all_button ?? 'Deny',
        'save_button' => $content->save_button ?? 'Save Settings',
        'services_title' => $content->services_title ?? 'Services',
        'services_description' => $content->services_description ?? 'These services process personal data to display personalized or interest-based advertisements.',
        'categories_title' => $content->categories_title ?? 'Categories',
        'categories_description' => $content->categories_description ?? 'These categories group services by their data processing purpose.',
        // Add more content settings as needed...
    ];
}
    
    private function getServices($configId)
    {
        // Get services for configuration...
        return [
            [
                'id' => 'essential',
                'name' => 'Essential',
                'is_essential' => true,
                'description' => 'Essential cookies for site functionality',
            ],
            [
                'id' => 'marketing',
                'name' => 'Marketing',
                'is_essential' => false,
                'description' => 'Marketing cookies for personalized ads',
            ],
            [
                'id' => 'functional',
                'name' => 'Functional',
                'is_essential' => false,
                'description' => 'Functional cookies for enhanced features',
            ],
        ];
    }
    
    private function getDefaultAppearance()
    {
        return [
            'layout_type' => 'wall',
            'background_color' => '#FFFFFF',
            'text_color' => '#000000',
            'link_color' => '#0066CC',
            'border_radius' => 8,
        ];
    }
    
    private function getDefaultContent()
{
    return [
        'first_layer_title' => 'Privacy Settings',
        'first_layer_message' => 'This site uses third-party website tracking technologies to provide and continually improve our services, and to display advertisements according to users\' interests. I agree and may revoke or change my consent at any time with effect for the future.',
        'privacy_policy_text' => 'Privacy Policy',
        'accept_all_button' => 'Accept All',
        'deny_all_button' => 'Deny',
        'save_button' => 'Save Settings',
        'accept_button_label' => 'Accept All',
        'deny_button_label' => 'Deny',
        'services_title' => 'Services',
        'services_description' => 'These services process personal data to display personalized or interest-based advertisements.',
        'categories_title' => 'Categories',
        'categories_description' => 'These categories group services by their data processing purpose.',
    ];
}
}