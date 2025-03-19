<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfBlocked
{
    public function handle($request, Closure $next)
    {
        if (Auth::user()->in_estado == 0) {
            Auth::logout();
            $request->session()->invalidate();

            return redirect('/');
        }

        return $next($request);
    }
}
