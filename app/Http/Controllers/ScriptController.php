<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ScriptController extends Controller
{
    /**
     * Serve JavaScript files for the consent management platform
     */
    public function serveScript($script)
    {
        $basePath = public_path('js/cmp');
        $filePath = $basePath . '/' . $script;
        
        if (!file_exists($filePath)) {
            return response('Script not found', 404);
        }
        
        $content = file_get_contents($filePath);
        $response = Response::make($content, 200);
        $response->header('Content-Type', 'application/javascript');
        
        // Add cache control headers for production
        if (app()->environment('production')) {
            $response->header('Cache-Control', 'public, max-age=3600');
        } else {
            $response->header('Cache-Control', 'no-store, no-cache, must-revalidate');
        }
        
        return $response;
    }
}