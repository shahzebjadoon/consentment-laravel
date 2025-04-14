<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Configuration;

class AnalyticsController extends Controller
{
    public function index($companyId, $configId)
    {
        $company = Company::findOrFail($companyId);
        $configuration = Configuration::findOrFail($configId);
        $activeTab = 'overview';
        
        return view('frontend.analytics.index', compact('company', 'configuration', 'activeTab'));
    }
    
    public function comparison($companyId, $configId)
    {
        $company = Company::findOrFail($companyId);
        $configuration = Configuration::findOrFail($configId);
        $activeTab = 'comparison';
        
        return view('frontend.analytics.comparison', compact('company', 'configuration', 'activeTab'));
    }
    
    public function granular($companyId, $configId)
    {
        $company = Company::findOrFail($companyId);
        $configuration = Configuration::findOrFail($configId);
        $activeTab = 'granular';
        
        return view('frontend.analytics.granular', compact('company', 'configuration', 'activeTab'));
    }
}