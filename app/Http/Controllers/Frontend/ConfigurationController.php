<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Configuration;
use App\Models\Company;
use App\Models\PricePlan;
use App\Models\Subscription;
use App\Models\CompanyUser;
use Auth;

class ConfigurationController extends Controller
{
   public function store(Request $request, $companyId)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'framework_type' => 'required|string|max:50',
        'framework_region' => 'required|string|max:50',
        'domain' => 'nullable|string'
    ]);

    // logic as per payment plan
  
    $status_to_create_domain = $this->pricePlanCheck(Auth::user()->id);
    if ($status_to_create_domain == false) {
        return redirect()
            ->route('frontend.companies.configurations', $companyId)
            ->with('error', 'You need to upgrade your plan to create a new configuration with a domain.');
    }

    
    // Create the configuration with the user-provided name
    $configuration = Configuration::create([
        'company_id' => $companyId,
        'name' => $validated['name'],
        'framework_type' => $validated['framework_type'],
        'framework_region' => $validated['framework_region'],
        'domain' => $validated['domain']
    ]);
    
    return redirect()->route('frontend.companies.configurations', $companyId)
        ->with('success', 'Configuration created successfully');
}

public function edit(Request $request, $companyId, $configId)
{
    $company = Company::findOrFail($companyId);
    $configuration = Configuration::findOrFail($configId);
    $activeTab = $request->query('tab', 'setup'); // Default to setup tab
    
    return view('frontend.configurations.edit', compact('company', 'configuration', 'activeTab'));
}

public function update(Request $request, $companyId, $configId)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'data_controller' => 'nullable|string|max:255',
        'show_error_cmp' => 'boolean|nullable',
    ]);
    
    // Get the configuration
    $configuration = Configuration::findOrFail($configId);
    
    // Update the configuration
    $configuration->update([
        'name' => $validated['name'],
        'data_controller' => $validated['data_controller'],
        'show_error_cmp' => $request->has('show_error_cmp'),
    ]);
    
    return redirect()
        ->route('frontend.configurations.edit', ['company_id' => $companyId, 'config_id' => $configId])
        ->with('success', 'Configuration updated successfully');
}

public function addDomain(Request $request, $companyId, $configId)
{
    $validated = $request->validate([
        'domain' => 'required|string|max:255',
    ]);
    
    // Get the configuration
    $configuration = Configuration::findOrFail($configId);
    
    // Get current domains or initialize an empty array
    $domains = [];
    if ($configuration->domain) {
        // Try to decode existing JSON
        $existingDomains = json_decode($configuration->domain, true);
        // If it's a valid array, use it
        if (is_array($existingDomains)) {
            $domains = $existingDomains;
        } else {
            // If it's not JSON but a single domain, create a new array with the existing domain
            $domains[] = $configuration->domain;
        }
    }
    
    // Add new domain if it doesn't already exist
    if (!in_array($validated['domain'], $domains)) {
        $domains[] = $validated['domain'];
    }
    
    // Save the updated domains array as JSON
    $configuration->update([
        'domain' => json_encode($domains)
    ]);
    
    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Domain added successfully',
            'domain' => $validated['domain']
        ]);
    }
    
    return redirect()
        ->route('frontend.configurations.edit', ['company_id' => $companyId, 'config_id' => $configId])
        ->with('success', 'Domain added successfully');
}


public function updateDomain(Request $request, $companyId, $configId)
{
    $validated = $request->validate([
        'domain' => 'required|string|max:255',
    ]);
    
    // Get the configuration
    $configuration = Configuration::findOrFail($configId);
    
    // Get current domains or initialize an empty array
 
    
    // Save the updated domains array as JSON
    $configuration->update([
        'domain' => $validated['domain']
    ]);
    
    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Domain updated successfully',
            'domain' => $validated['domain']
        ]);
    }
    
    return redirect()
        ->route('frontend.configurations.edit', ['company_id' => $companyId, 'config_id' => $configId])
        ->with('success', 'Domain updated successfully');
}

public function deleteDomain(Request $request, $companyId, $configId)
{
    $validated = $request->validate([
        'domain' => 'required|string|max:255',
    ]);
    
    // Get the configuration
    $configuration = Configuration::findOrFail($configId);
    
    // Get current domains
    $domains = [];
    if ($configuration->domain) {
        $decodedDomains = json_decode($configuration->domain, true);
        if (is_array($decodedDomains)) {
            $domains = $decodedDomains;
        } else {
            $domains = [$configuration->domain];
        }
    }
    
    // Remove the domain
    $domains = array_filter($domains, function($domain) use ($validated) {
        return $domain !== $validated['domain'];
    });
    
    // Save the updated domains array (re-index to ensure it's a sequential array)
    $configuration->update([
        'domain' => json_encode(array_values($domains))
    ]);
    
    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Domain deleted successfully'
        ]);
    }
    
    return redirect()
        ->route('frontend.configurations.edit', ['company_id' => $companyId, 'config_id' => $configId])
        ->with('success', 'Domain deleted successfully');
}



public function pricePlanCheck($user_id){

    // Get the user's subscription
    $subscription = Subscription::where('user_id', $user_id)
    ->where('status', 'active')
    ->first();
    
    

    if (!$subscription) {
        return false; // No subscription found
    }
    
    // Get the price plan associated with the subscription
    $pricePlan = PricePlan::find($subscription->price_plan_id);
    
    if (!$pricePlan) {
        return false; // No price plan found
    }
    
    // Check if the price plan allows creating a new configuration with a domain
    $max_domain=$pricePlan->max_domain;

    $totalConfigurations = CompanyUser::where('user_id', $user_id)
    ->join('configurations', 'company_user.company_id', '=', 'configurations.company_id')
    ->count('configurations.id');

    if ($totalConfigurations < $max_domain) {
        return true; // User can create a new configuration with a domain
    } else {
        return false; // User has reached the limit for configurations with domains
    }
}


}