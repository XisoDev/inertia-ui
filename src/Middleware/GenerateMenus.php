<?php

namespace Xiso\InertiaUI\Middleware;

use Closure;
use Illuminate\Http\Request;

class GenerateMenus
{
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}
