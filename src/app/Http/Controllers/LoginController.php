<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CartItem;

class LoginController extends Controller
{
    public function showLoginForm(Request $request)
    {
        if ($request->has('checkout')) {
            $request->session()->put('checkout_redirect', true);
        }
        return view('login');
    }

    public function __invoke(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($request->session()->has('checkout_redirect')) {
                $cart = session()->get('cart', []);

                foreach ($cart as $productId => $item) {
                CartItem::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'product_id' => $productId,
                    ],
                    [
                        'quantity' => $item['quantity'],
                    ]
                );
            }

                session()->forget('cart');
                $request->session()->forget('checkout_redirect');

                return redirect()->route('checkout.details');
            }

            return redirect()->intended('/'); 
        }

        return back()->withErrors([
            'email' => 'A megadott belépési adatok nem helyesek.',
        ])->onlyInput('email');
    }
}