<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Configuration;
use App\Models\AppearanceSettings;
use App\Models\ContentSettings;
use App\Models\DataProcessingServices;
use App\Models\ServiceCategories;

class PreviewController extends Controller
{
    /**
     * Display a preview of the consent banner for a configuration
     */
    public function preview($config_id)
    {
        $configuration = Configuration::findOrFail($config_id);
        
        return view('frontend.preview.consent-preview', [
            'configuration' => $configuration,
        ]);
    }
}