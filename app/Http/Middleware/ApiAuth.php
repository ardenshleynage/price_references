<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     * @param Closure(): void $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('X-Token');
        $userId = $request->header('X-User-ID');

        if (!$token || !$userId) {
            return response()->json(['error' => 'Non autorisé'], 401);
        }

        return $next($request);

    }
}
