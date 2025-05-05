<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PricePlan; // Ensure you have the correct namespace for PricePlan
use Illuminate\Support\Facades\Auth; // Import Auth facade
use App\Models\Subscription; // Ensure you have the correct namespace for Subscription
class PricePlanController extends Controller
{

    public function index()
    {
        $plans = PricePlan::all();
        $user = Auth::user();
        $subscription = null; // Initialize subscription to null
        if (!$user) {
            // Handle the case where the user is not authenticated
            return view('frontend.prices.priceplans', compact('plans', 'user', 'subscription'));
           
        }

       
            $subscription = Subscription::where('user_id', $user->id)
                ->orderBy('expire_at', 'desc') // Order by expire_at to get the latest
                ->first();
        


        return view('frontend.prices.priceplans', compact('plans', 'user', 'subscription'));
    }
}
