<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Address;

class RegisterController extends Controller
{
    public function store(Request $request)
    {
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
            'accept_tos' => 'accepted',
            'accept_privacy' => 'accepted',
            'subscribe_newsletter' => 'nullable',

            'billing_country' => 'required|string|max:255',
            'billing_zip' => 'required|string|max:20',
            'billing_city' => 'required|string|max:255',
            'billing_street_name' => 'required|string|max:255',
            'billing_street_type' => 'required|string|max:50',
            'billing_house_number' => 'required|string|max:20',
            'billing_building' => 'nullable|string|max:50',
            'billing_floor' => 'nullable|string|max:50',
            'billing_door' => 'nullable|string|max:50',

            'shipping_country' => 'required|string|max:255',
            'shipping_zip' => 'required|string|max:20',
            'shipping_city' => 'required|string|max:255',
            'shipping_street_name' => 'required|string|max:255',
            'shipping_street_type' => 'required|string|max:50',
            'shipping_house_number' => 'required|string|max:20',
            'shipping_building' => 'nullable|string|max:50',
            'shipping_floor' => 'nullable|string|max:50',
            'shipping_door' => 'nullable|string|max:50',
        ]);

        $billingAddress = Address::create([
            'country' => $validated['billing_country'],
            'zip' => $validated['billing_zip'],
            'city' => $validated['billing_city'],
            'street_name' => $validated['billing_street_name'],
            'street_type' => $validated['billing_street_type'] ?? null,
            'house_number' => $validated['billing_house_number'],
            'building' => $validated['billing_building'] ?? null,
            'floor' => $validated['billing_floor'] ?? null,
            'door' => $validated['billing_door'] ?? null,
        ]);

        $shippingAddress = Address::create([
            'country' => $validated['shipping_country'],
            'zip' => $validated['shipping_zip'],
            'city' => $validated['shipping_city'],
            'street_name' => $validated['shipping_street_name'],
            'street_type' => $validated['shipping_street_type'] ?? null,
            'house_number' => $validated['shipping_house_number'],
            'building' => $validated['shipping_building'] ?? null,
            'floor' => $validated['shipping_floor'] ?? null,
            'door' => $validated['shipping_door'] ?? null,
        ]);

        $user = User::create([
            'lastname' => $validated['lastname'],
            'firstname' => $validated['firstname'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'account_type' => $validated['account_type'],
            'phone_country_code' => $validated['phone_country_code'],
            'phone_number' => $validated['phone_number'],
            'billing_address_id' => $billingAddress->id,
            'shipping_address_id' => $shippingAddress->id,
        ]);


        Auth::login($user);

        return redirect('/')->with('success', 'Sikeres regisztráció!');
    }
}
