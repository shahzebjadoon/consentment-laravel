<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Domains\Auth\Models\User;

class CompanyController extends Controller
{
    public function store(Request $request)
{
    try {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'zip_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
        ]);
        
        DB::beginTransaction();
        
        // Create company
        $company = Company::create([
            'name' => $request->name,
            'street' => $request->street,
            'city' => $request->city,
            'zip_code' => $request->zip_code,
            'country' => $request->country,
            'billing_account' => $request->billing_account,
        ]);
        
        // Add current user as admin
        DB::table('company_user')->insert([
            'company_id' => $company->id,
            'user_id' => auth()->id(),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // If additional user email is provided, add them too
        if ($request->filled('email')) {
            $user = User::where('email', $request->email)->first();
            
            if ($user) {
                DB::table('company_user')->insert([
                    'company_id' => $company->id,
                    'user_id' => $user->id,
                    'role' => $request->permission ?? 'user',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        
        // Create default service categories for the new company
        $this->createDefaultServiceCategories($company->id);
        
        DB::commit();
        
        return response()->json([
            'success' => true,
            'message' => 'Company created successfully',
        ]);
        
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Company creation error: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'An error occurred: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Create default service categories for a company
 * 
 * @param int $companyId
 * @return void
 */
private function createDefaultServiceCategories($companyId)
{
    // You may need to adjust the configuration_id based on your system's setup
    $configId = 1; // Default configuration ID
    
    $categories = [
        [
            'name' => 'Essential',
            'description' => 'These cookies are necessary for the website to function and cannot be switched off in our systems.',
            'identifier' => 'essential',
            'is_essential' => 1,
            'is_default' => 1,
            'order_index' => 1,
        ],
        [
            'name' => 'Functional',
            'description' => 'These cookies enable the website to provide enhanced functionality and personalization.',
            'identifier' => 'functional',
            'is_essential' => 0,
            'is_default' => 1,
            'order_index' => 2,
        ],
        [
            'name' => 'Analytics',
            'description' => 'These cookies help us to understand how visitors interact with our website.',
            'identifier' => 'analytics',
            'is_essential' => 0,
            'is_default' => 1,
            'order_index' => 3,
        ],
        [
            'name' => 'Marketing',
            'description' => 'These technologies are used by advertisers to serve ads that are relevant to your interests.',
            'identifier' => 'marketing',
            'is_essential' => 0,
            'is_default' => 1,
            'order_index' => 4,
        ],
        [
            'name' => 'Other',
            'description' => 'Miscellaneous services that do not fit into other categories but still process user data.',
            'identifier' => 'other',
            'is_essential' => 0,
            'is_default' => 1,
            'order_index' => 5,
        ],
    ];
    
    $now = now();
    
    foreach ($categories as $category) {
        DB::table('service_categories')->insert([
            'company_id' => $companyId,
            'configuration_id' => $configId,
            'name' => $category['name'],
            'description' => $category['description'],
            'identifier' => $category['identifier'],
            'is_essential' => $category['is_essential'],
            'is_default' => $category['is_default'],
            'order_index' => $category['order_index'],
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
}