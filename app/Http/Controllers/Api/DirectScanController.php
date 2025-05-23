<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;


use Illuminate\Http\Request;
use App\Services\DpsScannerDirect;

class DirectScanController extends Controller
{

   


    protected $scanner;
    
    public function __construct(DpsScannerDirect $scanner)
    {
        $this->scanner = $scanner;
    }
    
    public function scan(Request $request)
    {
        $request->validate([
            'domain' => 'required|url'
        ]);
        
        $results = $this->scanner->scan($request->domain);
        
        return response()->json($results);
    }


    public function capture(Request $request)
    {
        $request->validate([
            'domain' => 'required|url'
        ]);
        
        $pic = $this->scanner->capture($request->domain);
        
        return response()->json($pic);
    }
}
