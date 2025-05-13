<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NewsletterSubscription;

class NewsletterSubscriptionController extends Controller
{
    // Store a new newsletter subscription
    public function store(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'email' => 'required|email|unique:newsletter_subscriptions,email',
        ]);

        // Create a new subscription
        $subscription = NewsletterSubscription::create([
            'email' => $validated['email'],
        ]);

        // Return the response
        return response()->json(['message' => 'Subscription successful', 'data' => $subscription], 201);
    }

    // Toggle the is_active status of the subscription
    public function toggleStatus($id)
    {
        // Find the subscription by ID
        $subscription = NewsletterSubscription::find($id);

        if (!$subscription) {
            return response()->json(['error' => 'Subscription not found'], 404);
        }

        // Toggle the is_active status
        $subscription->is_active = !$subscription->is_active;
        $subscription->save();

        // Return the updated subscription data
        return response()->json(['message' => 'Subscription status updated', 'data' => $subscription]);
    }
}
