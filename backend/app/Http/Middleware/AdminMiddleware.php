<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Log;


use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->user() || ! $request->user()->is_admin) {
            Log::info('Unauthorized access attempt.');
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    
        return $next($request);
    }

}
