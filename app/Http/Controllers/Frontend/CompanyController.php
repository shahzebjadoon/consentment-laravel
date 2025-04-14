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
}