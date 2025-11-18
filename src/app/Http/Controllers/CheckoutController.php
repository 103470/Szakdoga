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
use App\Enums\OrderStatus;

class CheckoutController extends Controller
{
    public function __construct()
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
    }


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

        $order = Order::create([
            'user_id' => Auth::id() ?? null,
            'shipping_name' => $checkoutData['shipping_name'],
            'shipping_email' => $checkoutData['shipping_email'],
            'shipping_phone_prefix' => $checkoutData['shipping_phone_prefix'] ?? '+36',
            'shipping_phone' => $checkoutData['shipping_phone'],
            'shipping_address_id' => $shippingAddress->id,
            'billing_name' => $checkoutData['billing_name'],
            'billing_email' => $checkoutData['billing_email'],
            'billing_phone_prefix' => $checkoutData['billing_phone_prefix'] ?? '+36',
            'billing_phone' => $checkoutData['billing_phone'],
            'billing_address_id' => $billingAddress->id,
            'delivery_option' => $request->input('delivery_option', 'courier'),
            'payment_option' => $request->input('payment_option', 'card'),
            'order_number' => $this->generateOrderNumber(8),
            'order_status' => OrderStatus::REGISTERED,
        ]);

        $cartItems = Auth::check()
            ? CartItem::with('product')->where('user_id', Auth::id())->get()
            : session('cart', []);

        $amount = 0;
        foreach ($cartItems as $item) {
            $product = Auth::check() ? $item->product : Product::find($item['product_id']);
            $quantity = Auth::check() ? $item->quantity : $item['quantity'];
            if (!$product) continue;

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price,
            ]);

            $amount += $product->price * $quantity;
        }

        if ($order->payment_option === 'card') {
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'mode' => 'payment',
                'success_url' => route('stripe.pending', [], true)
                    . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('stripe.cancel', ['order_id' => $order->id], true),
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'huf',
                        'product_data' => [
                            'name' => 'Rendelés #' . $order->order_number,
                        ],
                        'unit_amount' => $amount * 100,
                    ],
                    'quantity' => 1,
                ]],
            ]);

            $payment = Payment::create([
                'order_id' => $order->id,
                'payment_method' => $order->payment_option,
                'amount' => $amount,
                'transaction_id' => $session->id,
                'payment_status' => 'pending',
                'user_id' => Auth::id() ?? null,
            ]);

            if (Auth::check()) {
                CartItem::where('user_id', Auth::id())->delete();
            } else {
                session()->forget('cart');
            }

            session()->forget('checkout_data');

            return redirect($session->url);
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
        $orderId = $request->input('order');

        if (!$orderId) {
            return redirect()->route('checkout.payment');
        }

        $order = Order::findOrFail($orderId);

        return view('checkout.success', compact('order'));
    }

    public function createStripeCheckoutSession(Request $request)
    {
        $checkoutData = session('checkout_data');
        if (!$checkoutData) {
            return response()->json(['error' => 'Hiányzó adatok'], 400);
        }

        $order = $this->createOrder(
            $checkoutData,
            $checkoutData['delivery_option'] ?? 'courier',
            'card'
        );

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $cartItems = OrderItem::where('order_id', $order->id)->with('product')->get();

        $lineItems = $cartItems->map(function ($item) {
            return [
                'price_data' => [
                    'currency' => 'huf',
                    'product_data' => [
                        'name' => $item->product->name,
                    ],
                    'unit_amount' => intval($item->price * 100),
                ],
                'quantity' => $item->quantity,
            ];
        })->toArray();

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('stripe.pending', [], true) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('stripe.cancel', ['order_id' => $order->id], true),
        ]);

        Payment::create([
            'order_id' => $order->id,
            'payment_method' => 'card',
            'amount' => $cartItems->sum(fn($item) => $item->price * $item->quantity),
            'transaction_id' => $session->id,
            'payment_status' => 'pending',
            'user_id' => Auth::id() ?? null,
        ]);

        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())->delete();
        } else {
            session()->forget('cart');
        }

        session()->forget('checkout_data');

        return response()->json(['url' => $session->url]);
    }



    public function pending(Request $request)
    {
        $order = Order::findOrFail($request->order);
        return view('checkout.pending', compact('order'));
    }

    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = env('STRIPE_WEBHOOK_SECRET');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
            $type = $event->type;

            if ($type === 'checkout.session.completed') {
                $session = $event->data->object;

                $payment = Payment::where('transaction_id', $session->id)->first();
                if ($payment) {

                    if ($payment->payment_status === 'succeeded') {
                        return response()->json(['status' => 'already_processed']);
                    }

                    $payment->update([
                        'payment_status' => 'succeeded'
                    ]);

                    $order = $payment->order;
                    if ($order) {
                        $order->update([
                            'order_status' => OrderStatus::REGISTERED
                        ]);
                    }
                }
            }

            if ($type === 'payment_intent.payment_failed') {

                $paymentIntent = $event->data->object;
                $payment = Payment::where('payment_intent', $paymentIntent->id)->first();

                if ($payment) {
                    $payment->update(['payment_status' => 'failed']);

                    $order = $payment->order;
                    if ($order) {
                        $order->update(['order_status' => OrderStatus::PAYMENT_FAILED]);

                         $order->delete();
                    }
                }
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function cancel(Request $request)
    {
        $orderId = $request->query('order_id');
        if ($orderId) {
            $order = Order::find($orderId);
            if ($order) {
                $order->update(['order_status' => OrderStatus::PAYMENT_FAILED]);
                $order->delete(); 
            }
        }

        return redirect()->route('checkout.payment')
                        ->with('error', 'A fizetés nem sikerült, próbáld újra.');
    }





}
