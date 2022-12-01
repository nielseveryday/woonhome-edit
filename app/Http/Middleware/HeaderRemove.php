<?php

namespace App\Http\Middleware;

use Closure;

class HeaderRemove
{
    /**
     * Remove possible double Access-Control-Allow-Origin in header
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        header_remove('Access-Control-Allow-Origin');

        return $next($request);
    }
}
