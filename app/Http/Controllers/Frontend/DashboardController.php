<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;

class DashboardController extends Controller
{
    public function index()
    {
        // Get companies the logged-in user has access to
        $user = auth()->user();
        
        if ($user->role === 'super_admin') {
            // Super admins can see all companies
            $companies = Company::all();
        } else {
            // Regular users only see companies they have access to
            try {
                $companies = Company::select('companies.*')
                    ->join('company_user', 'companies.id', '=', 'company_user.company_id')
                    ->where('company_user.user_id', auth()->id())
                    ->get();
            } catch (\Exception $e) {
                $companies = [];
            }
        }
        
        // If no companies, initialize as empty array
        if ($companies === null) {
            $companies = [];
        }
        
        return view('frontend.dashboard.index', compact('companies'));
    }
}