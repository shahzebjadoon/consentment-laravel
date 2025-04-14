<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Configuration;
use App\Models\ContentSettings; // Assuming you have this model

class ContentController extends Controller
{
    /**
     * Display the First Layer page
     */
    public function firstLayer($company_id, $config_id)
    {
        $company = Company::findOrFail($company_id);
        $configuration = Configuration::findOrFail($config_id);
        
        // Get content settings if they exist
        $contentSettings = ContentSettings::where('company_id', $company_id)
            ->where('configuration_id', $config_id)
            ->first();
        
        return view('frontend.content.first-layer', [
            'company' => $company,
            'configuration' => $configuration,
            'contentSettings' => $contentSettings,
            'activeTab' => 'first-layer'
        ]);
    }
    
    /**
     * Display the Second Layer page
     */
    public function secondLayer($company_id, $config_id)
    {
        $company = Company::findOrFail($company_id);
        $configuration = Configuration::findOrFail($config_id);
        
        // Get content settings if they exist
        $contentSettings = ContentSettings::where('company_id', $company_id)
            ->where('configuration_id', $config_id)
            ->first();
        
        return view('frontend.content.second-layer', [
            'company' => $company,
            'configuration' => $configuration,
            'contentSettings' => $contentSettings,
            'activeTab' => 'second-layer'
        ]);
    }
    
    /**
     * Display the Labels page
     */
/**
 * Display the Labels page
 */
public function labels($company_id, $config_id)
{
    $company = Company::findOrFail($company_id);
    $configuration = Configuration::findOrFail($config_id);
    
    // Get content settings if they exist
    $contentSettings = ContentSettings::where('company_id', $company_id)
        ->where('configuration_id', $config_id)
        ->first();
    
    return view('frontend.content.labels', [
        'company' => $company,
        'configuration' => $configuration,
        'contentSettings' => $contentSettings,
        'activeTab' => 'labels'
    ]);
}
    
    /**
 * Save content settings
 */
public function saveContent(Request $request, $company_id, $config_id)
{
    $company = Company::findOrFail($company_id);
    $configuration = Configuration::findOrFail($config_id);
    
   // Validate the request
$validated = $request->validate([
    // First Layer fields
    'first_layer_title' => 'nullable|string|max:255',
    'first_layer_message' => 'nullable|string',
    'mobile_specific_message' => 'boolean',
    'mobile_message' => 'nullable|string',
    'legal_notice_text' => 'nullable|string|max:255',
    'legal_notice_url' => 'nullable|string|max:255',
    'privacy_policy_text' => 'nullable|string|max:255',
    'privacy_policy_url' => 'nullable|string|max:255',
    // Second Layer fields
    'second_layer_title' => 'nullable|string|max:255',
    'second_layer_description' => 'nullable|string',
    'services_title' => 'nullable|string|max:255',
    'services_description' => 'nullable|string',
    'categories_title' => 'nullable|string|max:255',
    'categories_description' => 'nullable|string',
    'accept_all_button' => 'nullable|string|max:255',
    'deny_all_button' => 'nullable|string|max:255',
    'save_button' => 'nullable|string|max:255',
    // Labels fields
    'accept_button_label' => 'nullable|string|max:100',
    'deny_button_label' => 'nullable|string|max:100',
    'more_info_label' => 'nullable|string|max:100',
    'service_provider_label' => 'nullable|string|max:100',
    'privacy_policy_label' => 'nullable|string|max:100',
    'legitimate_interest_label' => 'nullable|string|max:100',
    'storage_info_label' => 'nullable|string|max:100',
    'save_settings_label' => 'nullable|string|max:100',
    'accept_selected_label' => 'nullable|string|max:100',
    'essential_category_label' => 'nullable|string|max:100',
    'marketing_category_label' => 'nullable|string|max:100',
    'functional_category_label' => 'nullable|string|max:100',
    'analytics_category_label' => 'nullable|string|max:100',
    'active_status_label' => 'nullable|string|max:100',
    'inactive_status_label' => 'nullable|string|max:100',
    'required_status_label' => 'nullable|string|max:100'
]);
    
    // Find existing settings or create new ones
    $contentSettings = ContentSettings::updateOrCreate(
        [
            'company_id' => $company_id,
            'configuration_id' => $config_id
        ],
        $validated // This will automatically handle all validated fields from the request
    );
    
    // Determine which tab to redirect back to
    $tab = 'first-layer'; // Default
    if ($request->has('second_layer_title')) {
        $tab = 'second-layer';
    } else if ($request->has('accept_button_label')) {
        $tab = 'labels';
    }
    
    // Redirect back with success message
    return redirect()
        ->route("frontend.content.{$tab}", ['company_id' => $company_id, 'config_id' => $config_id])
        ->with('success', 'Content settings saved successfully!');
}
}