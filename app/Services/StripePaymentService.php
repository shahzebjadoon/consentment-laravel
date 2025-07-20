<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class StripePaymentService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Create a Stripe Checkout Session.
     *
     * @param array $params
     * @return \Stripe\Checkout\Session
     */
    public function createCheckoutSession(array $params)

    {

       
        return StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => $params['currency'] ?? 'gbp', // GBP as default
                    'product_data' => [
                        'name' => $params['product_name'],
                    ],
                    'unit_amount' => $params['amount'], // in pence
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $params['success_url'],
            'cancel_url' => $params['cancel_url'],
        ]);
    }
}
// This service class handles the creation of Stripe Checkout Sessions.
// It can be used in controllers to initiate payments without cluttering the controller logic.