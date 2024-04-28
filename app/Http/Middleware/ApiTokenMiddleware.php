<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;

class ApiTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (!$request->header('Authorization')) {
            return response()->json(['error' => 'Authorization header is missing'], 401);
        }

        $token = $request->header('Authorization');

        if (!Auth::onceUsingId($token)) {
            return response()->json(['error' => 'Invalid API token'], 401);
        }

        return $next($request);
    }
}
