<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::paginate(10); // lapozva listázás
        return view('adminusersindex', compact('users'));
    }

    public function edit(User $user)
    {
        return view('adminusersedit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'lastname' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'account_type' => 'required|in:personal,business',
            'phone_country_code' => 'required|string|max:10',
            'phone_number' => 'required|string|max:20',

            // billing
            'billing_country' => 'required|string|max:255',
            'billing_zip' => 'required|string|max:20',
            'billing_city' => 'required|string|max:255',
            'billing_street_name' => 'required|string|max:255',
            'billing_street_type' => 'required|string|max:50',
            'billing_house_number' => 'required|string|max:20',
            'billing_building' => 'nullable|string|max:50',
            'billing_floor' => 'nullable|string|max:50',
            'billing_door' => 'nullable|string|max:50',

            // shipping
            'shipping_country' => 'required|string|max:255',
            'shipping_zip' => 'required|string|max:20',
            'shipping_city' => 'required|string|max:255',
            'shipping_street_name' => 'required|string|max:255',
            'shipping_street_type' => 'required|string|max:50',
            'shipping_house_number' => 'required|string|max:20',
            'shipping_building' => 'nullable|string|max:50',
            'shipping_floor' => 'nullable|string|max:50',
            'shipping_door' => 'nullable|string|max:50',

            // új jelszó csak ha változtatni akar
            'password' => 'nullable|confirmed|min:6',

            // profilkép
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

            // alapadatok
            // Alapadatok frissítése (a password kivételével)
        $user->fill(collect($validated)->except('password')->toArray());

        // Ha adtak meg új jelszót, akkor külön frissítjük
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }



        // ha új kép
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profiles', 'public');
            $user->profile_image = $path;
        }

        // admin checkbox (ha nincs bepipálva, false lesz)
        $user->is_admin = $request->has('is_admin');

        $user->save();

        return redirect()->route('admin.users.index')
                         ->with('success','Felhasználó sikeresen frissítve!');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')
                         ->with('success','Felhasználó törölve!');
    }
}
