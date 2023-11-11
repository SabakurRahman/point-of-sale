<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StoreAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        $store_id = $request->route('store_id'); // Assuming you're passing store_id as a route parameter
        $user = Auth::user();
        // Check if the authenticated user owns the store
        if ($user->user_stores->count() > 0) {
            return $next($request);
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }
}
