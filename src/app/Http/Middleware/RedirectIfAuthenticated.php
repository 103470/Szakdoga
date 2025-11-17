<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$guards
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user();

                // Ha admin, menjen az admin dashboardra
                if ($user->is_admin ?? false) {
                    return redirect()->route('admin.dashboard');
                }

                // Egyébként user dashboardra
                return redirect()->route('user.dashboard');
            }
        }

        return $next($request);
    }
}
