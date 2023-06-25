<?php

namespace App\Http\Middleware;

use Closure;

class ProtectAPI
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $expectedApiKey = config('app.api_key'); // Set your expected API key here
        $providedApiKey = $request->header('X-Api-Key');

        if ($providedApiKey !== $expectedApiKey) {
            return response()->json(['error' => 'Unauthorized access.'], 401);
        }

        return $next($request);
    }
}
