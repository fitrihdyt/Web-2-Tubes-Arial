<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NotAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user() && auth()->user()->isAdmin) {
            abort(403);
        }

        return $next($request);
    }
}
