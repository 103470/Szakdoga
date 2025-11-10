<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function choice()
    {
        return view('checkout.choice');
    }

    public function details()
    {
        return view('checkout.details');
    }

    public function submitDetails(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:500',
            'phone' => ['required', 'regex:/^[0-9+\-\s]+$/', 'max:20'],
        ]);

        return redirect()->route('checkout.payment'); 
    }
}
