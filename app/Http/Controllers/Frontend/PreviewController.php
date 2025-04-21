<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Configuration;
use App\Models\AppearanceSettings;
use App\Models\ContentSettings;
use App\Models\DataProcessingServices;
use App\Models\ServiceCategories;
use App\Models\Company;


class PreviewController extends Controller
{
    /**
     * Display a preview of the consent banner for a configuration
     */
    public function preview(Request $request, $company_id, $config_id)
    { 
        
        $company = Company::findOrFail($company_id);
        $configuration = Configuration::findOrFail($config_id);
        $appearance = AppearanceSettings::firstOrNew(['configuration_id' => $config_id]);

        
        return view('frontend.preview.consent-preview', [
            'configuration' => $configuration,
            'company' => $company,
            'activeTab' => 'layout',
            'appearance' => $appearance,
        ]);
    }


    /**
     * Display the iframe preview of the consent banner for a configuration
     */
    public function iframePreview($config_id)
    {
        $configuration = Configuration::findOrFail($config_id);
        $appearance = AppearanceSettings::firstOrNew(['configuration_id' => $config_id]);
        $content = ContentSettings::firstOrNew(['configuration_id' => $config_id]);
        $dataProcessingServices = DataProcessingServices::where('configuration_id', $config_id)->get();
        $serviceCategories = ServiceCategories::where('configuration_id', $config_id)->get();

        return view('frontend.preview.iframe-preview', [
            'configuration' => $configuration,
            'appearance' => $appearance,
            'content' => $content,
            'dataProcessingServices' => $dataProcessingServices,
            'serviceCategories' => $serviceCategories,
        ]);
    }   
}