<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order; // feltételezve, hogy van Order modell
use App\Models\User;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 💡 Dummy rendelések (amíg nincs adatbázis)
        $orders = collect([
            (object)[
                'id' => 101,
                'created_at' => now()->subDays(3),
                'total' => 15990,
                'status' => 'completed',
            ],
            (object)[
                'id' => 102,
                'created_at' => now()->subDay(),
                'total' => 24990,
                'status' => 'pending',
            ],
            (object)[
                'id' => 103,
                'created_at' => now(),
                'total' => 8900,
                'status' => 'processing',
            ],
        ]);

        return view('userdashboard', compact('user', 'orders'));
    }


    public function editProfile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }
    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'firstname' => 'required|string|max:100',
            'lastname' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'profile_image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profiles', 'public');
            $user->profile_image = $path;
        }

        // assign properties explicitly and persist with save() to avoid undefined method errors
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->save();

        return redirect()->route('userdashboard')->with('success', 'Profil sikeresen frissítve!');
    }
}
