<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        // Handle login logic here
        return response()->json(['message' => 'Login attempt']);
    }
}