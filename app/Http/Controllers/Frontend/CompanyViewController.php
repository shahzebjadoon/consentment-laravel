<?php



namespace App\Http\Controllers\Frontend;

use App\Models\Configuration;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;

class CompanyViewController extends Controller
{
    public function index($id)
    {
        $company = Company::findOrFail($id);
        $activeTab = 'configurations';
        
        return view('frontend.company.index', compact('company', 'activeTab'));
    }
    
    public function configurations($id)
{
    $company = Company::findOrFail($id);
    $activeTab = 'configurations';
    $configurations = Configuration::where('company_id', $id)->get();
    
    return view('frontend.company.configurations', compact('company', 'activeTab', 'configurations'));
}
    
    public function geolocation($company_id, $config_id)
    {
        $company = Company::findOrFail($company_id);
        $configuration = Configuration::findOrFail($config_id);
        $activeTab = 'geolocation';
        $rulesets = []; // In the future, fetch actual geolocation rulesets
        
        return view('frontend.company.geolocation', compact('company', 'activeTab', 'rulesets', 'configuration'));
    }
    
    public function users($id)
    {
        $company = Company::findOrFail($id);
        $activeTab = 'users';
        $users = $company->users; // Get users associated with this company
        
        return view('frontend.company.users', compact('company', 'activeTab', 'users'));
    }
    
    public function details($id)
    {
        $company = Company::findOrFail($id);
        $activeTab = 'details';
        
        return view('frontend.company.details', compact('company', 'activeTab'));
    }
}