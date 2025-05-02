<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Configuration;
use App\Models\User;

class CompanyAndDomainController extends Controller
{
    
    public function save(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'company_name' => 'required|string|max:255',
            'country_name' => 'required|string|max:255',
            'domain_name' => 'required|string|max:255',
            'framework' => 'required|string|max:255',
            'region' => 'required|string|max:255',

        ]);

        

        $company = new Company();
        $company->name = $validated['company_name'];
        $company->website = $validated['domain_name'];
        $company->country = $validated['country_name'];
        $company->subscription_plan = 'free';
        $company->subscription_status = 'inactive';

        $company->save();

        $user = User::findOrFail($validated['user_id']);
        $user->company_id = $company->id;
        $user->save();
        // Create a new configuration

        $configuration = new Configuration();
        $configuration->company_id = $company->id;
        $configuration->name = $validated['framework'];

        $configuration->framework_type = $validated['framework'];
        $configuration->framework_region = $validated['region'];
        $configuration->domain = $validated['domain_name'];
        $configuration->status = 'active';
        $configuration->save();

        return response()->json([$company, $configuration], 201);
    }

}
