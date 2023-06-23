<?php

namespace App\Http\Middleware;

use App\Traits\GeneralTraits;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckToken
{

    use GeneralTraits;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string|null $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ?string $guard = null)
    {
        try {
            if ($guard) {
                Auth::shouldUse($guard);
            }

            $user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return $this->returnError(401,'invalid token');
        }

        if (!$user) {
            return $this->returnError(401,'invalid token');
        }

        return $next($request);
    }
}
