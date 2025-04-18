<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Configuration;
use App\Models\DpsScan;
use App\Models\DataProcessingServices;
use App\Models\ServiceCategory;
use App\Models\CategoryTranslation;

class ServiceSettingsController extends Controller
{
    /**
     * Display the DPS Scanner page
     */
    public function scanner($company_id, $config_id)
    {
        $company = Company::findOrFail($company_id);
        $configuration = Configuration::findOrFail($config_id);
        
        // Get domain from configuration
        $domain = $configuration->domain;
        
        // Get all DPS scans for this configuration with freshest data first
        $scans = DpsScan::where('configuration_id', $config_id)
                    ->orderBy('scan_date', 'desc')
                    ->get();
        
        // Get the last scan date directly from implementation settings (avoiding cache)
        $implementationSettings = \App\Models\ImplementationSettings::where('configuration_id', $config_id)
                                    ->first();
        
        $lastScanDate = $implementationSettings ? $implementationSettings->last_scan_date : null;
        $scanFrequency = $implementationSettings ? $implementationSettings->scan_frequency : 'monthly';
        
        // Count todo items
        $todoCount = DpsScan::where('configuration_id', $config_id)
                    ->where('status', 'todo')
                    ->count();
        
        return view('frontend.service-settings.service-settings', [
            'company' => $company,
            'configuration' => $configuration,
            'domain' => $domain,
            'lastScanDate' => $lastScanDate,
            'scanFrequency' => $scanFrequency,
            'todoCount' => $todoCount,
            'scans' => $scans,
            'activeTab' => 'scanner'
        ]);
    }
    
    /**
     * Display the Data Processing Services page
     */
    public function services($company_id, $config_id)
    {
        $company = Company::findOrFail($company_id);
        $configuration = Configuration::findOrFail($config_id);
        
        // Get DPS scans that have been added (not in "todo" status)
        $addedServices = DpsScan::where('configuration_id', $config_id)
            ->where('status', 'added')
            ->orderBy('service_name')
            ->get();
        
        // Get data processing services that were manually added
        // Using the correct model name (DataProcessingServices)
        $manualServices = DataProcessingServices::where('configuration_id', $config_id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
        
        // Merge the collections
        $services = $addedServices->concat($manualServices);
        
        return view('frontend.service-settings.data-processing-services', [
            'company' => $company,
            'configuration' => $configuration,
            'services' => $services,
            'activeTab' => 'services'
        ]);
    }
    
    /**
     * Display the Categories page
     */
    public function categories($company_id, $config_id)
    {
        $company = Company::findOrFail($company_id);
        $configuration = Configuration::findOrFail($config_id);
        
        // Get categories with their services
        $categories = ServiceCategory::where('configuration_id', $config_id)
            ->orderBy('order_index')
            ->get();
        
        // Get services for each category
        foreach ($categories as $category) {
            $category->servicesList = DataProcessingServices::where('configuration_id', $config_id)
                ->where('category', $category->identifier)
                ->where('status', 'active')
                ->get();
        }
        
        return view('frontend.service-settings.categories', [
            'company' => $company,
            'configuration' => $configuration,
            'categories' => $categories,
            'activeTab' => 'categories'
        ]);
    }
    
    /**
     * Trigger a DPS scan for the domain
     */
    public function startScan(Request $request, $company_id, $config_id)
{
    $configuration = Configuration::findOrFail($config_id);
    
    // Run the scanner only
    $scanner = new \App\Services\DpsScanner();
    $result = $scanner->scanDomain($configuration);
    
    // Update the last scan date
    \App\Models\ImplementationSettings::updateOrCreate(
        ['configuration_id' => $config_id],
        [
            'company_id' => $company_id,
            'last_scan_date' => now()
        ]
    );
    
    return response()->json(['success' => true]);
}

    /**
     * Automatically add all detected services to DPS
     */
    private function autoAddServicesToDPS($company_id, $config_id)
    {
        // Get all services that have a name but are not added yet
        $scansToAdd = DpsScan::where('configuration_id', $config_id)
            ->where('status', 'todo')
            ->whereNotNull('service_name')
            ->get();
        
        $scanner = new \App\Services\DpsScanner();
        
        foreach ($scansToAdd as $scan) {
            $scanner->addServiceToDPS($scan->id, $company_id, $config_id);
        }
    }
    
    /**
     * Add a service from scan to DPS
     */
    public function addService(Request $request, $company_id, $config_id, $scan_id)
    {
        // Find the scan record
        $scan = DpsScan::findOrFail($scan_id);
        
        // Check if service already exists in DPS to prevent duplicates
        $existingService = DataProcessingServices::where('configuration_id', $config_id)
            ->where('name', $scan->service_name)
            ->first();
        
        if ($existingService) {
            // Just update the scan status to 'added'
            $scan->status = 'added';
            $scan->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Service already exists',
                'service' => $existingService
            ]);
        }
        
        // Create a new data processing service
        $service = DataProcessingServices::create([
            'company_id' => $company_id,
            'configuration_id' => $config_id,
            'name' => $scan->service_name ?? 'Unknown Service',
            'template_id' => null,
            'category' => $scan->category ?? 'functional',
            'status' => 'active',
            'is_essential' => false,
            'data_sharing_eu' => false,
            'accepted_by_default' => false
        ]);
        
        // Update the scan status to 'added'
        $scan->status = 'added';
        $scan->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Service added successfully',
            'service' => $service
        ]);
    }

    /**
     * Update a category
     */
    public function updateCategory(Request $request, $company_id, $config_id, $category_id)
    {
        $category = ServiceCategory::findOrFail($category_id);
        
        // Ensure the category belongs to the configuration
        if ($category->configuration_id != $config_id) {
            return response()->json([
                'success' => false,
                'message' => 'Category does not belong to this configuration'
            ], 403);
        }
        
        // Update category
        $category->update([
            'name' => $request->input('name', $category->name),
            'description' => $request->input('description', $category->description),
            'is_essential' => $request->input('is_essential', $category->is_essential)
        ]);
        
        // Handle translations if provided
        if ($request->has('translations')) {
            foreach ($request->input('translations') as $lang => $fields) {
                foreach ($fields as $field => $value) {
                    CategoryTranslation::updateOrCreate(
                        [
                            'category_id' => $category_id,
                            'language' => $lang,
                            'field' => $field
                        ],
                        [
                            'translation' => $value
                        ]
                    );
                }
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully'
        ]);
    }
}