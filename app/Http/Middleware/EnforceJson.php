<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;

class EnforceJson
{
    /**
     * Enforce json
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $request->headers->set('Accept', 'application/json');

        return $next($request);
    }
}
