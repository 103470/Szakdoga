<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PhonePrefix;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;

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
            'shipping_address' => 'required|string|max:500',
            'shipping_city' => 'required|string|max:255',
            'shipping_zip' => 'required|string|max:20',
            'shipping_country' => 'required|string|max:100',
            'billing_name' => 'required|string|max:255',
            'billing_email' => 'required|email|max:255',
            'billing_phone_prefix' => 'required|string|max:10',
            'billing_phone' => 'required|string|max:20',
            'billing_address' => 'required|string|max:500',
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

    public function finalize(Request $request)
    {
        $checkoutData = session('checkout_data');

        if (!$checkoutData) {
            return redirect()->route('checkout.details');
        }

        $order = new Order();
        $order->user_id = Auth::id() ?? null;
        $order->shipping_name = $checkoutData['shipping_name'];
        $order->shipping_email = $checkoutData['shipping_email'];
        $order->shipping_phone_prefix = $checkoutData['shipping_phone_prefix'] ?? '+36';
        $order->shipping_phone = $checkoutData['shipping_phone'];
        $order->shipping_address = $checkoutData['shipping_address'];
        $order->shipping_city = $checkoutData['shipping_city'];
        $order->shipping_zip = $checkoutData['shipping_zip'];
        $order->shipping_country = $checkoutData['shipping_country'];
        $order->billing_name = $checkoutData['billing_name'];
        $order->billing_email = $checkoutData['billing_email'];
        $order->billing_phone = $checkoutData['billing_phone'];
        $order->billing_phone_prefix = $checkoutData['billing_phone_prefix'] ?? '+36';
        $order->billing_address = $checkoutData['billing_address'];
        $order->billing_city = $checkoutData['billing_city'];
        $order->billing_zip = $checkoutData['billing_zip'];
        $order->billing_country = $checkoutData['billing_country'];
        $order->delivery_option = $request->input('delivery_option', 'courier');
        $order->payment_option = $request->input('payment_option', 'card');

        $order->save();

        $cartItems = Auth::check() 
            ? CartItem::where('user_id', Auth::id())->get() 
            : session('cart', []);

            foreach ($cartItems as $item) {
            $product = Auth::check() ? $item->product : $item['product'];
            $quantity = Auth::check() ? $item->quantity : $item['quantity'];

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

        return redirect()->route('order.success', ['order' => $order->id])
                         ->with('success', 'A rendelésed sikeresen elküldve!');
    }
}
