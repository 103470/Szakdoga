<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserDashboardController extends \Illuminate\Routing\Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /** FELHASZNÁLÓI FŐOLDAL (DASHBOARD) **/
    public function index()
    {
        $user = Auth::user();

        $orders = collect([
            (object)[ 'id' => 101, 'created_at' => now()->subDays(3), 'total' => 15990, 'status' => 'Teljesítve' ],
            (object)[ 'id' => 102, 'created_at' => now()->subDay(), 'total' => 24990, 'status' => 'Feldolgozás alatt' ],
            (object)[ 'id' => 103, 'created_at' => now(), 'total' => 8900, 'status' => 'Függőben' ],
        ]);

        return view('userdashboard', compact('user', 'orders'));
    }

    /** PROFIL SZERKESZTÉS **/
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'firstname' => 'required|string|max:100',
            'lastname' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'account_type' => 'nullable|string',
            'phone_country_code' => 'nullable|string|max:10',
            'phone_number' => 'nullable|string|max:30',
            'password' => 'nullable|confirmed|min:6',
            'profile_image' => 'nullable|image|max:2048',

            // Számlázási és szállítási mezők
            'billing_country' => 'nullable|string|max:100',
            'billing_zip' => 'nullable|string|max:20',
            'billing_city' => 'nullable|string|max:100',
            'billing_street_name' => 'nullable|string|max:100',
            'billing_street_type' => 'nullable|string|max:50',
            'billing_house_number' => 'nullable|string|max:20',
            'billing_building' => 'nullable|string|max:50',
            'billing_floor' => 'nullable|string|max:50',
            'billing_door' => 'nullable|string|max:50',
            'shipping_country' => 'nullable|string|max:100',
            'shipping_zip' => 'nullable|string|max:20',
            'shipping_city' => 'nullable|string|max:100',
            'shipping_street_name' => 'nullable|string|max:100',
            'shipping_street_type' => 'nullable|string|max:50',
            'shipping_house_number' => 'nullable|string|max:20',
            'shipping_building' => 'nullable|string|max:50',
            'shipping_floor' => 'nullable|string|max:50',
            'shipping_door' => 'nullable|string|max:50',
            'is_admin' => 'nullable|boolean',
        ]);
        // Egyszerű mezők frissítése
        /** @var \App\Models\User $user */
        $user->fill($validated);
        $user->fill($validated);

        // Jelszó frissítése csak ha megadta
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        // Profilkép kezelése
        if ($request->hasFile('profile_image')) {
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $path = $request->file('profile_image')->store('profiles', 'public');
            $user->profile_image = $path;
        }

        $user->save();

        return redirect()->route('user.dashboard')->with('success', 'Profil sikeresen frissítve!');
    }
}
    