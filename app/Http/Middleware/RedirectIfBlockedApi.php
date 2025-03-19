<?php

namespace App\Http\Middleware;

use Closure;

class RedirectIfBlockedApi
{
    public function handle($request, Closure $next)
    {
        $user = $request->user();
        if ($user->in_estado == 0) {
            $user->api_token = null;
            $user->save();
    
            return response()->json(['message' => 'No tiene acceso']);
        }
        
        return $next($request);
    }
}
