<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function store(Request $request)
    {
        // Validáció
        $validated = $request->validate([
            'lastname' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'confirmed',
                'min:6'
            ],
            'account_type' => 'required|in:personal,business',
            'phone_country_code' => 'required|string|max:10',
            'phone_number' => 'required|string|max:20',

            // billing cím
            'billing_country' => 'required|string|max:255',
            'billing_zip' => 'required|string|max:20',
            'billing_city' => 'required|string|max:255',
            'billing_street_name' => 'required|string|max:255',
            'billing_street_type' => 'required|string|max:50',
            'billing_house_number' => 'required|string|max:20',
            'billing_building' => 'nullable|string|max:50',
            'billing_floor' => 'nullable|string|max:50',
            'billing_door' => 'nullable|string|max:50',

            // shipping cím
            'shipping_country' => 'required|string|max:255',
            'shipping_zip' => 'required|string|max:20',
            'shipping_city' => 'required|string|max:255',
            'shipping_street_name' => 'required|string|max:255',
            'shipping_street_type' => 'required|string|max:50',
            'shipping_house_number' => 'required|string|max:20',
            'shipping_building' => 'nullable|string|max:50',
            'shipping_floor' => 'nullable|string|max:50',
            'shipping_door' => 'nullable|string|max:50',

            // ÁSZF
            'terms' => 'accepted',
            'privacy' => 'accepted',
            'newsletter' => 'nullable'
        ]);

        // Létrehozás
        $user = User::create([
            'lastname' => $validated['lastname'],
            'firstname' => $validated['firstname'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'account_type' => $validated['account_type'],
            'phone_country_code' => $validated['phone_country_code'],
            'phone_number' => $validated['phone_number'],

            // billing
            'billing_country' => $validated['billing_country'],
            'billing_zip' => $validated['billing_zip'],
            'billing_city' => $validated['billing_city'],
            'billing_street_name' => $validated['billing_street_name'],
            'billing_street_type' => $validated['billing_street_type'],
            'billing_house_number' => $validated['billing_house_number'],
            'billing_building' => $request->billing_building,
            'billing_floor' => $request->billing_floor,
            'billing_door' => $request->billing_door,

            // shipping
            'shipping_country' => $validated['shipping_country'],
            'shipping_zip' => $validated['shipping_zip'],
            'shipping_city' => $validated['shipping_city'],
            'shipping_street_name' => $validated['shipping_street_name'],
            'shipping_street_type' => $validated['shipping_street_type'],
            'shipping_house_number' => $validated['shipping_house_number'],
            'shipping_building' => $request->shipping_building,
            'shipping_floor' => $request->shipping_floor,
            'shipping_door' => $request->shipping_door,
        ]);

        // Beléptetés vagy átirányítás
        Auth::login($user);

        return redirect('/')->with('success', 'Sikeres regisztráció!');
    }
}
