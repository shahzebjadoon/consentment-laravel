<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Configuration;
use App\Models\AppearanceSettings;
use App\Models\ContentSettings;
use App\Models\DataProcessingServices;
use App\Models\ServiceCategories;

class ConsentConfigController extends Controller
{
    /**
     * Get configuration for a specific domain
     */
    public function getConfig(Request $request, $settingsId)
    {
        // Find configuration by settings ID
        $configuration = Configuration::where('id', $settingsId)->first();
        
        if (!$configuration) {
            return response()->json(['error' => 'Configuration not found'], 404);
        }
        
        // Get related settings
        $appearance = AppearanceSettings::where('configuration_id', $configuration->id)->first();
        $content = ContentSettings::where('configuration_id', $configuration->id)->first();
        
        // Get services and categories
        $services = DataProcessingServices::where('configuration_id', $configuration->id)->get();
        $categories = ServiceCategories::where('configuration_id', $configuration->id)->get();
        
        // Build config object
        $config = [
            'settings_id' => $settingsId,
            'framework_type' => $configuration->framework_type,
            'framework_region' => $configuration->framework_region,
            'appearance' => $appearance ? $this->formatAppearance($appearance) : $this->getDefaultAppearance(),
            'content' => $content ? $this->formatContent($content) : $this->getDefaultContent(),
            'services' => $this->formatServices($services, $categories),
        ];
        
        return response()->json($config);
    }
    
    /**
     * Format appearance settings
     */
    private function formatAppearance($appearance)
    {
        return [
            'layout_type' => $appearance->layout_type ?? 'wall',
            'show_deny_all' => (bool)($appearance->show_deny_all ?? true),
            'background_color' => $appearance->background_color ?? '#FFFFFF',
            'text_color' => $appearance->text_color ?? '#000000',
            'link_color' => $appearance->link_color ?? '#0066CC',
            'accent_color' => $appearance->accent_color ?? '#D9D9D9',
            'border_radius' => $appearance->border_radius ?? 8,
            'background_overlay' => (bool)($appearance->background_overlay ?? true),
            'overlay_color' => $appearance->overlay_color ?? '#000000',
            'overlay_opacity' => $appearance->overlay_opacity ?? 70,
            'logo_url' => $appearance->logo_url,
            'font_family' => $appearance->font_family ?? 'Arial, sans-serif',
            'deny_button_bg' => $appearance->deny_button_bg ?? '#CF7A7A',
            'deny_button_text' => $appearance->deny_button_text ?? '#FFFFFF',
            'save_button_bg' => $appearance->save_button_bg ?? '#CF7A7A',
            'save_button_text' => $appearance->save_button_text ?? '#FFFFFF',
            'button_corner_radius' => $appearance->button_corner_radius ?? 4,
        ];
    }
    
    /**
     * Format content settings
     */
    private function formatContent($content)
    {
        return [
            'first_layer_title' => $content->first_layer_title ?? 'Privacy Settings',
            'first_layer_message' => $content->first_layer_message ?? 'We use cookies to provide and improve our services. By using our site, you consent to cookies.',
            'privacy_policy_text' => $content->privacy_policy_text ?? 'Privacy Policy',
            'privacy_policy_url' => $content->privacy_policy_url,
            'accept_all_button' => $content->accept_all_button ?? 'Accept All',
            'deny_all_button' => $content->deny_all_button ?? 'Deny All',
            'save_button' => $content->save_button ?? 'Save',
        ];
    }
    
    /**
     * Format services and categories
     */
    private function formatServices($services, $categories)
    {
        $formattedCategories = [];
        
        // Format categories
        foreach ($categories as $category) {
            $formattedCategories[$category->id] = [
                'id' => $category->id,
                'name' => $category->name,
                'description' => $category->description,
                'is_essential' => (bool)$category->is_essential,
                'services' => [],
            ];
        }
        
        // Add services to categories
        foreach ($services as $service) {
            if (isset($formattedCategories[$service->category])) {
                $formattedCategories[$service->category]['services'][] = [
                    'id' => $service->id,
                    'name' => $service->name,
                    'status' => $service->status,
                    'is_essential' => (bool)$service->is_essential,
                ];
            }
        }
        
        return array_values($formattedCategories);
    }
    
    /**
     * Get default appearance settings
     */
    private function getDefaultAppearance()
    {
        return [
            'layout_type' => 'wall',
            'show_deny_all' => true,
            'background_color' => '#FFFFFF',
            'text_color' => '#000000',
            'link_color' => '#0066CC',
            'accent_color' => '#D9D9D9',
            'border_radius' => 8,
            'background_overlay' => true,
            'overlay_color' => '#000000',
            'overlay_opacity' => 70,
            'font_family' => 'Arial, sans-serif',
            'deny_button_bg' => '#CF7A7A',
            'deny_button_text' => '#FFFFFF',
            'save_button_bg' => '#CF7A7A',
            'save_button_text' => '#FFFFFF',
            'button_corner_radius' => 4,
        ];
    }
    
    /**
     * Get default content settings
     */
    private function getDefaultContent()
    {
        return [
            'first_layer_title' => 'Privacy Settings',
            'first_layer_message' => 'We use cookies to provide and improve our services. By using our site, you consent to cookies.',
            'privacy_policy_text' => 'Privacy Policy',
            'accept_all_button' => 'Accept All',
            'deny_all_button' => 'Deny All',
            'save_button' => 'Save',
        ];
    }
}