<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        // Validálás
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Próbálja meg bejelentkeztetni
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/'); // siker esetén főoldalra
        }

        // Ha nem sikerül
        return back()->withErrors([
            'email' => 'A megadott belépési adatok nem helyesek.',
        ])->onlyInput('email');
    }
}