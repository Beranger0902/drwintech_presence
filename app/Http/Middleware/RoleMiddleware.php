<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! $request->user()) {
            abort(403, 'Accès non autorisé.');
        }

        if (! in_array($request->user()->role, $roles)) {
            abort(403, 'Vous n’avez pas accès à cette page.');
        }

        return $next($request);
    }
}