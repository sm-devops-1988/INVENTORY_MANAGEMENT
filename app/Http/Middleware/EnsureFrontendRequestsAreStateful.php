<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureFrontendRequestsAreStateful
{
    /**
     * Traite la requête entrante.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Si l'API est en mode "stateful" (avec les cookies), l'authentification sera gérée via Sanctum
        if ($request->hasHeader('X-Sanctum-Token') || $request->bearerToken()) {
            $request->headers->set('Authorization', 'Bearer ' . $request->bearerToken());
        }

        return $next($request);
    }
}
