<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    public function createPayment(Order $order)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $paymentIntent = PaymentIntent::create([
            'amount' => $order->total * 100, 
            'currency' => 'huf',
            'metadata' => [
                'order_id' => $order->id
            ],
        ]);

        return response()->json([
            'clientSecret' => $paymentIntent->client_secret
        ]);
    }
}
