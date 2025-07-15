<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StripePaymentService;
use App\Models\PricePlan;
use App\Models\Subscription;
use App\Models\SubscriptionHistory;
use Auth;

class ContinuePaymentController extends Controller
{



    protected $stripeService;

    public function __construct(StripePaymentService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    public function handle(Request $request)
    {
        $planId = $request->input('plan_id');

        $plan = PricePlan::where('id', $planId)->first();

        if (!$plan) {

            return redirect()->back()->withErrors(['error' => 'Invalid plan selected.']);
        }

        $session = $this->stripeService->createCheckoutSession([
            'product_name' => $plan->membership,
            'amount' => $plan->price_month * 100, // £25.00 becomes 2500 pence
            'currency' => 'gbp',
            'success_url' => route('payment.success', ['plan_id' => $planId]),
            'cancel_url' => route('payment.cancel'),
        ]);

        return redirect($session->url)
            ->with(['plan_id' => $planId]);
    }


    public function success(Request $request)
    {

        $planId = $request->query('plan_id');
        // Handle successful payment logic here
        // For example, you might want to update the user's subscription status or log the payment.

        $user = Auth::user();
        $plan = PricePlan::find($planId);


        // Check if the user already has a subscription for this plan today
        $existing = Subscription::where('user_id', $user->id)
            ->where('price_plan_id', $planId)
            ->whereDate('created_at', today())
            ->exists();

        if ($existing) {
            // Subscription already created today, prevent duplicate
            return redirect()->route('frontend.dashboard')->with('info', 'You have already activated this subscription today.');
        }

        Subscription::updateOrCreate(
            [
                'user_id' => $user->id,
                'price_plan_id' => $planId,
                'status' => 'active',
                'created_at' => now(),
            ],
            [
                'started_at' => now(),
                'price_paid' => $plan->price_month,
                'expire_at' => now()->addMonth(),
            ]
        );


        SubscriptionHistory::create([
            'user_id' => $user->id,
            'price_plan_id' => $plan->id,
            'amount' => $plan->price_month,
            'currency' => 'GBP',
            'payment_method' => 'Stripe',
            'payment_reference' => '',
            'status' => 'succeeded',
            'meta' => [
                'gateway' => 'stripe',
                'plan' => $plan->membership,
                'plan_expire_at' => now()->addMonth(),
                'plan_is_lifetime' =>  $plan->is_life_time, 
                // Optionally store additional gateway data
            ],
        ]);





        // Redirect to a success view or page
        session()->flash('success', 'Payment successful! Your subscription is now active.');

        return redirect()->route('frontend.dashboard');
    }
}
