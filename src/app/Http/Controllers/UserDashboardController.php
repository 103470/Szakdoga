<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('userdashboard', compact('user'));
    }
}
