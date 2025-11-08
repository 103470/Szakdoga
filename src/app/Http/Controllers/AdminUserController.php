<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
        $user->update([
            'is_admin' => $request->has('is_admin'),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Felhasználó frissítve!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Felhasználó törölve!');
    }
}
