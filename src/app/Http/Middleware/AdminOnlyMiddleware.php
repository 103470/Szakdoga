<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminOnlyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (! $user || ! ($user->is_admin ?? false)) {
            return redirect()->route('login')->with('error', 'Access denied.');
        }

        return $next($request);
    }
}
