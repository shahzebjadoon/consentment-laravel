<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Configuration;

class ImplementationController extends Controller
{
    /**
     * Display the Script Tag page
     */
    public function scriptTag($company_id, $config_id)
    {
        $company = Company::findOrFail($company_id);
        $configuration = Configuration::findOrFail($config_id);
        
        return view('frontend.implementation.script-tag', [
            'company' => $company,
            'configuration' => $configuration,
            'activeTab' => 'script-tag'
        ]);
    }
    
    /**
     * Display the Embeddings page
     */
    public function embeddings($company_id, $config_id)
    {
        $company = Company::findOrFail($company_id);
        $configuration = Configuration::findOrFail($config_id);
        
        return view('frontend.implementation.embeddings', [
            'company' => $company,
            'configuration' => $configuration,
            'activeTab' => 'embeddings'
        ]);
    }
    
    /**
     * Display the Data Layer & Events page
     */
    public function dataLayer($company_id, $config_id)
    {
        $company = Company::findOrFail($company_id);
        $configuration = Configuration::findOrFail($config_id);
        
        return view('frontend.implementation.data-layer', [
            'company' => $company,
            'configuration' => $configuration,
            'activeTab' => 'data-layer'
        ]);
    }
    
    /**
     * Display the A/B Testing page
     */
    public function abTesting($company_id, $config_id)
    {
        $company = Company::findOrFail($company_id);
        $configuration = Configuration::findOrFail($config_id);
        
        return view('frontend.implementation.ab-testing', [
            'company' => $company,
            'configuration' => $configuration,
            'activeTab' => 'ab-testing'
        ]);
    }
}