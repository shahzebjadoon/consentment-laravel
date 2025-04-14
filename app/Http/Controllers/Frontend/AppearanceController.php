<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Configuration;
use App\Models\AppearanceSettings;

class AppearanceController extends Controller
{
    /**
     * Display the Layout page
     */
    public function layout($company_id, $config_id)
    {
        $company = Company::findOrFail($company_id);
        $configuration = Configuration::findOrFail($config_id);
        $appearance = AppearanceSettings::firstOrNew(['configuration_id' => $config_id]);
        
        return view('frontend.appearance.layout', [
            'company' => $company,
            'configuration' => $configuration,
            'appearance' => $appearance,
            'activeTab' => 'layout'
        ]);
    }
    
    /**
     * Display the Styling page
     */
    public function styling($company_id, $config_id)
    {
        $company = Company::findOrFail($company_id);
        $configuration = Configuration::findOrFail($config_id);
        $appearance = AppearanceSettings::firstOrNew(['configuration_id' => $config_id]);
        
        return view('frontend.appearance.styling', [
            'company' => $company,
            'configuration' => $configuration,
            'appearance' => $appearance,
            'activeTab' => 'styling'
        ]);
    }
    
    /**
     * Save appearance settings
     */
    public function saveAppearance(Request $request, $company_id, $config_id)
    {
        $company = Company::findOrFail($company_id);
        $configuration = Configuration::findOrFail($config_id);
        
        // Validate request
        $request->validate([
            'layout_type' => 'nullable|string',
            'show_deny_all' => 'nullable|boolean',
            'background_color' => 'nullable|string|max:10',
            'text_color' => 'nullable|string|max:10',
            'link_color' => 'nullable|string|max:10',
            'accent_color' => 'nullable|string|max:10',
            'border_radius' => 'nullable|integer',
            'background_overlay' => 'nullable|boolean',
            'overlay_color' => 'nullable|string|max:10',
            'overlay_opacity' => 'nullable|integer|min:0|max:100',
            'logo_url' => 'nullable|string|max:255',
            'font_family' => 'nullable|string|max:100',
            'deny_button_bg' => 'nullable|string|max:10',
            'deny_button_text' => 'nullable|string|max:10',
            'save_button_bg' => 'nullable|string|max:10',
            'save_button_text' => 'nullable|string|max:10',
            'button_corner_radius' => 'nullable|integer',
            // Add other fields as needed
        ]);
        
        // Find or create appearance settings
        $appearance = AppearanceSettings::firstOrNew([
            'configuration_id' => $config_id
        ]);
        
        // Set company ID if it's a new record
        if (!$appearance->exists) {
            $appearance->company_id = $company_id;
            $appearance->configuration_id = $config_id;
        }
        
        // Update fields from request
        $fillableFields = $appearance->getFillable();
        foreach ($fillableFields as $field) {
            if ($request->has($field)) {
                $appearance->$field = $request->input($field);
            }
        }
        
        // Save the appearance settings
        $appearance->save();
        
        // Redirect back with success message
        return redirect()
            ->back()
            ->with('success', 'Appearance settings saved successfully.');
    }
}