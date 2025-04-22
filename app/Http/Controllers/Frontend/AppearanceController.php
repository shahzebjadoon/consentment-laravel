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
         'logo_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
    
    // Handle logo upload
    if ($request->hasFile('logo_file')) {
        // Delete old logo if exists
        if ($appearance->logo_url && file_exists(public_path($appearance->logo_url))) {
            unlink(public_path($appearance->logo_url));
        }
        
        $logoFile = $request->file('logo_file');
        $filename = 'logo_' . time() . '_' . $company_id . '.' . $logoFile->getClientOriginalExtension();
        $path = 'hzere1a/'; // Directory in public folder
        
        // Make sure directory exists
        if (!file_exists(public_path($path))) {
            mkdir(public_path($path), 0755, true);
        }
        
        // Move the file to public folder
        $logoFile->move(public_path($path), $filename);
        
        // Save the path to database
        $appearance->logo_url = '/' . $path . $filename;
    }
    
    // Remove logo if requested
    if ($request->has('remove_logo') && $request->input('remove_logo') == '1') {
        if ($appearance->logo_url && file_exists(public_path($appearance->logo_url))) {
            unlink(public_path($appearance->logo_url));
        }
        $appearance->logo_url = null;
    }
    
    // Update fields from request
    $fillableFields = $appearance->getFillable();
    foreach ($fillableFields as $field) {
        if ($request->has($field) && $field !== 'logo_url') { // Skip logo_url as we handled it separately
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