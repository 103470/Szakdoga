<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PhonePrefix;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\DeliveryOption;
use App\Models\PaymentOption;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Address;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Payment;use Stripe\Webhook;

class CheckoutController extends Controller
{
    public function choice()
    {
        return view('checkout.choice');
    }

    public function details()
    {
        $user = auth()->user();
        $phonePrefixes = PhonePrefix::all();
        return view('checkout.details', compact('user', 'phonePrefixes'));
    }

    public function submitDetails(Request $request)
    {
        $data = $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_email' => 'required|email|max:255',
            'shipping_phone_prefix' => 'required|string|max:10',
            'shipping_phone' => 'required|string|max:20',
            'shipping_street_type' => 'required|string|max:100',
            'shipping_street_name' => 'required|string|max:100',
            'shipping_house_number' => 'required|string|max:20',
            'shipping_building' => 'nullable|string|max:50',
            'shipping_floor' => 'nullable|string|max:50',
            'shipping_door' => 'nullable|string|max:50',
            'shipping_city' => 'required|string|max:255',
            'shipping_zip' => 'required|string|max:20',
            'shipping_country' => 'required|string|max:100',

            'billing_name' => 'required|string|max:255',
            'billing_email' => 'required|email|max:255',
            'billing_phone_prefix' => 'required|string|max:10',
            'billing_phone' => 'required|string|max:20',
            'billing_street_type' => 'required|string|max:100',
            'billing_street_name' => 'required|string|max:100',
            'billing_house_number' => 'required|string|max:20',
            'billing_building' => 'nullable|string|max:50',
            'billing_floor' => 'nullable|string|max:50',
            'billing_door' => 'nullable|string|max:50',
            'billing_city' => 'required|string|max:255',
            'billing_zip' => 'required|string|max:20',
            'billing_country' => 'required|string|max:100',
        ]);

        session(['checkout_data' => $data]);

        return redirect()->route('checkout.payment'); 
    }

     public function payment()
    {
        $checkoutData = session('checkout_data');

        if (!$checkoutData) {
            return redirect()->route('checkout.details');
        }

        return view('checkout.payment', compact('checkoutData'));
    }

    protected function generateOrderNumber($length = 8)
    {
        return strtoupper(substr(bin2hex(random_bytes($length)), 0, $length));
    }


    public function finalize(Request $request)
    {
        $checkoutData = session('checkout_data');

        if (!$checkoutData) {
            return redirect()->route('checkout.details');
        }

        $shippingAddress = Address::create([
            'country' => $checkoutData['shipping_country'],
            'zip' => $checkoutData['shipping_zip'],
            'city' => $checkoutData['shipping_city'],
            'street_type' => $checkoutData['shipping_street_type'] ?? null,
            'street_name' => $checkoutData['shipping_street_name'] ?? null,
            'house_number' => $checkoutData['shipping_house_number'] ?? null,
            'building' => $checkoutData['shipping_building'] ?? null,
            'floor' => $checkoutData['shipping_floor'] ?? null,
            'door' => $checkoutData['shipping_door'] ?? null,
        ]);

        $billingAddress = Address::create([
            'country' => $checkoutData['billing_country'],
            'zip' => $checkoutData['billing_zip'],
            'city' => $checkoutData['billing_city'],
            'street_type' => $checkoutData['billing_street_type'] ?? null,
            'street_name' => $checkoutData['billing_street_name'] ?? null,
            'house_number' => $checkoutData['billing_house_number'] ?? null,
            'building' => $checkoutData['billing_building'] ?? null,
            'floor' => $checkoutData['billing_floor'] ?? null,
            'door' => $checkoutData['billing_door'] ?? null,
        ]);

        $order = new Order();
        $order->user_id = Auth::id() ?? null;
        $order->shipping_name = $checkoutData['shipping_name'];
        $order->shipping_email = $checkoutData['shipping_email'];
        $order->shipping_phone_prefix = $checkoutData['shipping_phone_prefix'] ?? '+36';
        $order->shipping_phone = $checkoutData['shipping_phone'];
        $order->shipping_address_id = $shippingAddress->id;

        $order->billing_name = $checkoutData['billing_name'];
        $order->billing_email = $checkoutData['billing_email'];
        $order->billing_phone_prefix = $checkoutData['billing_phone_prefix'] ?? '+36';
        $order->billing_phone = $checkoutData['billing_phone'];
        $order->billing_address_id = $billingAddress->id;

        $order->delivery_option = $request->input('delivery_option', 'courier');
        $order->payment_option = $request->input('payment_option', 'card');

        $order->order_number = $this->generateOrderNumber(8);
        $order->save();

        $cartItems = Auth::check() 
            ? CartItem::where('user_id', Auth::id())->get() 
            : session('cart', []);

        foreach ($cartItems as $item) {
            if (Auth::check()) {
                $product = $item->product;
                $quantity = $item->quantity;
            } else {
                $product = Product::find($item['product_id']); 
                $quantity = $item['quantity'];
            }

            if (!$product) continue;

            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $product->id;
            $orderItem->quantity = $quantity;
            $orderItem->price = $product->price;
            $orderItem->save();
        }

        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())->delete();
        } else {
            session()->forget('cart');
        }

        session()->forget('checkout_data');

        return redirect()->route('checkout.success', ['order_id' => $order->id]);
    }

    public function showPaymentPage()
    {
         if (Auth::check()) {
            $cartItems = CartItem::with('product')
                ->where('user_id', Auth::id())
                ->get();
        } else {
            $sessionCart = session('cart', []);
            $cartItems = collect($sessionCart)->map(function ($item) {
                $product = Product::find($item['product_id']);
                return (object)[
                    'product' => $product,
                    'quantity' => $item['quantity'] ?? 0
                ];
            });
        }

        $subtotal = $cartItems->sum(function ($item) {
            return ($item->product->price ?? 0) * $item->quantity;
        });

        $deliveryOptions = DeliveryOption::where('is_active', true)->get();
        $paymentOptions = PaymentOption::where('is_active', true)->get();

        return view('checkout.payment', compact('deliveryOptions', 'paymentOptions', 'subtotal'));
    }

    public function success(Request $request)
    {
        $sessionId = $request->input('session_id');

        $payment = Payment::where('transaction_id', $sessionId)->first();

        if (!$payment || !$payment->order) {
            return redirect()->route('checkout.choice');
        }

        $order = $payment->order;

        return view('checkout.success', compact('order'));
    }


    public function createStripeCheckoutSession(Request $request)
    {
        $checkoutData = session('checkout_data');
        if (!$checkoutData) {
            return response()->json(['error' => 'No checkout data'], 400);
        }

        if (Auth::check()) {
            $cartItems = CartItem::with('product')->where('user_id', Auth::id())->get();
        } else {
            $sessionCart = session('cart', []);
            $cartItems = collect($sessionCart)->map(fn($item) => [
                'product' => Product::find($item['product_id']),
                'quantity' => $item['quantity']
            ]);
        }

        $lineItems = [];
        foreach ($cartItems as $item) {
            if (Auth::check()) {
                $product = $item->product;
                $quantity = $item->quantity;
            } else {
                $product = $item['product'];
                $quantity = $item['quantity'];
            }

            if (!$product) continue;

            $lineItems[] = [
                'price_data' => [
                    'currency' => 'huf',
                    'product_data' => ['name' => $product->name],
                    'unit_amount' => intval($product->price * 100),
                ],
                'quantity' => $quantity,
            ];
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.payment'),
            'metadata' => [
                'user_id' => Auth::id() ?? null,
            ],
        ]);

        $order = Order::where('user_id', Auth::id())->latest()->first(); 
        if ($order) {
            Payment::create([
                'order_id' => $order->id,
                'payment_method' => 'stripe', 
                'amount' => $cartItems->sum(fn($i) => ($i['product']->price ?? 0) * $i['quantity']),
                'transaction_id' => $session->id, 
                'payment_status' => 'pending', 
            ]);
        }

        return response()->json(['url' => $session->url]);
    }

    public function handleWebhook(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            $payment = Payment::where('transaction_id', $session->id)->first();
            if ($payment) {
                $payment->payment_status = 'succeeded';
                $payment->save();
            }
        }

        return response()->json(['status' => 'success']);
    }



}
